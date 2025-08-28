# -*- coding: utf-8 -*-
"""
Scraping Berita Radar Surabaya - Program untuk mengumpulkan data berita
Dibuat oleh: Dosen Data Mining & Full Stack Engineer
Pengalaman: 30 tahun dalam web scraping dan data mining
Sertifikasi: Internasional Web Scraping & Data Mining
Tanggal: 2024
"""

# Import library yang diperlukan
import requests
from bs4 import BeautifulSoup
import pandas as pd
import time
import random
from urllib.parse import quote, urljoin
import re
from datetime import datetime
import math
import ssl
import urllib3

# Untuk menghindari error SSL di Colab
urllib3.disable_warnings(urllib3.exceptions.InsecureRequestWarning)

# Konfigurasi global
MAX_ARTICLES_PER_PAGE = 20
MAX_PAGES_TO_CHECK = 50
MAX_EXTRA_ARTICLES_TO_FETCH = 200
BASE_URL = "https://radarsurabaya.jawapos.com"

def get_search_url(keyword, page=1):
    """Fungsi untuk membuat URL pencarian Radar Surabaya berdasarkan keyword dan halaman"""
    encoded_keyword = quote(keyword)
    if page == 1:
        search_url = f"{BASE_URL}/search?q={encoded_keyword}"
    else:
        search_url = f"{BASE_URL}/search?q={encoded_keyword}&page={page}"
    return search_url

def get_page_content(url):
    """Fungsi untuk mengambil konten halaman dengan penanganan error yang robust"""
    headers = {
        'User-Agent': 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
        'Accept': 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8',
        'Accept-Language': 'id-ID,id;q=0.9,en-US;q=0.8,en;q=0.7',
        'Accept-Encoding': 'gzip, deflate, br',
        'Connection': 'keep-alive',
        'Upgrade-Insecure-Requests': '1',
        'Sec-Fetch-Dest': 'document',
        'Sec-Fetch-Mode': 'navigate',
        'Sec-Fetch-Site': 'none',
        'Cache-Control': 'max-age=0',
        'Referer': BASE_URL
    }
    
    try:
        response = requests.get(url, headers=headers, verify=False, timeout=20)
        response.raise_for_status()
        response.encoding = 'utf-8'
        return response.text
    except requests.exceptions.RequestException as e:
        print(f"❌ Error saat mengakses {url}: {e}")
        return None

def extract_article_links_from_page(search_html, base_url):
    """Fungsi untuk mengekstrak link artikel dari halaman pencarian Radar Surabaya"""
    if not search_html:
        return []

    soup = BeautifulSoup(search_html, 'html.parser')
    links = set()

    # Mencoba berbagai selector untuk menemukan artikel
    article_selectors = [
        # Selector utama berdasarkan inspect element yang diberikan
        '/html/body/div[3]/div/div/div[2]/section/div[3]/div[1]/div[2]/h2/a',
        # Selector alternatif
        'div.article-item h2 a',
        'div.news-item h2 a',
        'article h2 a',
        'div.post-item h2 a',
        'div.entry-title a',
        'h2 a[href*="/"]',
        'div.article h2 a',
        'div.news h2 a'
    ]

    # Mencoba selector XPath yang diberikan
    try:
        # Konversi XPath ke CSS selector sederhana
        xpath_links = soup.select('div[class*="article"] h2 a, div[class*="news"] h2 a, div[class*="post"] h2 a')
        for link in xpath_links:
            href = link.get('href')
            title = link.get_text(strip=True)
            
            if href and title:
                if href.startswith('/'):
                    href = urljoin(base_url, href)
                elif not href.startswith('http'):
                    href = urljoin(base_url, href)
                
                if (href.startswith(BASE_URL) and 
                    len(title) > 10 and
                    not re.search(r'#|\.pdf$|\.jpg$|\.png$|/foto/|/video/|/indeks|/tag/|/category/|/search/|/about|/contact', href, re.IGNORECASE)):
                    links.add((title, href))
    except Exception as e:
        print(f"⚠️ Error saat mengekstrak link dengan XPath: {e}")

    # Mencoba selector CSS alternatif
    css_selectors = [
        'a[href*="/"]',
        'h2 a',
        'h3 a',
        '.title a',
        '.headline a',
        'article a',
        '.post-title a'
    ]

    for selector in css_selectors:
        try:
            elements = soup.select(selector)
            for element in elements:
                href = element.get('href')
                title = element.get_text(strip=True)
                
                if href and title and len(title) > 10:
                    if href.startswith('/'):
                        href = urljoin(base_url, href)
                    elif not href.startswith('http'):
                        href = urljoin(base_url, href)
                    
                    if (href.startswith(BASE_URL) and 
                        not re.search(r'#|\.pdf$|\.jpg$|\.png$|/foto/|/video/|/indeks|/tag/|/category/|/search/|/about|/contact', href, re.IGNORECASE)):
                        links.add((title, href))
        except Exception as e:
            continue

    return [{'title': title, 'url': url} for title, url in links]

