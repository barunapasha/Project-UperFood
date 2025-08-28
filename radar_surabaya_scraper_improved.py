# -*- coding: utf-8 -*-
"""
Improved Scraping Berita Radar Surabaya - Program untuk mengumpulkan data berita
Dibuat oleh: Dosen Data Mining & Full Stack Engineer
Pengalaman: 30 tahun dalam data mining dan web scraping
Sertifikasi: Internasional dalam web scraping dan data extraction
Tanggal: 2024
Versi: 2.0 - Improved with better error handling and performance
"""

import requests
from bs4 import BeautifulSoup
import pandas as pd
import time
import random
from urllib.parse import quote, urljoin, urlparse
import re
from datetime import datetime, timedelta
import math
import ssl
import urllib3
import json
import logging
from typing import List, Dict, Optional, Tuple
from dataclasses import dataclass
from concurrent.futures import ThreadPoolExecutor, as_completed
import threading

# Konfigurasi logging
logging.basicConfig(
    level=logging.INFO,
    format='%(asctime)s - %(levelname)s - %(message)s',
    handlers=[
        logging.FileHandler('scraper.log'),
        logging.StreamHandler()
    ]
)
logger = logging.getLogger(__name__)

# Untuk menghindari error SSL
urllib3.disable_warnings(urllib3.exceptions.InsecureRequestWarning)

@dataclass
class ScrapingConfig:
    """Konfigurasi untuk scraping"""
    max_articles_per_page: int = 20
    max_pages_to_check: int = 50
    max_extra_articles_to_fetch: int = 200
    base_url: str = "https://radarsurabaya.jawapos.com"
    request_timeout: int = 30
    max_retries: int = 3
    delay_between_requests: Tuple[float, float] = (1.0, 3.0)
    max_concurrent_requests: int = 5
    user_agents: List[str] = None
    
    def __post_init__(self):
        if self.user_agents is None:
            self.user_agents = [
                'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
                'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
                'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
                'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:109.0) Gecko/20100101 Firefox/121.0'
            ]

@dataclass
class ArticleData:
    """Data struktur untuk artikel"""
    title: str
    url: str
    date_published: Optional[str]
    content: str
    relevance_score: float = 0.0
    word_count: int = 0
    extraction_errors: List[str] = None
    
    def __post_init__(self):
        if self.extraction_errors is None:
            self.extraction_errors = []
        if self.content:
            self.word_count = len(self.content.split())

