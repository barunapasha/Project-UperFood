# -*- coding: utf-8 -*-
"""
Scraping Berita Radar Surabaya - Program untuk mengumpulkan data berita
Dibuat oleh: Dosen Data Mining & Full Stack Engineer
Pengalaman: 30 tahun dalam data mining dan web scraping
Sertifikasi: Internasional dalam web scraping dan data extraction
Tanggal: 2024
"""

# Import library yang diperlukan
import requests
from bs4 import BeautifulSoup
import pandas as pd
import time
import random
from urllib.parse import quote, urljoin, urlparse
import re
from datetime import datetime
import math
import ssl
import urllib3
import logging
import json
from typing import List, Dict, Optional, Tuple
from dataclasses import dataclass
from pathlib import Path

# Import konfigurasi
try:
    from config import *
except ImportError:
    # Fallback konfigurasi jika file config.py tidak ada
    BASE_URL = "https://radarsurabaya.jawapos.com"
    REQUEST_TIMEOUT = 30
    MAX_RETRIES = 3
    MAX_ARTICLES_PER_PAGE = 20
    MAX_PAGES_TO_CHECK = 50
    MAX_EXTRA_ARTICLES_TO_FETCH = 200
    MAX_ARTICLES_LIMIT = 500
    MIN_DELAY = 1
    MAX_DELAY = 3
    OUTPUT_DIR = "output"
    CSV_ENCODING = "utf-8-sig"
    LOG_FILE = "scraper.log"
    LOG_LEVEL = "INFO"
    LOG_FORMAT = "%(asctime)s - %(levelname)s - %(message)s"
    MIN_TITLE_LENGTH = 10
    MIN_CONTENT_LENGTH = 100
    MIN_DATE_LENGTH = 5

# Konfigurasi logging
logging.basicConfig(
    level=getattr(logging, LOG_LEVEL),
    format=LOG_FORMAT,
    handlers=[
        logging.FileHandler(LOG_FILE),
        logging.StreamHandler()
    ]
)
logger = logging.getLogger(__name__)

# Untuk menghindari error SSL di Colab
urllib3.disable_warnings(urllib3.exceptions.InsecureRequestWarning)

@dataclass
class ArticleData:
    """Data class untuk menyimpan data artikel"""
    title: str
    url: str
    date_published: Optional[str]
    content: str
    keyword: str
    scraped_at: datetime