def clean_text(text):
    """Fungsi untuk membersihkan teks dari karakter tidak diinginkan"""
    if not text:
        return ""
    # Hapus karakter khusus dan whitespace berlebih
    text = re.sub(r'\s+', ' ', text)
    text = re.sub(r'[\r\n\t]+', ' ', text)
    text = text.strip()
    return text

def extract_article_details(article_url):
    """
    Fungsi untuk mengekstrak detail artikel: judul, tanggal, dan konten
    Menggunakan selector yang spesifik untuk Radar Surabaya
    """
    print(f"📄 Mengambil data dari: {article_url}")
    html_content = get_page_content(article_url)
    if not html_content:
        return None, None, None

    soup = BeautifulSoup(html_content, 'html.parser')

    # Ekstrak judul artikel
    title = "Judul tidak ditemukan"
    title_selectors = [
        'h1.article-title',
        'h1.post-title',
        'h1.entry-title',
        'h1.news-title',
        'h1',
        '.article-title',
        '.post-title',
        '.entry-title',
        '.news-title',
        'title'
    ]

    for selector in title_selectors:
        title_element = soup.select_one(selector)
        if title_element:
            title_text = title_element.get_text(strip=True)
            if title_text and len(title_text) > 10:
                title = title_text
                break

    # Ekstrak tanggal rilis menggunakan selector yang diberikan
    date_published = None
    date_selectors = [
        # Selector berdasarkan inspect element yang diberikan
        '/html/body/div[3]/div/div/div[2]/section/div[3]/div[1]/div[2]/date',
        # Selector alternatif
        'time[datetime]',
        '.article-date time',
        '.post-date time',
        '.entry-date time',
        '.publish-date time',
        'time',
        '[datetime]',
        '.date',
        '.publish-date',
        '.article-date',
        '.post-date',
        '.entry-date',
        '.tanggal',
        'date'
    ]

    for selector in date_selectors:
        try:
            if selector.startswith('/'):
                # Handle XPath selector
                date_element = soup.select_one('date, time, .date, .publish-date, .article-date')
            else:
                date_element = soup.select_one(selector)
            
            if date_element:
                if date_element.has_attr('datetime'):
                    date_published = date_element['datetime']
                    break
                else:
                    date_text = date_element.get_text(strip=True)
                    if date_text and len(date_text) > 5:
                        # Bersihkan teks tanggal
                        date_text = re.sub(r'[^\d/\-\s:]', '', date_text).strip()
                        if date_text:
                            date_published = date_text
                            break
        except Exception as e:
            continue

    # Jika tanggal tidak ditemukan, cari dalam teks body
    if not date_published:
        body_text = soup.get_text()
        date_patterns = [
            r'(\d{1,2}\s+(Januari|Februari|Maret|April|Mei|Juni|Juli|Agustus|September|Oktober|November|Desember)\s+\d{4}\s*\d{1,2}:\d{2})',
            r'(\d{1,2}\s+(Januari|Februari|Maret|April|Mei|Juni|Juli|Agustus|September|Oktober|November|Desember)\s+\d{4})',
            r'(\d{1,2}[/-]\d{1,2}[/-]\d{4}\s*\d{1,2}:\d{2})',
            r'(\d{1,2}[/-]\d{1,2}[/-]\d{4})',
            r'(\d{4}[/-]\d{2}[/-]\d{2})'
        ]
        for pattern in date_patterns:
            match = re.search(pattern, body_text)
            if match:
                date_published = match.group(1)
                break

    # Ekstrak konten artikel menggunakan selector yang diberikan
    content_text = ""
    content_selectors = [
        # Selector utama berdasarkan inspect element yang diberikan
        '/html/body/div[3]/div/div/div[2]/div/div[1]/article',
        # Selector alternatif
        '.article-content',
        '.post-content',
        '.entry-content',
        '.news-content',
        '.content-text',
        'article .content',
        'article',
        '.post-body',
        '.entry-body',
        '.article-body'
    ]

    content_element = None
    for selector in content_selectors:
        try:
            if selector.startswith('/'):
                # Handle XPath selector - cari article element
                content_element = soup.select_one('article, .article-content, .post-content, .entry-content')
            else:
                content_element = soup.select_one(selector)
            
            if content_element:
                break
        except Exception as e:
            continue

    if content_element:
        # Hapus elemen yang tidak diinginkan (iklan, sidebar, dll)
        for unwanted in content_element(["script", "style", "nav", "aside", "header", "footer", "advertisement", "ads", "noscript", "iframe"]):
            unwanted.decompose()

        # Hapus elemen dengan class yang mengandung kata iklan/promo
        for unwanted in content_element.find_all(class_=re.compile(r'.*(ads|advertisement|promo|related|widget|sidebar|footer|banner).*', re.I)):
            unwanted.decompose()
        
        for unwanted in content_element.find_all(id=re.compile(r'.*(ads|advertisement|promo|related|widget|banner).*', re.I)):
            unwanted.decompose()

        content_text = content_element.get_text(separator=' ', strip=True)
    else:
        # Fallback: ambil dari body jika selector tidak ditemukan
        body = soup.find('body')
        if body:
            for unwanted in body(["script", "style", "nav", "aside", "header", "footer", "noscript", "iframe"]):
                unwanted.decompose()
            content_text = body.get_text(separator=' ', strip=True)

    content_text = clean_text(content_text)
    
    return title, date_published, content_text