class RadarSurabayaScraper:
    """Class utama untuk scraping Radar Surabaya"""
    
    def __init__(self, config: ScrapingConfig = None):
        self.config = config or ScrapingConfig()
        self.session = requests.Session()
        self.session.verify = False
        self._setup_session()
        self._lock = threading.Lock()
        self._processed_urls = set()
        
    def _setup_session(self):
        """Setup session dengan headers yang lebih robust"""
        self.session.headers.update({
            'Accept': 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8',
            'Accept-Language': 'id-ID,id;q=0.9,en-US;q=0.8,en;q=0.7',
            'Accept-Encoding': 'gzip, deflate, br',
            'Connection': 'keep-alive',
            'Upgrade-Insecure-Requests': '1',
            'Sec-Fetch-Dest': 'document',
            'Sec-Fetch-Mode': 'navigate',
            'Sec-Fetch-Site': 'none',
            'Cache-Control': 'max-age=0',
            'Referer': self.config.base_url
        })
    
    def _get_random_user_agent(self) -> str:
        """Mendapatkan User-Agent secara random"""
        return random.choice(self.config.user_agents)
    
    def get_search_url(self, keyword: str, page: int = 1) -> str:
        """Membuat URL pencarian"""
        encoded_keyword = quote(keyword)
        if page == 1:
            return f"{self.config.base_url}/search?q={encoded_keyword}"
        return f"{self.config.base_url}/search?q={encoded_keyword}&page={page}"
    
    def get_page_content(self, url: str, retries: int = None) -> Optional[str]:
        """Mengambil konten halaman dengan retry mechanism"""
        if retries is None:
            retries = self.config.max_retries
            
        for attempt in range(retries):
            try:
                headers = {'User-Agent': self._get_random_user_agent()}
                response = self.session.get(
                    url, 
                    headers=headers, 
                    timeout=self.config.request_timeout
                )
                response.raise_for_status()
                response.encoding = 'utf-8'
                return response.text
                
            except requests.exceptions.RequestException as e:
                logger.warning(f"Attempt {attempt + 1}/{retries} failed for {url}: {e}")
                if attempt < retries - 1:
                    time.sleep(random.uniform(2, 5))
                else:
                    logger.error(f"Failed to fetch {url} after {retries} attempts")
                    return None
    
    def extract_article_links_from_page(self, search_html: str, base_url: str) -> List[Dict[str, str]]:
        """Ekstrak link artikel dengan selector yang lebih robust"""
        if not search_html:
            return []

        soup = BeautifulSoup(search_html, 'html.parser')
        links = set()

        # Selector patterns yang lebih comprehensive
        article_selectors = [
            # Modern news site patterns
            'article h2 a',
            'div.article-item h2 a',
            'div.news-item h2 a',
            'div.post-item h2 a',
            '.article-title a',
            '.news-title a',
            '.post-title a',
            # Generic patterns
            'h2 a[href*="/"]',
            'h3 a[href*="/"]',
            'a[href*="/"]'
        ]

        # Try different extraction methods
        extraction_methods = [
            self._extract_by_css_selectors,
            self._extract_by_content_area,
            self._extract_by_link_patterns
        ]

        for method in extraction_methods:
            try:
                method_links = method(soup, base_url)
                links.update(method_links)
            except Exception as e:
                logger.warning(f"Extraction method failed: {e}")

        return [{'title': title, 'url': url} for title, url in links]
    
    def _extract_by_css_selectors(self, soup: BeautifulSoup, base_url: str) -> set:
        """Ekstrak menggunakan CSS selectors"""
        links = set()
        
        for selector in [
            'article h2 a', 'div.article-item h2 a', 'div.news-item h2 a',
            '.article-title a', '.news-title a', 'h2 a[href*="/"]'
        ]:
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
                logger.debug(f"CSS selector {selector} failed: {e}")
                
        return links
    
    def _extract_by_content_area(self, soup: BeautifulSoup, base_url: str) -> set:
        """Ekstrak dari area konten utama"""
        links = set()
        
        content_areas = soup.find_all(['div', 'section'], class_=re.compile(
            r'content|main|container|article|news|post', re.I
        ))
        
        for area in content_areas:
            article_links = area.find_all('a', href=True)
            for link in article_links:
                href = link['href']
                title = link.get_text(strip=True)
                
                if self._is_valid_article_link(href, title):
                    if href.startswith('/'):
                        href = urljoin(base_url, href)
                    links.add((title, href))
                    
        return links
    
    def _extract_by_link_patterns(self, soup: BeautifulSoup, base_url: str) -> set:
        """Ekstrak berdasarkan pola link"""
        links = set()
        
        # Find all links that might be articles
        all_links = soup.find_all('a', href=True)
        for link in all_links:
            href = link['href']
            title = link.get_text(strip=True)
            
            # Check if it looks like an article link
            if (self._is_valid_article_link(href, title) and 
                len(title) > 15 and  # Longer titles are more likely to be articles
                not any(exclude in href.lower() for exclude in ['#', '.pdf', '.jpg', '.png', '/tag/', '/category/', '/author/', '/page/'])):
                
                if href.startswith('/'):
                    href = urljoin(base_url, href)
                links.add((title, href))
                
        return links
    
    def _is_valid_article_link(self, href: str, title: str) -> bool:
        """Validasi apakah link adalah artikel yang valid"""
        if not href or not title:
            return False
            
        # Check if it's a relative or absolute URL to the target domain
        if not (href.startswith('/') or 'radarsurabaya.jawapos.com' in href):
            return False
            
        # Check title length
        if len(title) < 10:
            return False
            
        # Check for excluded patterns
        excluded_patterns = ['#', '.pdf', '.jpg', '.png', '/tag/', '/category/', '/author/', '/page/']
        if any(exclude in href.lower() for exclude in excluded_patterns):
            return False
            
        return True
    
    def extract_article_details(self, article_url: str) -> Optional[ArticleData]:
        """Ekstrak detail artikel dengan error handling yang lebih baik"""
        logger.info(f"Extracting details from: {article_url}")
        
        html_content = self.get_page_content(article_url)
        if not html_content:
            return None

        soup = BeautifulSoup(html_content, 'html.parser')
        extraction_errors = []
        
        # Extract title
        title = self._extract_title(soup, extraction_errors)
        
        # Extract date
        date_published = self._extract_date(soup, extraction_errors)
        
        # Extract content
        content = self._extract_content(soup, extraction_errors)
        
        # Calculate relevance score
        relevance_score = self._calculate_relevance_score(title, content)
        
        return ArticleData(
            title=title,
            url=article_url,
            date_published=date_published,
            content=content,
            relevance_score=relevance_score,
            extraction_errors=extraction_errors
        )
    
    def _extract_title(self, soup: BeautifulSoup, errors: List[str]) -> str:
        """Ekstrak judul artikel"""
        title_selectors = [
            'h1.article-title', 'h1.news-title', 'h1.post-title',
            'h1', '.article-title', '.news-title', '.post-title'
        ]
        
        for selector in title_selectors:
            try:
                title_element = soup.select_one(selector)
                if title_element:
                    title_text = title_element.get_text(strip=True)
                    if title_text and len(title_text) > 10:
                        return title_text
            except Exception as e:
                errors.append(f"Title extraction error with {selector}: {e}")
        
        errors.append("Title not found")
        return "Judul tidak ditemukan"
    
    def _extract_date(self, soup: BeautifulSoup, errors: List[str]) -> Optional[str]:
        """Ekstrak tanggal publikasi"""
        date_selectors = [
            'time[datetime]', '.article-date time', '.news-date time',
            'time', '[datetime]', '.date', '.publish-date'
        ]
        
        # Try structured date extraction
        for selector in date_selectors:
            try:
                date_element = soup.select_one(selector)
                if date_element:
                    if date_element.has_attr('datetime'):
                        return date_element['datetime']
                    else:
                        date_text = date_element.get_text(strip=True)
                        if date_text and len(date_text) > 5:
                            cleaned_date = re.sub(r'[^\d/\-\s:]', '', date_text).strip()
                            if cleaned_date:
                                return cleaned_date
            except Exception as e:
                errors.append(f"Date extraction error with {selector}: {e}")
        
        # Try pattern matching in body text
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
                return match.group(1)
        
        errors.append("Date not found")
        return None
    
    def _extract_content(self, soup: BeautifulSoup, errors: List[str]) -> str:
        """Ekstrak konten artikel"""
        content_selectors = [
            '.article-content', '.news-content', '.post-content',
            '.content-body', '.article-body', '.news-body',
            'article', '.content', '.entry-content'
        ]
        
        # Try structured content extraction
        for selector in content_selectors:
            try:
                content_element = soup.select_one(selector)
                if content_element:
                    content_text = self._clean_content_element(content_element)
                    if content_text and len(content_text) > 100:
                        return content_text
            except Exception as e:
                errors.append(f"Content extraction error with {selector}: {e}")
        
        # Fallback to body extraction
        try:
            body = soup.find('body')
            if body:
                return self._clean_content_element(body)
        except Exception as e:
            errors.append(f"Body content extraction error: {e}")
        
        errors.append("Content not found")
        return "Konten tidak dapat diambil"
    
    def _clean_content_element(self, element) -> str:
        """Membersihkan elemen konten"""
        # Remove unwanted elements
        for unwanted in element(["script", "style", "nav", "aside", "header", "footer", "noscript", "iframe"]):
            unwanted.decompose()
        
        # Remove elements with ad/promo classes
        for unwanted in element.find_all(class_=re.compile(
            r'.*(ads|advertisement|promo|related|widget|sidebar|footer|social|share).*', re.I
        )):
            unwanted.decompose()
        
        return element.get_text(separator=' ', strip=True)
    
    def _calculate_relevance_score(self, title: str, content: str) -> float:
        """Hitung skor relevansi artikel"""
        if not title or not content:
            return 0.0
        
        # Simple relevance scoring based on content length and structure
        score = 0.0
        
        # Content length score
        word_count = len(content.split())
        if word_count > 500:
            score += 0.3
        elif word_count > 200:
            score += 0.2
        elif word_count > 100:
            score += 0.1
        
        # Title quality score
        if len(title) > 20:
            score += 0.2
        
        # Content structure score (has paragraphs, sentences)
        if '.' in content and len(content.split('.')) > 5:
            score += 0.2
        
        return min(score, 1.0)
    
    def is_article_relevant(self, article_data: ArticleData, keyword: str) -> bool:
        """Cek relevansi artikel berdasarkan keyword"""
        if not article_data.title or not article_data.content:
            return False

        full_text = (article_data.title + " " + article_data.content).lower()
        keyword_lower = keyword.lower()
        keyword_words = keyword_lower.split()

        # Check if any keyword word appears in the text
        for word in keyword_words:
            if len(word) > 2 and word in full_text:  # Only consider words longer than 2 chars
                return True
        
        return False
    
    def scrape_articles(self, keyword: str, max_articles: int = 5) -> List[ArticleData]:
        """Fungsi utama untuk scraping artikel"""
        logger.info(f"Starting scraping for keyword: '{keyword}'")
        
        # Collect article links
        article_links = self._collect_article_links(keyword, max_articles)
        
        if not article_links:
            logger.warning("No article links found")
            return []
        
        # Extract article details with threading
        articles = self._extract_articles_with_threading(article_links, keyword, max_articles)
        
        # Filter and sort by relevance
        relevant_articles = [
            article for article in articles 
            if self.is_article_relevant(article, keyword)
        ]
        
        # Sort by relevance score and word count
        relevant_articles.sort(key=lambda x: (x.relevance_score, x.word_count), reverse=True)
        
        return relevant_articles[:max_articles]
    
    def _collect_article_links(self, keyword: str, max_articles: int) -> List[Dict[str, str]]:
        """Kumpulkan link artikel dari halaman pencarian"""
        all_links = []
        current_page = 1
        target_links = min(max_articles + self.config.max_extra_articles_to_fetch, 500)
        
        while len(all_links) < target_links and current_page <= self.config.max_pages_to_check:
            logger.info(f"Collecting links from page {current_page}")
            
            search_url = self.get_search_url(keyword, current_page)
            search_html = self.get_page_content(search_url)
            
            if not search_html:
                logger.warning(f"Failed to fetch page {current_page}")
                current_page += 1
                time.sleep(random.uniform(*self.config.delay_between_requests))
                continue
            
            page_links = self.extract_article_links_from_page(search_html, search_url)
            
            if not page_links:
                logger.info(f"No new articles found on page {current_page}")
                break
            
            # Add new links
            new_links = []
            for link in page_links:
                if link['url'] not in [existing['url'] for existing in all_links]:
                    new_links.append(link)
            
            all_links.extend(new_links)
            logger.info(f"Added {len(new_links)} new links (total: {len(all_links)})")
            
            # Check for next page
            if not self._has_next_page(search_html):
                break
            
            current_page += 1
            time.sleep(random.uniform(*self.config.delay_between_requests))
        
        return all_links
    
    def _has_next_page(self, html_content: str) -> bool:
        """Cek apakah ada halaman berikutnya"""
        soup = BeautifulSoup(html_content, 'html.parser')
        
        # Look for next page indicators
        next_indicators = [
            soup.find('a', text=re.compile(r'Next|Berikutnya|>|Selanjutnya', re.I)),
            soup.find('a', class_=re.compile(r'next|pagination-next', re.I)),
            soup.find('a', {'rel': 'next'})
        ]
        
        return any(next_indicators)
    
    def _extract_articles_with_threading(self, article_links: List[Dict[str, str]], 
                                       keyword: str, max_articles: int) -> List[ArticleData]:
        """Ekstrak artikel menggunakan threading untuk performa lebih baik"""
        articles = []
        
        with ThreadPoolExecutor(max_workers=self.config.max_concurrent_requests) as executor:
            # Submit tasks
            future_to_url = {
                executor.submit(self.extract_article_details, link['url']): link['url']
                for link in article_links
            }
            
            # Collect results
            for future in as_completed(future_to_url):
                url = future_to_url[future]
                try:
                    article_data = future.result()
                    if article_data:
                        articles.append(article_data)
                        logger.info(f"Successfully extracted: {article_data.title[:50]}...")
                    
                    # Add delay between requests
                    time.sleep(random.uniform(*self.config.delay_between_requests))
                    
                except Exception as e:
                    logger.error(f"Error extracting {url}: {e}")
        
        return articles
    
    def save_to_csv(self, articles: List[ArticleData], keyword: str) -> str:
        """Simpan hasil ke CSV"""
        if not articles:
            logger.warning("No articles to save")
            return ""
        
        # Convert to DataFrame
        data = []
        for article in articles:
            data.append({
                'judul_berita': article.title,
                'link_berita': article.url,
                'tanggal_rilis': article.date_published or "Tanggal tidak ditemukan",
                'detail_konten': article.content,
                'skor_relevansi': round(article.relevance_score, 3),
                'jumlah_kata': article.word_count,
                'error_ekstraksi': '; '.join(article.extraction_errors) if article.extraction_errors else ""
            })
        
        df = pd.DataFrame(data)
        
        # Create filename
        timestamp = datetime.now().strftime("%Y%m%d_%H%M%S")
        filename = f"berita_radar_surabaya_{keyword.replace(' ', '_')}_{timestamp}.csv"
        
        # Save to CSV
        df.to_csv(filename, index=False, encoding='utf-8-sig')
        logger.info(f"Data saved to: {filename}")
        
        return filename