class RadarSurabayaScraper:
    """Class utama untuk scraping Radar Surabaya"""
    
    def __init__(self):
        self.session = requests.Session()
        headers = DEFAULT_HEADERS.copy()
        headers['Referer'] = BASE_URL
        self.session.headers.update(headers)
        
    def get_search_url(self, keyword: str, page: int = 1) -> str:
        """Fungsi untuk membuat URL pencarian Radar Surabaya berdasarkan keyword dan halaman"""
        encoded_keyword = quote(keyword)
        if page == 1:
            search_url = f"{BASE_URL}/search?q={encoded_keyword}"
        else:
            search_url = f"{BASE_URL}/search?q={encoded_keyword}&page={page}"
        return search_url

    def get_page_content(self, url: str, retries: int = MAX_RETRIES) -> Optional[str]:
        """Fungsi untuk mengambil konten halaman dengan penanganan error yang robust"""
        for attempt in range(retries):
            try:
                response = self.session.get(url, verify=False, timeout=REQUEST_TIMEOUT)
                response.raise_for_status()
                response.encoding = 'utf-8'
                return response.text
            except requests.exceptions.RequestException as e:
                logger.warning(f"Attempt {attempt + 1}/{retries} failed for {url}: {e}")
                if attempt < retries - 1:
                    time.sleep(random.uniform(RETRY_DELAY_MIN, RETRY_DELAY_MAX))
                else:
                    logger.error(f"Failed to access {url} after {retries} attempts: {e}")
                    return None
        return None

    def extract_article_links_from_page(self, search_html: str, base_url: str) -> List[Dict[str, str]]:
        """Fungsi untuk mengekstrak link artikel dari halaman pencarian Radar Surabaya"""
        if not search_html:
            return []

        soup = BeautifulSoup(search_html, 'html.parser')
        links = set()

        # Selector patterns untuk artikel
        article_selectors = ARTICLE_SELECTORS

        # Mencoba ekstraksi berdasarkan struktur konten utama
        try:
            main_content = soup.find('div', {'class': re.compile(r'content|main|container', re.I)})
            if main_content:
                article_links = main_content.find_all('a', href=True)
                for link in article_links:
                    href = link['href']
                    title = link.get_text(strip=True)
                    
                    if self._is_valid_article_link(href, title):
                        if href.startswith('/'):
                            href = urljoin(base_url, href)
                        links.add((title, href))
        except Exception as e:
            logger.warning(f"Error dalam ekstraksi konten utama: {e}")

        # Mencoba selector CSS standar
        for selector in article_selectors:
            try:
                elements = soup.select(selector)
                for element in elements:
                    if element.name == 'a' and element.has_attr('href'):
                        href = element['href']
                        title = element.get_text(strip=True)
                        
                        if self._is_valid_article_link(href, title):
                            if href.startswith('/'):
                                href = urljoin(base_url, href)
                            links.add((title, href))
            except Exception as e:
                logger.debug(f"Selector {selector} failed: {e}")
                continue

        return [{'title': title, 'url': url} for title, url in links]

    def _is_valid_article_link(self, href: str, title: str) -> bool:
        """Validasi apakah link adalah artikel yang valid"""
        if not href or not title:
            return False
            
        # Cek panjang judul
        if len(title) < MIN_TITLE_LENGTH:
            return False
            
        # Cek apakah link relatif atau dari domain yang sama
        if not (href.startswith('/') or 'radarsurabaya.jawapos.com' in href):
            return False
            
        # Cek apakah link bukan file atau halaman khusus
        if any(exclude in href.lower() for exclude in EXCLUDE_PATTERNS):
            return False
            
        return True

    def clean_text(self, text: str) -> str:
        """Fungsi untuk membersihkan teks dari karakter tidak diinginkan"""
        if not text:
            return ""
        
        # Hapus karakter khusus dan whitespace berlebih
        text = re.sub(r'\s+', ' ', text)
        text = re.sub(r'[\r\n\t]+', ' ', text)
        text = text.strip()
        
        # Hapus karakter yang tidak diinginkan
        text = re.sub(r'[^\w\s\-.,!?;:()"\']', '', text)
        
        return text

    def extract_article_details(self, article_url: str) -> Tuple[str, Optional[str], str]:
        """Fungsi untuk mengekstrak detail artikel: judul, tanggal, dan konten"""
        logger.info(f"Mengambil data dari: {article_url}")
        
        html_content = self.get_page_content(article_url)
        if not html_content:
            return "Judul tidak ditemukan", None, ""

        soup = BeautifulSoup(html_content, 'html.parser')

        # Ekstrak judul artikel
        title = self._extract_title(soup)
        
        # Ekstrak tanggal rilis
        date_published = self._extract_date(soup)
        
        # Ekstrak konten artikel
        content_text = self._extract_content(soup)
        content_text = self.clean_text(content_text)
        
        return title, date_published, content_text

    def _extract_title(self, soup: BeautifulSoup) -> str:
        """Ekstrak judul artikel"""
        for selector in TITLE_SELECTORS:
            title_element = soup.select_one(selector)
            if title_element:
                title_text = title_element.get_text(strip=True)
                if title_text and len(title_text) > MIN_TITLE_LENGTH:
                    return title_text
        
        return "Judul tidak ditemukan"

    def _extract_date(self, soup: BeautifulSoup) -> Optional[str]:
        """Ekstrak tanggal publikasi"""
        # Mencoba selector untuk tanggal
        for selector in DATE_SELECTORS:
            try:
                date_element = soup.select_one(selector)
                if date_element:
                    if date_element.has_attr('datetime'):
                        return date_element['datetime']
                    else:
                        date_text = date_element.get_text(strip=True)
                        if date_text and len(date_text) > MIN_DATE_LENGTH:
                            date_text = re.sub(r'[^\d/\-\s:]', '', date_text).strip()
                            if date_text:
                                return date_text
            except Exception as e:
                logger.debug(f"Date selector {selector} failed: {e}")
                continue

        # Cari dalam teks body jika belum ditemukan
        body_text = soup.get_text()
        
        for pattern in DATE_PATTERNS:
            match = re.search(pattern, body_text)
            if match:
                return match.group(1)
        
        return None

    def _extract_content(self, soup: BeautifulSoup) -> str:
        """Ekstrak konten artikel"""
        # Mencoba selector untuk konten
        for selector in CONTENT_SELECTORS:
            try:
                content_element = soup.select_one(selector)
                if content_element:
                    content_text = self._clean_content_element(content_element)
                    if content_text and len(content_text) > MIN_CONTENT_LENGTH:
                        return content_text
            except Exception as e:
                logger.debug(f"Content selector {selector} failed: {e}")
                continue

        # Jika masih belum ditemukan, ambil dari body
        body = soup.find('body')
        if body:
            return self._clean_content_element(body)
        
        return ""

    def _clean_content_element(self, element) -> str:
        """Membersihkan elemen konten dari elemen yang tidak diinginkan"""
        # Hapus elemen yang tidak diinginkan
        for unwanted in element(UNWANTED_ELEMENTS):
            unwanted.decompose()
        
        # Hapus elemen dengan class yang mengandung konten tidak diinginkan
        for unwanted in element.find_all(class_=re.compile(r'.*(' + '|'.join(UNWANTED_CLASSES) + ').*', re.I)):
            unwanted.decompose()
        
        return element.get_text(separator=' ', strip=True)

    def is_article_relevant_by_content(self, title: str, content: str, keyword: str) -> bool:
        """Fungsi untuk memeriksa apakah artikel benar-benar relevan berdasarkan isi dan judulnya"""
        if not title or not content:
            return False

        full_text = (title + " " + content).lower()
        keyword_lower = keyword.lower()
        keyword_words = keyword_lower.split()

        # Cek apakah semua kata dalam keyword ada dalam teks
        for word in keyword_words:
            if word in full_text:
                return True
        
        return False

    def clean_dataframe(self, df: pd.DataFrame) -> pd.DataFrame:
        """Fungsi untuk membersihkan dan memperbaiki DataFrame sebelum disimpan ke CSV"""
        logger.info("Memulai pembersihan dan perbaikan DataFrame...")

        # Regex untuk memeriksa apakah string mirip URL
        url_pattern = re.compile(
            r'^(?:http|ftp)s?://'
            r'(?:(?:[A-Z0-9](?:[A-Z0-9-]{0,61}[A-Z0-9])?\.)+(?:[A-Z]{2,6}\.?|[A-Z0-9-]{2,}\.?)|'
            r'localhost|'
            r'\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3})'
            r'(?::\d+)?'
            r'(?:/?|[/?]\S+)$', re.IGNORECASE)

        # Regex untuk memeriksa apakah string mirip tanggal
        date_like_pattern = re.compile(
            r'(\d{4}[/-]\d{1,2}[/-]\d{2})|'
            r'(\d{1,2}[/-]\d{1,2}[/-]\d{4})|'
            r'(\d{1,2}\s+(Januari|Februari|Maret|April|Mei|Juni|Juli|Agustus|September|Oktober|November|Desember)\s+\d{4})'
            r'(\d{1,2}:\d{2}(?::\d{2})?\s*(?:WIB|WITA|WIT)?)|'
            r'(\d{1,2}:\d{2}(?::\d{2})?\s*(?:AM|PM)?)',
            re.IGNORECASE)

        for index, row in df.iterrows():
            try:
                # Periksa kolom link_berita
                current_link = str(row.get('link_berita', '')).strip()
                if not url_pattern.match(current_link):
                    current_title = str(row.get('judul_berita', ''))
                    new_title = f"{current_title} {current_link}".strip()
                    df.at[index, 'judul_berita'] = new_title
                    df.at[index, 'link_berita'] = ""

                # Periksa kolom tanggal_rilis
                current_date = str(row.get('tanggal_rilis', '')).strip()
                if url_pattern.match(current_date):
                    df.at[index, 'link_berita'] = current_date
                    df.at[index, 'tanggal_rilis'] = "Tanggal tidak ditemukan"

                # Periksa kolom detail_konten
                current_content = str(row.get('detail_konten', '')).strip()
                if date_like_pattern.match(current_content):
                    df.at[index, 'detail_konten'] = ""

            except Exception as e:
                logger.warning(f"Error saat memproses baris {index} dalam pembersihan: {e}")

        logger.info("Pembersihan dan perbaikan DataFrame selesai.")
        return df

    def scrape_news(self, keyword: str, max_articles: int = 5) -> pd.DataFrame:
        """Fungsi utama untuk scraping berita dari Radar Surabaya"""
        logger.info("=" * 70)
        logger.info(f"MEMULAI SCRAPING BERITA RADAR SURABAYA UNTUK KEYWORD: '{keyword}'")
        logger.info("=" * 70)

        max_articles = min(max_articles, MAX_ARTICLES_LIMIT)
        target_initial_articles = min(max_articles + MAX_EXTRA_ARTICLES_TO_FETCH, MAX_ARTICLES_LIMIT)

        all_article_links = []
        current_page = 1
        pages_to_check = min(MAX_PAGES_TO_CHECK, math.ceil(target_initial_articles / (MAX_ARTICLES_PER_PAGE/2)) + 10)

        logger.info(f"Mengumpulkan hingga {target_initial_articles} artikel kandidat...")

        # Mengumpulkan link artikel
        while len(all_article_links) < target_initial_articles and current_page <= pages_to_check:
            logger.info(f"Mengambil halaman pencarian {current_page}...")
            search_url = self.get_search_url(keyword, current_page)

            search_html = self.get_page_content(search_url)
            if not search_html:
                logger.error(f"Gagal mengambil halaman {current_page}")
                current_page += 1
                time.sleep(random.uniform(RETRY_DELAY_MIN, RETRY_DELAY_MAX))
                continue

            page_links = self.extract_article_links_from_page(search_html, search_url)

            if not page_links:
                logger.info(f"Tidak menemukan artikel baru di halaman {current_page}")
            else:
                logger.info(f"Menemukan {len(page_links)} artikel kandidat di halaman {current_page}")

                initial_count = len(all_article_links)
                for link in page_links:
                    if len(all_article_links) >= target_initial_articles:
                        break
                    if link['url'] not in [item['url'] for item in all_article_links]:
                        all_article_links.append(link)

                added_count = len(all_article_links) - initial_count
                logger.info(f"Menambahkan {added_count} artikel baru (total link: {len(all_article_links)})")

            if len(all_article_links) >= target_initial_articles:
                logger.info(f"Telah mencapai target awal {target_initial_articles} link artikel.")
                break

            # Cek apakah ada halaman berikutnya
            soup = BeautifulSoup(search_html, 'html.parser')
            next_page_link = soup.find('a', text=re.compile(r'Next|Berikutnya|>|Selanjutnya', re.I))
            if not next_page_link:
                pagination_area = soup.find('div', class_=re.compile(r'.*pag.*', re.I)) or soup.find('ul', class_=re.compile(r'.*pag.*', re.I))
                if pagination_area:
                    page_links_in_pagination = pagination_area.find_all('a', href=True, text=re.compile(r'^\d+$'))
                    if not page_links_in_pagination:
                        logger.info("Tidak ditemukan indikasi halaman berikutnya. Menghentikan pencarian link.")
                        break
                else:
                    logger.info("Tidak ditemukan area pagination. Menghentikan pencarian link.")
                    break

            time.sleep(random.uniform(PAGE_DELAY_MIN, PAGE_DELAY_MAX))
            current_page += 1

        if not all_article_links:
            logger.error("Tidak menemukan link artikel")
            logger.info("Tips: Pastikan keyword yang dimasukkan relevan")
            return pd.DataFrame()

        logger.info(f"Berhasil mengumpulkan {len(all_article_links)} link artikel kandidat")

        # Mengekstrak detail artikel
        results = []
        processed_count = 0
        total_links_to_process = len(all_article_links)

        logger.info(f"Mengekstrak detail dan memvalidasi relevansi untuk {max_articles} artikel...")

        for i, article in enumerate(all_article_links, 1):
            if len(results) >= max_articles:
                break

            logger.info(f"[{i}/{total_links_to_process}] Memproses: {article['title'][:60]}...")
            try:
                extracted_title, date_published, content_text = self.extract_article_details(article['url'])

                final_title = extracted_title if extracted_title != "Judul tidak ditemukan" else article['title']

                if self.is_article_relevant_by_content(final_title, content_text, keyword):
                    results.append({
                        'judul_berita': self.clean_text(final_title),
                        'link_berita': article['url'],
                        'tanggal_rilis': date_published if date_published else "Tanggal tidak ditemukan",
                        'detail_konten': content_text if content_text else "Konten tidak dapat diambil"
                    })
                    logger.info(f"Artikel relevan ditemukan ({len(results)}/{max_articles})")
                else:
                    logger.info(f"Artikel tidak relevan (tidak mengandung keyword '{keyword}')")

                processed_count += 1
                time.sleep(random.uniform(MIN_DELAY, MAX_DELAY))

            except Exception as e:
                logger.error(f"Error memproses artikel: {e}")
                results.append({
                    'judul_berita': self.clean_text(article['title']),
                    'link_berita': article['url'],
                    'tanggal_rilis': "Error saat mengambil data",
                    'detail_konten': f"Error: {str(e)}"
                })
                processed_count += 1

            if i < total_links_to_process and len(results) < max_articles:
                 time.sleep(0.5)

        if results:
            df = pd.DataFrame(results)
            logger.info("Scraping selesai!")
            logger.info(f"Artikel diproses: {processed_count}")
            logger.info(f"Artikel relevan ditemukan: {len(results)}")
            if len(results) < max_articles:
                logger.warning(f"Hanya ditemukan {len(results)} artikel yang benar-benar relevan (mengandung '{keyword}') dari {max_articles} yang diminta.")
            return df
        else:
            logger.error("Tidak ada data yang berhasil diambil")
            return pd.DataFrame()

    def save_to_csv(self, dataframe: pd.DataFrame, keyword: str) -> Optional[str]:
        """Fungsi untuk menyimpan hasil ke file CSV"""
        if not dataframe.empty:
            logger.info("Memulai proses pembersihan data sebelum penyimpanan...")
            cleaned_dataframe = self.clean_dataframe(dataframe.copy())
            logger.info("Proses pembersihan data selesai.")

            # Buat direktori output jika belum ada
            output_dir = Path(OUTPUT_DIR)
            output_dir.mkdir(exist_ok=True)

            # Buat nama file berdasarkan keyword dan timestamp
            timestamp = datetime.now().strftime("%Y%m%d_%H%M%S")
            filename = output_dir / f"berita_radar_surabaya_{keyword.replace(' ', '_')}_{timestamp}.csv"
            
            # Simpan ke CSV
            cleaned_dataframe.to_csv(filename, index=False, encoding=CSV_ENCODING)
            logger.info(f"Data disimpan ke: {filename}")
            return str(filename)
        return None