def is_article_relevant_by_content(title, content, keyword):
    """Fungsi untuk memeriksa apakah artikel benar-benar relevan berdasarkan isi dan judulnya"""
    if not title or not content:
        return False

    full_text = (title + " " + content).lower()
    keyword_lower = keyword.lower()

    # Cek apakah keyword ada dalam teks
    if keyword_lower in full_text:
        return True
    
    # Cek kata-kata individual dari keyword
    keyword_words = keyword_lower.split()
    if len(keyword_words) > 1:
        # Jika keyword terdiri dari beberapa kata, cek apakah minimal 70% kata ada
        found_words = sum(1 for word in keyword_words if word in full_text)
        if found_words >= len(keyword_words) * 0.7:
            return True
    
    return False

def clean_dataframe(df):
    """
    Fungsi untuk membersihkan dan memperbaiki DataFrame sebelum disimpan ke CSV
    """
    print("🔍 Memulai pembersihan dan perbaikan DataFrame...")

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
            # Perbaiki kolom link_berita
            current_link = str(row.get('link_berita', '')).strip()
            if not url_pattern.match(current_link):
                current_title = str(row.get('judul_berita', ''))
                new_title = f"{current_title} {current_link}".strip()
                df.at[index, 'judul_berita'] = new_title
                df.at[index, 'link_berita'] = ""

            # Perbaiki kolom tanggal_rilis
            current_date = str(row.get('tanggal_rilis', '')).strip()
            if url_pattern.match(current_date):
                df.at[index, 'link_berita'] = current_date
                df.at[index, 'tanggal_rilis'] = "Tanggal tidak ditemukan"

            # Perbaiki kolom detail_konten
            current_content = str(row.get('detail_konten', '')).strip()
            if date_like_pattern.match(current_content):
                df.at[index, 'detail_konten'] = ""

        except Exception as e:
            print(f"⚠️ Error saat memproses baris {index} dalam pembersihan: {e}")

    print("✅ Pembersihan dan perbaikan DataFrame selesai.")
    return df

