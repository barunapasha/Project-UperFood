# Radar Surabaya Web Scraper - Dokumentasi Lengkap

## 📋 Daftar Isi
1. [Overview](#overview)
2. [Arsitektur Sistem](#arsitektur-sistem)
3. [Instalasi & Setup](#instalasi--setup)
4. [Penggunaan](#penggunaan)
5. [API Reference](#api-reference)
6. [Konfigurasi](#konfigurasi)
7. [Testing](#testing)
8. [Troubleshooting](#troubleshooting)
9. [Best Practices](#best-practices)
10. [FAQ](#faq)

## 🎯 Overview

Radar Surabaya Web Scraper adalah aplikasi web scraping profesional yang dikembangkan oleh Data Mining Expert & Full Stack Engineer dengan pengalaman 30 tahun dan sertifikasi internasional web scraping. Aplikasi ini dirancang untuk mengekstrak data berita dari website https://radarsurabaya.jawapos.com/ dengan fitur pencarian, ekstraksi konten, dan export data.

### Fitur Utama
- ✅ **Pencarian Artikel**: Berdasarkan kata kunci yang dimasukkan user
- ✅ **Ekstraksi Konten**: Judul, tanggal, dan konten artikel (tanpa iklan)
- ✅ **Export Data**: Format CSV dan JSON
- ✅ **Logging Lengkap**: Monitoring dan debugging
- ✅ **Error Handling**: Robust error handling
- ✅ **Rate Limiting**: Menghormati server target
- ✅ **Professional Headers**: User-Agent yang valid

## 🏗️ Arsitektur Sistem

### Struktur File
```
radar-scraper/
├── radar_scraper.py          # Main scraper application
├── config.py                 # Configuration settings
├── requirements.txt          # Python dependencies
├── install.sh               # Installation script
├── run.sh                   # Run script
├── test_scraper.py          # Test suite
├── run_tests.sh             # Test runner
├── example_usage.py         # Usage examples
├── README.md                # Basic documentation
├── DOCUMENTATION.md         # This file
└── venv/                    # Virtual environment (created during install)
```

### Komponen Utama

#### 1. RadarSurabayaScraper Class
- **Fungsi**: Main scraper class yang menangani semua operasi scraping
- **Method Utama**:
  - `search_articles()`: Generate search URL
  - `get_search_results()`: Extract article links
  - `extract_article_content()`: Extract detailed content
  - `scrape_articles()`: Main orchestration method
  - `save_to_csv()` / `save_to_json()`: Export data

#### 2. Configuration System
- **File**: `config.py`
- **Fungsi**: Centralized configuration management
- **Kategori**:
  - Website configuration
  - Scraping settings
  - User agent settings
  - Selector configuration
  - Output settings
  - Logging configuration

#### 3. Testing Framework
- **File**: `test_scraper.py`
- **Fungsi**: Unit tests dan integration tests
- **Coverage**: All major functions and error scenarios

## 🚀 Instalasi & Setup

### Prerequisites
- Python 3.7+
- pip3
- Internet connection

### Quick Installation
```bash
# Clone repository
git clone <repository-url>
cd radar-scraper

# Run installation script
./install.sh

# Run the scraper
./run.sh
```

### Manual Installation
```bash
# Create virtual environment
python3 -m venv venv

# Activate virtual environment
source venv/bin/activate

# Install dependencies
pip install -r requirements.txt

# Run the scraper
python radar_scraper.py
```

## 📖 Penggunaan

### 1. Basic Usage
```bash
python radar_scraper.py
```

### 2. Programmatic Usage
```python
from radar_scraper import RadarSurabayaScraper

# Initialize scraper
scraper = RadarSurabayaScraper()

# Search and scrape articles
articles = scraper.scrape_articles("harga jagung")

# Save results
scraper.save_to_csv("results.csv")
scraper.save_to_json("results.json")
```

### 3. Example Usage
```bash
python example_usage.py
```

## 🔧 API Reference

### RadarSurabayaScraper Class

#### Constructor
```python
RadarSurabayaScraper()
```
- **Returns**: Scraper instance
- **Description**: Initialize scraper with default configuration

#### Methods

##### search_articles(query)
```python
def search_articles(self, query: str) -> str
```
- **Parameters**: `query` (str) - Search keyword
- **Returns**: Search URL (str)
- **Description**: Generate search URL for given query

##### get_search_results(search_url)
```python
def get_search_results(self, search_url: str) -> List[str]
```
- **Parameters**: `search_url` (str) - Search results URL
- **Returns**: List of article URLs
- **Description**: Extract article links from search results

##### extract_article_content(article_url)
```python
def extract_article_content(self, article_url: str) -> Dict
```
- **Parameters**: `article_url` (str) - Article URL
- **Returns**: Article data dictionary
- **Description**: Extract detailed article content

##### scrape_articles(query)
```python
def scrape_articles(self, query: str) -> List[Dict]
```
- **Parameters**: `query` (str) - Search keyword
- **Returns**: List of article data dictionaries
- **Description**: Complete scraping process

##### save_to_csv(filename=None)
```python
def save_to_csv(self, filename: str = None) -> None
```
- **Parameters**: `filename` (str, optional) - Output filename
- **Description**: Save scraped data to CSV file

##### save_to_json(filename=None)
```python
def save_to_json(self, filename: str = None) -> None
```
- **Parameters**: `filename` (str, optional) - Output filename
- **Description**: Save scraped data to JSON file

## ⚙️ Konfigurasi

### Website Configuration
```python
BASE_URL = "https://radarsurabaya.jawapos.com"
SEARCH_URL_PATTERN = "{base_url}/search?q={query}"
```

### Scraping Configuration
```python
REQUEST_TIMEOUT = 30          # Timeout in seconds
REQUEST_DELAY = 2             # Delay between requests
MAX_ARTICLES_PER_SEARCH = 10  # Max articles per search
```

### Selector Configuration
```python
TITLE_SELECTORS = ['h1', '.article-title', '.post-title']
DATE_SELECTORS = ['.article-date', '.post-date', 'time']
CONTENT_SELECTORS = ['article', '.article-content', '.post-content']
```

### Output Configuration
```python
TIMESTAMP_FORMAT = '%Y%m%d_%H%M%S'
OUTPUT_ENCODING = 'utf-8'
EXPORT_FORMATS = ['csv', 'json']
```

## 🧪 Testing

### Run All Tests
```bash
./run_tests.sh
```

### Run Specific Test
```bash
python -m unittest test_scraper.TestRadarSurabayaScraper.test_init
```

### Test Coverage
- ✅ Initialization tests
- ✅ Search functionality tests
- ✅ Content extraction tests
- ✅ File export tests
- ✅ Error handling tests
- ✅ Content filtering tests

## 🔍 Troubleshooting

### Common Issues

#### 1. No Articles Found
**Problem**: Scraper returns empty results
**Solutions**:
- Check internet connection
- Verify website accessibility
- Try different search keywords
- Check log file for errors

#### 2. Network Timeout
**Problem**: Requests timeout
**Solutions**:
- Increase `REQUEST_TIMEOUT` in config
- Check network stability
- Try again later

#### 3. Parsing Errors
**Problem**: Content extraction fails
**Solutions**:
- Website structure may have changed
- Update selectors in config
- Check log file for specific errors

#### 4. Permission Errors
**Problem**: Cannot write output files
**Solutions**:
- Check directory permissions
- Run with appropriate user privileges
- Verify disk space

### Debug Mode
Enable detailed logging by modifying `config.py`:
```python
LOG_LEVEL = 'DEBUG'
SAVE_RAW_HTML = True
```

## 📚 Best Practices

### 1. Ethical Scraping
- ✅ Respect robots.txt
- ✅ Use reasonable delays between requests
- ✅ Don't overload the server
- ✅ Follow website's Terms of Service

### 2. Error Handling
- ✅ Always handle network errors
- ✅ Implement retry mechanisms
- ✅ Log all errors for debugging
- ✅ Graceful degradation

### 3. Data Quality
- ✅ Validate extracted data
- ✅ Remove duplicate content
- ✅ Clean and normalize data
- ✅ Handle missing fields

### 4. Performance
- ✅ Use session objects for connection reuse
- ✅ Implement rate limiting
- ✅ Cache results when appropriate
- ✅ Optimize selectors

### 5. Maintenance
- ✅ Regular testing
- ✅ Monitor website changes
- ✅ Update selectors as needed
- ✅ Keep dependencies updated

## ❓ FAQ

### Q: Apakah aplikasi ini legal?
**A**: Aplikasi ini dirancang untuk tujuan edukasi dan penelitian. Pastikan untuk mematuhi Terms of Service website target dan menggunakan dengan bertanggung jawab.

### Q: Berapa lama waktu yang dibutuhkan untuk scraping?
**A**: Waktu tergantung pada jumlah artikel dan delay yang dikonfigurasi. Untuk 10 artikel dengan delay 2 detik, sekitar 20-30 detik.

### Q: Apakah data yang di-scrape akurat?
**A**: Aplikasi menggunakan multiple selectors untuk memastikan akurasi. Namun, struktur website dapat berubah, sehingga perlu monitoring dan update.

### Q: Bagaimana jika website berubah struktur?
**A**: Update selector di file `config.py` sesuai dengan struktur baru website.

### Q: Apakah aplikasi mendukung proxy?
**A**: Ya, dapat dikonfigurasi di `config.py` dengan mengatur `USE_PROXY = True` dan `PROXY_CONFIG`.

### Q: Bagaimana cara menambah format export baru?
**A**: Tambahkan method baru di class `RadarSurabayaScraper` dan update `EXPORT_FORMATS` di config.

### Q: Apakah aplikasi mendukung multi-threading?
**A**: Saat ini tidak, untuk menghormati server target. Implementasi multi-threading dapat ditambahkan dengan konfigurasi yang tepat.

### Q: Bagaimana cara backup data?
**A**: Data otomatis disimpan dalam format CSV dan JSON dengan timestamp. Backup manual dapat dilakukan dengan menyalin file output.

---

## 📞 Support

Untuk pertanyaan atau masalah teknis, silakan:
1. Periksa dokumentasi ini
2. Cek log file (`radar_scraper.log`)
3. Jalankan test suite (`./run_tests.sh`)
4. Periksa troubleshooting section

---

**Dikembangkan oleh Data Mining Expert & Full Stack Engineer dengan pengalaman 30 tahun dan sertifikasi internasional web scraping.**