def main():
    """Fungsi utama program"""
    print("🎓 PROGRAM SCRAPING BERITA RADAR SURABAYA")
    print("👨‍🏫 Dibuat oleh Dosen Data Mining & Full Stack Engineer")
    print("📊 Pengalaman 30 tahun dalam data mining dan web scraping")
    print("🏆 Sertifikasi internasional dalam web scraping")
    print("=" * 70)

    scraper = RadarSurabayaScraper()

    while True:
        keyword = input("\n🔍 Masukkan keyword pencarian berita: ").strip()
        if keyword:
            break
        print("❌ Keyword tidak boleh kosong!")

    while True:
        try:
            max_articles = input("📊 Jumlah maksimal artikel (default 5, maks 500): ").strip()
            if not max_articles:
                max_articles = 5
                break
            max_articles = int(max_articles)
            if 1 <= max_articles <= 500:
                break
            else:
                print("❌ Masukkan angka antara 1-500")
        except ValueError:
            print("❌ Masukkan angka yang valid!")

    print(f"\n📝 Keyword yang akan dicari: '{keyword}'")
    print(f"📈 Maksimal artikel yang akan diambil: {max_articles}")

    try:
        df_results = scraper.scrape_news(keyword, max_articles)
        if not df_results.empty:
            print("\n" + "=" * 70)
            print("📊 HASIL SCRAPING:")
            print("=" * 70)
            
            # Tampilkan preview hasil
            print(df_results[['judul_berita', 'tanggal_rilis']].head(10).to_string(index=False, max_colwidth=50))

            # Statistik
            print(f"\n📈 Statistik:")
            print(f"   • Total artikel relevan: {len(df_results)}")
            print(f"   • Artikel dengan tanggal: {df_results['tanggal_rilis'].apply(lambda x: x != 'Tanggal tidak ditemukan' and not x.startswith('Error')).sum()}")
            print(f"   • Artikel dengan konten: {df_results['detail_konten'].apply(lambda x: len(x) > 50 and not x.startswith('Error')).sum()}")

            # Simpan ke CSV
            filename = scraper.save_to_csv(df_results, keyword)
            if filename:
                print(f"✅ File berhasil disimpan!")

            # Tampilkan preview data secara otomatis
            print("\n📄 PREVIEW DETAIL KONTEN:")
            print("=" * 70)
            for idx, row in df_results.head(3).iterrows():
                print(f"\n📋 Judul: {row['judul_berita']}")
                print(f"📅 Tanggal: {row['tanggal_rilis']}")
                print(f"🔗 URL: {row['link_berita']}")
                konten_preview = str(row['detail_konten'])[:500] + ("..." if len(str(row['detail_konten'])) > 500 else "")
                print(f"📝 Konten (preview 500 karakter):")
                print(f"   {konten_preview}")
                print("-" * 50)
        else:
            print("\n❌ Maaf, tidak ada data yang berhasil diambil.")
            print("💡 Tips:")
            print("   • Pastikan keyword yang dimasukkan spesifik")
            print("   • Periksa koneksi internet")
            print("   • Coba keyword lain")
    except KeyboardInterrupt:
        print("\n⚠️ Program dihentikan oleh user")
    except Exception as e:
        logger.error(f"Terjadi error: {e}")
        print(f"\n❌ Terjadi error: {e}")
        print("💡 Silakan coba lagi atau hubungi developer")

    print("\n👋 Terima kasih telah menggunakan program ini!")
    print("📚 Gunakan data dengan bijak dan sesuai etika riset")

# Jalankan program
if __name__ == "__main__":
    main()