def scrape_radar_surabaya_news(keyword, max_articles=5):
    """Fungsi utama untuk scraping berita dari Radar Surabaya"""
    print("=" * 70)
    print(f"MEMULAI SCRAPING BERITA RADAR SURABAYA UNTUK KEYWORD: '{keyword}'")
    print("=" * 70)

    max_articles = min(max_articles, 500)
    target_initial_articles = min(max_articles + MAX_EXTRA_ARTICLES_TO_FETCH, 500)

    all_article_links = []
    current_page = 1
    pages_to_check = min(MAX_PAGES_TO_CHECK, math.ceil(target_initial_articles / (MAX_ARTICLES_PER_PAGE/2)) + 10)

    print(f"Mengumpulkan hingga {target_initial_articles} artikel kandidat...")

    while len(all_article_links) < target_initial_articles and current_page <= pages_to_check:
        print(f"Mengambil halaman pencarian {current_page}...")
        search_url = get_search_url(keyword, current_page)

        search_html = get_page_content(search_url)
        if not search_html:
            print(f"❌ Gagal mengambil halaman {current_page}")
            current_page += 1
            time.sleep(random.uniform(2, 4))
            continue

        page_links = extract_article_links_from_page(search_html, search_url)

        if not page_links:
            print(f"ℹ️ Tidak menemukan artikel baru di halaman {current_page}")
        else:
            print(f"✅ Menemukan {len(page_links)} artikel kandidat di halaman {current_page}")

            initial_count = len(all_article_links)
            for link in page_links:
                if len(all_article_links) >= target_initial_articles:
                    break
                if link['url'] not in [item['url'] for item in all_article_links]:
                    all_article_links.append(link)

            added_count = len(all_article_links) - initial_count
            print(f"📈 Menambahkan {added_count} artikel baru (total link: {len(all_article_links)})")

        if len(all_article_links) >= target_initial_articles:
            print(f"🎯 Telah mencapai target awal {target_initial_articles} link artikel.")
            break

        # Cek apakah ada halaman berikutnya
        soup = BeautifulSoup(search_html, 'html.parser')
        next_page_link = soup.find('a', text=re.compile(r'Next|Berikutnya|>|Selanjutnya', re.I))
        if not next_page_link:
            pagination_area = soup.find('div', class_=re.compile(r'.*pag.*', re.I)) or soup.find('ul', class_=re.compile(r'.*pag.*', re.I))
            if pagination_area:
                page_links_in_pagination = pagination_area.find_all('a', href=True, text=re.compile(r'^\d+$'))
                if not page_links_in_pagination:
                    print("ℹ️ Tidak ditemukan indikasi halaman berikutnya. Menghentikan pencarian link.")
                    break
            else:
                print("ℹ️ Tidak ditemukan area pagination. Menghentikan pencarian link.")
                break

        time.sleep(random.uniform(1, 2))
        current_page += 1

    if not all_article_links:
        print("❌ Tidak menemukan link artikel")
        print("💡 Tips: Pastikan keyword yang dimasukkan relevan")
        return pd.DataFrame()

    print(f"✅ Berhasil mengumpulkan {len(all_article_links)} link artikel kandidat")

    results = []
    processed_count = 0
    total_links_to_process = len(all_article_links)

    print(f"\nMengekstrak detail dan memvalidasi relevansi untuk {max_articles} artikel...")

    for i, article in enumerate(all_article_links, 1):
        if len(results) >= max_articles:
            break

        print(f"\n[{i}/{total_links_to_process}] Memproses: {article['title'][:60]}...")
        try:
            extracted_title, date_published, content_text = extract_article_details(article['url'])

            final_title = extracted_title if extracted_title != "Judul tidak ditemukan" else article['title']

            if is_article_relevant_by_content(final_title, content_text, keyword):
                results.append({
                    'judul_berita': clean_text(final_title),
                    'link_berita': article['url'],
                    'tanggal_rilis': date_published if date_published else "Tanggal tidak ditemukan",
                    'detail_konten': content_text if content_text else "Konten tidak dapat diambil"
                })
                print(f"   ✅ Artikel relevan ditemukan ({len(results)}/{max_articles})")
            else:
                print(f"   ❌ Artikel tidak relevan (tidak mengandung keyword '{keyword}')")

            processed_count += 1
            time.sleep(random.uniform(1, 3))

        except Exception as e:
            print(f"❌ Error memproses artikel: {e}")
            results.append({
                'judul_berita': clean_text(article['title']),
                'link_berita': article['url'],
                'tanggal_rilis': "Error saat mengambil data",
                'detail_konten': f"Error: {str(e)}"
            })
            processed_count += 1

        if i < total_links_to_process and len(results) < max_articles:
            time.sleep(0.5)

    if results:
        df = pd.DataFrame(results)
        print(f"\n✅ Scraping selesai!")
        print(f"   • Artikel diproses: {processed_count}")
        print(f"   • Artikel relevan ditemukan: {len(results)}")
        if len(results) < max_articles:
            print(f"   ⚠️ Hanya ditemukan {len(results)} artikel yang benar-benar relevan (mengandung '{keyword}') dari {max_articles} yang diminta.")
        return df
    else:
        print("❌ Tidak ada data yang berhasil diambil")
        return pd.DataFrame()

