# -*- coding: utf-8 -*-
"""
Konfigurasi Program Scraping Radar Surabaya
Dibuat oleh: Dosen Data Mining & Full Stack Engineer
"""

# ============================================================================
# KONFIGURASI DASAR
# ============================================================================

# URL dasar website
BASE_URL = "https://radarsurabaya.jawapos.com"

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

# Timeout untuk request (dalam detik)
REQUEST_TIMEOUT = 20

# ============================================================================
# KONFIGURASI HEADERS
# ============================================================================

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
    'Referer': BASE_URL
}

# ============================================================================
# KONFIGURASI SELECTOR
# ============================================================================

# Selector untuk artikel
ARTICLE_SELECTORS = [
    # Selector utama berdasarkan inspect element yang diberikan
    '/html/body/div[3]/div/div/div[2]/section/div[3]/div[1]/div[2]/h2/a',
    # Selector alternatif
    'div.article-item h2 a',
    'div.news-item h2 a',
    'article h2 a',
    'div.post-item h2 a',
    '.article-title a',
    '.news-title a',
    'h2 a[href*="/"]',
    'a[href*="/"]'
]

# Selector untuk judul artikel
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

# Selector untuk tanggal
DATE_SELECTORS = [
    # Selector berdasarkan inspect element yang diberikan
    '/html/body/div[3]/div/div/div[2]/section/div[3]/div[1]/div[2]/date',
    # Selector alternatif
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

# Selector untuk konten artikel
CONTENT_SELECTORS = [
    # Selector utama berdasarkan inspect element
    '/html/body/div[3]/div/div/div[2]/div/div[1]/article',
    # Selector alternatif
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

# ============================================================================
# KONFIGURASI FILTER
# ============================================================================

# URL yang harus dihindari
EXCLUDED_URL_PATTERNS = [
    '#', '.pdf', '.jpg', '.png', '/tag/', '/category/', '/author/', '/page/'
]

# Class yang mengandung iklan/promo (akan dihapus)
AD_CLASS_PATTERNS = [
    'ads', 'advertisement', 'promo', 'related', 'widget', 
    'sidebar', 'footer', 'social', 'share'
]

# Elemen yang akan dihapus dari konten
UNWANTED_ELEMENTS = [
    'script', 'style', 'nav', 'aside', 'header', 'footer', 
    'noscript', 'iframe'
]

# ============================================================================
# KONFIGURASI OUTPUT
# ============================================================================

# Format nama file output
OUTPUT_FILENAME_FORMAT = "berita_radar_surabaya_{keyword}_{timestamp}.csv"

# Encoding untuk file CSV
CSV_ENCODING = 'utf-8-sig'

# Kolom yang akan disimpan
OUTPUT_COLUMNS = [
    'judul_berita',
    'link_berita', 
    'tanggal_rilis',
    'detail_konten'
]

# ============================================================================
# KONFIGURASI VALIDASI
# ============================================================================

# Panjang minimum untuk judul artikel
MIN_TITLE_LENGTH = 10

# Panjang minimum untuk konten artikel
MIN_CONTENT_LENGTH = 50

# Panjang minimum untuk tanggal
MIN_DATE_LENGTH = 5

# ============================================================================
# KONFIGURASI PESAN
# ============================================================================

# Pesan status
MESSAGES = {
    'start': "MEMULAI SCRAPING BERITA RADAR SURABAYA UNTUK KEYWORD: '{keyword}'",
    'collecting': "Mengumpulkan hingga {count} artikel kandidat...",
    'page_fetch': "Mengambil halaman pencarian {page}...",
    'page_success': "✅ Menemukan {count} artikel kandidat di halaman {page}",
    'page_empty': "ℹ️ Tidak menemukan artikel baru di halaman {page}",
    'processing': "[{current}/{total}] Memproses: {title}...",
    'article_relevant': "✅ Artikel relevan ditemukan ({found}/{max})",
    'article_not_relevant': "❌ Artikel tidak relevan (tidak mengandung keyword '{keyword}')",
    'scraping_complete': "✅ Scraping selesai!",
    'no_data': "❌ Tidak ada data yang berhasil diambil",
    'file_saved': "💾 Data disimpan ke: {filename}",
    'cleaning_start': "🔍 Memulai pembersihan dan perbaikan DataFrame...",
    'cleaning_complete': "✅ Pembersihan dan perbaikan DataFrame selesai."
}

# ============================================================================
# KONFIGURASI DEBUG
# ============================================================================

# Mode debug
DEBUG_MODE = False

# Log level
LOG_LEVEL = 'INFO'  # DEBUG, INFO, WARNING, ERROR

# Simpan log ke file
SAVE_LOG_TO_FILE = False
LOG_FILENAME = "scraping_log.txt"

# ============================================================================
# KONFIGURASI GOOGLE COLAB
# ============================================================================

# Konfigurasi khusus untuk Google Colab
COLAB_CONFIG = {
    'auto_download': True,
    'show_progress': True,
    'use_tqdm': True
}

# ============================================================================
# FUNGSI KONFIGURASI
# ============================================================================

def get_config():
    """Mengembalikan semua konfigurasi dalam bentuk dictionary"""
    return {
        'base_url': BASE_URL,
        'max_articles_per_page': MAX_ARTICLES_PER_PAGE,
        'max_pages_to_check': MAX_PAGES_TO_CHECK,
        'max_extra_articles_to_fetch': MAX_EXTRA_ARTICLES_TO_FETCH,
        'max_articles_limit': MAX_ARTICLES_LIMIT,
        'min_delay': MIN_DELAY,
        'max_delay': MAX_DELAY,
        'request_timeout': REQUEST_TIMEOUT,
        'headers': DEFAULT_HEADERS,
        'article_selectors': ARTICLE_SELECTORS,
        'title_selectors': TITLE_SELECTORS,
        'date_selectors': DATE_SELECTORS,
        'content_selectors': CONTENT_SELECTORS,
        'excluded_url_patterns': EXCLUDED_URL_PATTERNS,
        'ad_class_patterns': AD_CLASS_PATTERNS,
        'unwanted_elements': UNWANTED_ELEMENTS,
        'output_filename_format': OUTPUT_FILENAME_FORMAT,
        'csv_encoding': CSV_ENCODING,
        'output_columns': OUTPUT_COLUMNS,
        'min_title_length': MIN_TITLE_LENGTH,
        'min_content_length': MIN_CONTENT_LENGTH,
        'min_date_length': MIN_DATE_LENGTH,
        'messages': MESSAGES,
        'debug_mode': DEBUG_MODE,
        'log_level': LOG_LEVEL,
        'save_log_to_file': SAVE_LOG_TO_FILE,
        'log_filename': LOG_FILENAME,
        'colab_config': COLAB_CONFIG
    }

def update_config(**kwargs):
    """Update konfigurasi dengan parameter yang diberikan"""
    global (
        MAX_ARTICLES_PER_PAGE, MAX_PAGES_TO_CHECK, MAX_EXTRA_ARTICLES_TO_FETCH,
        MAX_ARTICLES_LIMIT, MIN_DELAY, MAX_DELAY, REQUEST_TIMEOUT,
        DEBUG_MODE, LOG_LEVEL, SAVE_LOG_TO_FILE
    )
    
    for key, value in kwargs.items():
        if key == 'max_articles_per_page':
            MAX_ARTICLES_PER_PAGE = value
        elif key == 'max_pages_to_check':
            MAX_PAGES_TO_CHECK = value
        elif key == 'max_extra_articles_to_fetch':
            MAX_EXTRA_ARTICLES_TO_FETCH = value
        elif key == 'max_articles_limit':
            MAX_ARTICLES_LIMIT = value
        elif key == 'min_delay':
            MIN_DELAY = value
        elif key == 'max_delay':
            MAX_DELAY = value
        elif key == 'request_timeout':
            REQUEST_TIMEOUT = value
        elif key == 'debug_mode':
            DEBUG_MODE = value
        elif key == 'log_level':
            LOG_LEVEL = value
        elif key == 'save_log_to_file':
            SAVE_LOG_TO_FILE = value

def print_config():
    """Mencetak konfigurasi saat ini"""
    config = get_config()
    print("🔧 KONFIGURASI PROGRAM SCRAPING RADAR SURABAYA")
    print("=" * 50)
    
    for key, value in config.items():
        if key not in ['headers', 'messages', 'colab_config']:
            print(f"{key}: {value}")
    
    print("\n📋 SELECTORS:")
    print(f"Article selectors: {len(ARTICLE_SELECTORS)} selector")
    print(f"Title selectors: {len(TITLE_SELECTORS)} selector")
    print(f"Date selectors: {len(DATE_SELECTORS)} selector")
    print(f"Content selectors: {len(CONTENT_SELECTORS)} selector")
    
    print("\n🚫 FILTERS:")
    print(f"Excluded URL patterns: {len(EXCLUDED_URL_PATTERNS)} pattern")
    print(f"Ad class patterns: {len(AD_CLASS_PATTERNS)} pattern")
    print(f"Unwanted elements: {len(UNWANTED_ELEMENTS)} element")

if __name__ == "__main__":
    # Test konfigurasi
    print_config()