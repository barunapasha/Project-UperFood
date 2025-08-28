# -*- coding: utf-8 -*-
"""
Konfigurasi untuk Radar Surabaya Scraper
"""

# Konfigurasi dasar
BASE_URL = "https://radarsurabaya.jawapos.com"
REQUEST_TIMEOUT = 30
MAX_RETRIES = 3

# Konfigurasi scraping
MAX_ARTICLES_PER_PAGE = 20
MAX_PAGES_TO_CHECK = 50
MAX_EXTRA_ARTICLES_TO_FETCH = 200
MAX_ARTICLES_LIMIT = 500

# Konfigurasi delay (dalam detik)
MIN_DELAY = 1
MAX_DELAY = 3
PAGE_DELAY_MIN = 1
PAGE_DELAY_MAX = 2
RETRY_DELAY_MIN = 2
RETRY_DELAY_MAX = 5

# Konfigurasi headers
DEFAULT_HEADERS = {
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
}

# Selector patterns untuk artikel
ARTICLE_SELECTORS = [
    'div.article-item h2 a',
    'div.news-item h2 a',
    'article h2 a',
    'div.post-item h2 a',
    '.article-title a',
    '.news-title a',
    'h2 a[href*="/"]',
    'a[href*="/"]'
]

# Selector patterns untuk judul
TITLE_SELECTORS = [
    'h1.article-title',
    'h1.news-title',
    'h1.post-title',
    'h1',
    '.article-title',
    '.news-title',
    '.post-title',
    'title'
]

# Selector patterns untuk tanggal
DATE_SELECTORS = [
    'time[datetime]',
    '.article-date time',
    '.news-date time',
    '.post-date time',
    'time',
    '[datetime]',
    '.date',
    '.publish-date',
    '.article-date',
    '.news-date',
    '.post-date',
    '.tanggal',
    '.waktu'
]

# Selector patterns untuk konten
CONTENT_SELECTORS = [
    '.article-content',
    '.news-content',
    '.post-content',
    '.content-body',
    '.article-body',
    '.news-body',
    '.post-body',
    'article',
    '.content',
    '.entry-content'
]

# Pattern untuk mengecualikan link
EXCLUDE_PATTERNS = [
    '#', '.pdf', '.jpg', '.png', '.jpeg', '.gif',
    '/tag/', '/category/', '/author/', '/page/',
    '/search', '/login', '/register', '/contact'
]

# Pattern untuk elemen yang tidak diinginkan dalam konten
UNWANTED_ELEMENTS = [
    'script', 'style', 'nav', 'aside', 'header', 
    'footer', 'noscript', 'iframe', 'form', 'button'
]

# Pattern untuk class yang mengandung konten tidak diinginkan
UNWANTED_CLASSES = [
    'ads', 'advertisement', 'promo', 'related', 
    'widget', 'sidebar', 'footer', 'social', 
    'share', 'comment', 'navigation', 'menu'
]

# Pattern untuk tanggal
DATE_PATTERNS = [
    r'(\d{1,2}\s+(Januari|Februari|Maret|April|Mei|Juni|Juli|Agustus|September|Oktober|November|Desember)\s+\d{4}\s*\d{1,2}:\d{2})',
    r'(\d{1,2}\s+(Januari|Februari|Maret|April|Mei|Juni|Juli|Agustus|September|Oktober|November|Desember)\s+\d{4})',
    r'(\d{1,2}[/-]\d{1,2}[/-]\d{4}\s*\d{1,2}:\d{2})',
    r'(\d{1,2}[/-]\d{1,2}[/-]\d{4})',
    r'(\d{4}[/-]\d{2}[/-]\d{2})'
]

# Konfigurasi output
OUTPUT_DIR = "output"
CSV_ENCODING = "utf-8-sig"
LOG_FILE = "scraper.log"

# Konfigurasi logging
LOG_LEVEL = "INFO"
LOG_FORMAT = "%(asctime)s - %(levelname)s - %(message)s"

# Konfigurasi validasi
MIN_TITLE_LENGTH = 10
MIN_CONTENT_LENGTH = 100
MIN_DATE_LENGTH = 5