def save_to_csv(dataframe, keyword):
    """Fungsi untuk menyimpan hasil ke file CSV"""
    if not dataframe.empty:
        print("\n🛠️ Memulai proses pembersihan data sebelum penyimpanan...")
        cleaned_dataframe = clean_dataframe(dataframe.copy())
        print("🛠️ Proses pembersihan data selesai.")

        # Buat nama file berdasarkan keyword dan timestamp
        timestamp = datetime.now().strftime("%Y%m%d_%H%M%S")
        filename = f"berita_radar_surabaya_{keyword.replace(' ', '_')}_{timestamp}.csv"
        
        # Simpan ke CSV
        cleaned_dataframe.to_csv(filename, index=False, encoding='utf-8-sig')
        print(f"💾 Data disimpan ke: {filename}")
        return filename
    return None

def main():
    """Fungsi utama program"""
    print("🎓 PROGRAM SCRAPING BERITA RADAR SURABAYA")
    print("👨‍🏫 Dibuat oleh Dosen Data Mining & Full Stack Engineer")
    print("📊 Pengalaman 30 tahun dalam web scraping dan data mining")
    print("🏆 Sertifikasi Internasional Web Scraping & Data Mining")
    print("=" * 70)

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
        df_results = scrape_radar_surabaya_news(keyword, max_articles)
        if not df_results.empty:
            print("\n" + "=" * 70)
            print("📊 HASIL SCRAPING RADAR SURABAYA:")
            print("=" * 70)
            
            # Tampilkan preview hasil
            print(df_results[['judul_berita', 'tanggal_rilis']].head(10).to_string(index=False, max_colwidth=50))

            # Statistik
            print(f"\n📈 Statistik:")
            print(f"   • Total artikel relevan: {len(df_results)}")
            print(f"   • Artikel dengan tanggal: {df_results['tanggal_rilis'].apply(lambda x: x != 'Tanggal tidak ditemukan' and not x.startswith('Error')).sum()}")
            print(f"   • Artikel dengan konten: {df_results['detail_konten'].apply(lambda x: len(x) > 50 and not x.startswith('Error')).sum()}")

            # Simpan ke CSV
            filename = save_to_csv(df_results, keyword)
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
        print(f"\n❌ Terjadi error: {e}")
        print("💡 Silakan coba lagi atau hubungi developer")

    print("\n👋 Terima kasih telah menggunakan program ini!")
    print("📚 Gunakan data dengan bijak dan sesuai etika riset")

# Jalankan program
if __name__ == "__main__":
    main()