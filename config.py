#!/usr/bin/env python3
"""
Konfigurasi Radar Surabaya Web Scraper
Configuration file for Radar Surabaya Web Scraper
"""

# ============================================================================
# WEBSITE CONFIGURATION
# ============================================================================

# Base URL website target
BASE_URL = "https://radarsurabaya.jawapos.com"

# Search URL pattern
SEARCH_URL_PATTERN = "{base_url}/search?q={query}"

# ============================================================================
# SCRAPING CONFIGURATION
# ============================================================================

# Timeout untuk HTTP requests (dalam detik)
REQUEST_TIMEOUT = 30

# Delay antara requests (dalam detik) - untuk menghormati server
REQUEST_DELAY = 2

# Maksimum artikel yang akan di-scrape per pencarian
MAX_ARTICLES_PER_SEARCH = 10

# Maksimum artikel dari halaman utama (jika search gagal)
MAX_ARTICLES_FROM_MAIN = 5

# ============================================================================
# USER AGENT CONFIGURATION
# ============================================================================

# User Agent yang digunakan untuk requests
USER_AGENT = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36'

# Headers tambahan untuk requests
ADDITIONAL_HEADERS = {
    'Accept': 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
    'Accept-Language': 'en-US,en;q=0.5',
    'Accept-Encoding': 'gzip, deflate',
    'Connection': 'keep-alive',
    'Upgrade-Insecure-Requests': '1',
}

# ============================================================================
# SELECTOR CONFIGURATION
# ============================================================================

# Selector untuk judul artikel
TITLE_SELECTORS = [
    'h1',
    '.article-title',
    '.post-title',
    '.entry-title',
    'h2.article-title',
    'h1.entry-title',
    '.title',
    'h1.title'
]

# Selector untuk tanggal artikel
DATE_SELECTORS = [
    '.article-date',
    '.post-date',
    '.entry-date',
    '.published-date',
    'time',
    '.date',
    'span[class*="date"]',
    '.publish-date',
    '.news-date'
]

# Selector untuk konten artikel
CONTENT_SELECTORS = [
    'article',
    '.article-content',
    '.post-content',
    '.entry-content',
    '.content',
    '.article-body',
    '.post-body',
    '.news-content',
    '.story-content'
]

# Selector untuk link artikel (untuk search results)
ARTICLE_LINK_SELECTORS = [
    'h2 a',
    '.article-title a',
    '.post-title a',
    '.news-title a',
    'a[href*="/berita/"]',
    'a[href*="/news/"]',
    'a[href*="/artikel/"]'
]

# ============================================================================
# OUTPUT CONFIGURATION
# ============================================================================

# Format timestamp untuk nama file
TIMESTAMP_FORMAT = '%Y%m%d_%H%M%S'

# Prefix untuk nama file output
CSV_FILE_PREFIX = "radar_surabaya_articles"
JSON_FILE_PREFIX = "radar_surabaya_articles"

# Encoding untuk file output
OUTPUT_ENCODING = 'utf-8'

# ============================================================================
# LOGGING CONFIGURATION
# ============================================================================

# Level logging
LOG_LEVEL = 'INFO'

# Format log
LOG_FORMAT = '%(asctime)s - %(levelname)s - %(message)s'

# Nama file log
LOG_FILENAME = 'radar_scraper.log'

# ============================================================================
# ERROR HANDLING CONFIGURATION
# ============================================================================

# Maksimum retry untuk failed requests
MAX_RETRIES = 3

# Delay antara retry (dalam detik)
RETRY_DELAY = 5

# ============================================================================
# CONTENT FILTERING CONFIGURATION
# ============================================================================

# Elemen yang akan dihapus dari konten artikel
ELEMENTS_TO_REMOVE = [
    'script',
    'style',
    'nav',
    'header',
    'footer',
    'aside',
    '.advertisement',
    '.ads',
    '.social-share',
    '.related-posts',
    '.comments'
]

# ============================================================================
# ADVANCED CONFIGURATION
# ============================================================================

# Apakah menggunakan proxy (True/False)
USE_PROXY = False

# Konfigurasi proxy (jika USE_PROXY = True)
PROXY_CONFIG = {
    'http': 'http://proxy:port',
    'https': 'https://proxy:port'
}

# Apakah menyimpan raw HTML untuk debugging
SAVE_RAW_HTML = False

# Direktori untuk menyimpan raw HTML
RAW_HTML_DIR = 'raw_html'

# ============================================================================
# VALIDATION CONFIGURATION
# ============================================================================

# Minimum panjang konten artikel (karakter)
MIN_CONTENT_LENGTH = 50

# Minimum panjang judul artikel (karakter)
MIN_TITLE_LENGTH = 10

# ============================================================================
# EXPORT CONFIGURATION
# ============================================================================

# Format yang akan di-export
EXPORT_FORMATS = ['csv', 'json']

# Apakah menampilkan progress bar
SHOW_PROGRESS = True

# Apakah menampilkan hasil di console
SHOW_CONSOLE_OUTPUT = True