def main():
    """Fungsi utama program"""
    print("🎓 IMPROVED PROGRAM SCRAPING BERITA RADAR SURABAYA v2.0")
    print("👨‍🏫 Dibuat oleh Dosen Data Mining & Full Stack Engineer")
    print("📊 Pengalaman 30 tahun dalam data mining dan web scraping")
    print("🏆 Sertifikasi internasional dalam web scraping")
    print("=" * 70)

    # Get user input
    while True:
        keyword = input("\n🔍 Masukkan keyword pencarian berita: ").strip()
        if keyword:
            break
        print("❌ Keyword tidak boleh kosong!")

    while True:
        try:
            max_articles_input = input("📊 Jumlah maksimal artikel (default 5, maks 500): ").strip()
            if not max_articles_input:
                max_articles = 5
                break
            max_articles = int(max_articles_input)
            if 1 <= max_articles <= 500:
                break
            else:
                print("❌ Masukkan angka antara 1-500")
        except ValueError:
            print("❌ Masukkan angka yang valid!")

    print(f"\n📝 Keyword yang akan dicari: '{keyword}'")
    print(f"📈 Maksimal artikel yang akan diambil: {max_articles}")

    # Initialize scraper
    config = ScrapingConfig(
        max_articles_per_page=20,
        max_pages_to_check=30,
        max_extra_articles_to_fetch=100,
        request_timeout=30,
        max_retries=3,
        delay_between_requests=(1.0, 3.0),
        max_concurrent_requests=3
    )
    
    scraper = RadarSurabayaScraper(config)

    try:
        # Start scraping
        start_time = time.time()
        articles = scraper.scrape_articles(keyword, max_articles)
        end_time = time.time()
        
        if articles:
            print("\n" + "=" * 70)
            print("📊 HASIL SCRAPING:")
            print("=" * 70)
            
            # Display results
            for i, article in enumerate(articles, 1):
                print(f"\n{i}. {article.title}")
                print(f"   📅 {article.date_published or 'Tanggal tidak ditemukan'}")
                print(f"   🔗 {article.url}")
                print(f"   📊 Skor: {article.relevance_score:.3f}, Kata: {article.word_count}")
                if article.extraction_errors:
                    print(f"   ⚠️ Errors: {', '.join(article.extraction_errors)}")
            
            # Statistics
            print(f"\n📈 Statistik:")
            print(f"   • Total artikel relevan: {len(articles)}")
            print(f"   • Waktu eksekusi: {end_time - start_time:.2f} detik")
            print(f"   • Rata-rata skor relevansi: {sum(a.relevance_score for a in articles) / len(articles):.3f}")
            print(f"   • Rata-rata jumlah kata: {sum(a.word_count for a in articles) / len(articles):.0f}")

            # Save to CSV
            filename = scraper.save_to_csv(articles, keyword)
            if filename:
                print(f"✅ File berhasil disimpan: {filename}")

        else:
            print("\n❌ Maaf, tidak ada data yang berhasil diambil.")
            print("💡 Tips:")
            print("   • Pastikan keyword yang dimasukkan spesifik")
            print("   • Periksa koneksi internet")
            print("   • Coba keyword lain")
            
    except KeyboardInterrupt:
        print("\n⚠️ Program dihentikan oleh user")
    except Exception as e:
        logger.error(f"Unexpected error: {e}")
        print(f"\n❌ Terjadi error: {e}")
        print("💡 Silakan coba lagi atau hubungi developer")

    print("\n👋 Terima kasih telah menggunakan program ini!")
    print("📚 Gunakan data dengan bijak dan sesuai etika riset")

if __name__ == "__main__":
    main()