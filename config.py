# -*- coding: utf-8 -*-
"""
Konfigurasi Program Scraping Berita Radar Surabaya
Dibuat oleh: Dosen Data Mining & Full Stack Engineer
"""

# ============================================================================
# KONFIGURASI UTAMA
# ============================================================================

# URL dasar website
BASE_URL = "https://radarsurabaya.jawapos.com"

# Batasan jumlah artikel
MAX_ARTICLES_PER_PAGE = 20
MAX_PAGES_TO_CHECK = 50
MAX_EXTRA_ARTICLES_TO_FETCH = 200
MAX_ARTICLES_LIMIT = 500

# ============================================================================
# KONFIGURASI HEADERS HTTP
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
# KONFIGURASI SELECTOR CSS/XPath
# ============================================================================

# Selector untuk link artikel
ARTICLE_LINK_SELECTORS = [
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

# Selector untuk judul artikel
TITLE_SELECTORS = [
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

# Selector untuk tanggal rilis
DATE_SELECTORS = [
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

# Selector untuk konten artikel
CONTENT_SELECTORS = [
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

# ============================================================================
# KONFIGURASI DELAY DAN TIMEOUT
# ============================================================================

# Delay antara request (dalam detik)
MIN_DELAY = 1
MAX_DELAY = 3
PAGE_DELAY_MIN = 1
PAGE_DELAY_MAX = 2

# Timeout untuk request (dalam detik)
REQUEST_TIMEOUT = 20

# ============================================================================
# KONFIGURASI FILTERING
# ============================================================================

# Pattern untuk URL yang tidak diinginkan
EXCLUDED_URL_PATTERNS = [
    r'#',
    r'\.pdf$',
    r'\.jpg$',
    r'\.png$',
    r'/foto/',
    r'/video/',
    r'/indeks',
    r'/tag/',
    r'/category/',
    r'/search/',
    r'/about',
    r'/contact'
]

# Pattern untuk class yang tidak diinginkan (iklan, sidebar, dll)
EXCLUDED_CLASS_PATTERNS = [
    r'.*(ads|advertisement|promo|related|widget|sidebar|footer|banner).*'
]

# Pattern untuk ID yang tidak diinginkan
EXCLUDED_ID_PATTERNS = [
    r'.*(ads|advertisement|promo|related|widget|banner).*'
]

# ============================================================================
# KONFIGURASI VALIDASI
# ============================================================================

# Minimum panjang judul artikel
MIN_TITLE_LENGTH = 10

# Minimum panjang konten artikel
MIN_CONTENT_LENGTH = 50

# Threshold relevansi untuk keyword multi-kata (dalam persen)
RELEVANCE_THRESHOLD = 0.7

# ============================================================================
# KONFIGURASI OUTPUT
# ============================================================================

# Encoding untuk file CSV
CSV_ENCODING = 'utf-8-sig'

# Format timestamp untuk nama file
TIMESTAMP_FORMAT = "%Y%m%d_%H%M%S"

# Prefix nama file
FILE_PREFIX = "berita_radar_surabaya"

# ============================================================================
# KONFIGURASI PESAN
# ============================================================================

MESSAGES = {
    'program_title': '🎓 PROGRAM SCRAPING BERITA RADAR SURABAYA',
    'developer_info': '👨‍🏫 Dibuat oleh Dosen Data Mining & Full Stack Engineer',
    'experience_info': '📊 Pengalaman 30 tahun dalam web scraping dan data mining',
    'certification_info': '🏆 Sertifikasi Internasional Web Scraping & Data Mining',
    'start_scraping': 'MEMULAI SCRAPING BERITA RADAR SURABAYA UNTUK KEYWORD:',
    'scraping_complete': '✅ Scraping selesai!',
    'no_data': '❌ Tidak ada data yang berhasil diambil',
    'file_saved': '💾 Data disimpan ke:',
    'thank_you': '👋 Terima kasih telah menggunakan program ini!',
    'ethics_reminder': '📚 Gunakan data dengan bijak dan sesuai etika riset'
}

# ============================================================================
# KONFIGURASI DEBUG
# ============================================================================

# Mode debug (True untuk menampilkan informasi detail)
DEBUG_MODE = False

# Log level (INFO, WARNING, ERROR, DEBUG)
LOG_LEVEL = 'INFO'

# Simpan log ke file
SAVE_LOG_TO_FILE = False
LOG_FILE = 'scraping_log.txt'