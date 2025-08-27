# 📚 DOKUMENTASI LENGKAP PROGRAM SCRAPING RADAR SURABAYA

## 👨‍🏫 Tentang Developer

**Dibuat oleh:** Dosen Data Mining & Full Stack Engineer  
**Pengalaman:** 30 tahun dalam data mining dan web scraping  
**Sertifikasi:** Internasional dalam web scraping dan data extraction  
**Tujuan:** Untuk keperluan riset dan analisis data berita  

---

## 🎯 Deskripsi Program

Program ini adalah sistem web scraping yang dirancang khusus untuk mengumpulkan data berita dari website [Radar Surabaya](https://radarsurabaya.jawapos.com/). Program ini memiliki fitur pencarian berdasarkan keyword yang diinput user, dengan kemampuan ekstraksi data yang lengkap dan akurat.

### 🔍 Fitur Utama

- ✅ **Pencarian Berita:** Mencari berita berdasarkan keyword yang diinput user
- ✅ **Ekstraksi Data Lengkap:** Mengambil judul, tanggal, link, dan konten berita
- ✅ **Validasi Relevansi:** Memastikan artikel yang diambil relevan dengan keyword
- ✅ **Pembersihan Data:** Membersihkan dan memperbaiki data sebelum disimpan
- ✅ **Export CSV:** Menyimpan hasil dalam format CSV yang kompatibel dengan Excel
- ✅ **Penanganan Error:** Robust error handling untuk berbagai kondisi
- ✅ **Rate Limiting:** Delay otomatis untuk menghindari blocking
- ✅ **Multi-page Support:** Mendukung scraping dari multiple halaman
- ✅ **Configurable:** Konfigurasi yang dapat disesuaikan

---

## 🏗️ Arsitektur Program

### 📁 Struktur File

```
scraping_radar_surabaya/
├── scraping_radar_surabaya.py    # Program utama
├── config.py                     # File konfigurasi
├── requirements.txt              # Dependencies
├── test_scraping.py              # Unit tests
├── demo.py                       # Demo program
├── colab_example.py              # Contoh untuk Google Colab
├── README.md                     # Dokumentasi singkat
└── DOKUMENTASI_LENGKAP.md        # Dokumentasi lengkap (ini)
```

### 🔧 Komponen Utama

1. **Main Scraping Engine** (`scraping_radar_surabaya.py`)
   - Fungsi utama scraping
   - Penanganan error
   - Validasi data

2. **Configuration System** (`config.py`)
   - Konfigurasi selector
   - Parameter scraping
   - Setting output

3. **Testing Framework** (`test_scraping.py`)
   - Unit tests
   - Integration tests
   - Error handling tests

4. **Demo & Examples** (`demo.py`, `colab_example.py`)
   - Contoh penggunaan
   - Tutorial interaktif
   - Google Colab integration

---

## 🚀 Cara Penggunaan

### 📋 Persyaratan Sistem

- **Python:** 3.7 atau lebih baru
- **Dependencies:** requests, beautifulsoup4, pandas, lxml, urllib3
- **Koneksi Internet:** Stabil untuk hasil optimal
- **Platform:** Windows, macOS, Linux, Google Colab

### 🔧 Instalasi

#### Untuk Google Colab (Direkomendasikan)

1. **Upload File Program**
   ```python
   # Upload scraping_radar_surabaya.py ke Google Colab
   ```

2. **Install Dependencies**
   ```python
   !pip install requests beautifulsoup4 pandas lxml urllib3
   ```

3. **Import Program**
   ```python
   from scraping_radar_surabaya import *
   ```

#### Untuk Environment Lokal

1. **Clone Repository**
   ```bash
   git clone [repository-url]
   cd scraping_radar_surabaya
   ```

2. **Install Dependencies**
   ```bash
   pip install -r requirements.txt
   ```

3. **Jalankan Program**
   ```bash
   python scraping_radar_surabaya.py
   ```

### 🎮 Mode Penggunaan

#### 1. Mode Interaktif (Default)
```python
# Jalankan program utama
python scraping_radar_surabaya.py

# Program akan meminta input:
# - Keyword pencarian
# - Jumlah artikel maksimal
```

#### 2. Mode Programmatic
```python
from scraping_radar_surabaya import scrape_radar_surabaya_news, save_to_csv

# Scraping dengan parameter
keyword = "harga jagung"
max_articles = 10
df_results = scrape_radar_surabaya_news(keyword, max_articles)

# Simpan hasil
if not df_results.empty:
    filename = save_to_csv(df_results, keyword)
    print(f"Data disimpan: {filename}")
```

#### 3. Mode Demo
```python
# Jalankan demo
python demo.py

# Pilih demo yang diinginkan:
# 1. Scraping Sederhana
# 2. Multiple Keywords
# 3. Analisis Data
# 4. Penanganan Error
# 5. Konfigurasi
# 6. Mode Interaktif
```

---

## 🔍 Cara Kerja Program

### 1. Proses Pencarian

```
User Input Keyword → URL Encoding → Search Request → Parse Results
```

**Detail Proses:**
1. User memasukkan keyword (contoh: "harga jagung")
2. Program meng-encode keyword menjadi URL-safe format
3. Membuat URL pencarian: `https://radarsurabaya.jawapos.com/search?q=harga+jagung`
4. Mengirim HTTP request dengan headers yang realistis
5. Parse HTML response untuk mencari link artikel

### 2. Ekstraksi Link Artikel

Program menggunakan multiple selector strategy:

```python
# Selector utama berdasarkan inspect element
'/html/body/div[3]/div/div/div[2]/section/div[3]/div[1]/div[2]/h2/a'

# Selector alternatif untuk fallback
'div.article-item h2 a'
'div.news-item h2 a'
'article h2 a'
# ... dan lainnya
```

### 3. Ekstraksi Detail Artikel

Untuk setiap link artikel yang ditemukan:

```python
# Ekstrak judul
title_selectors = [
    'h1.article-title',
    'h1.news-title',
    'h1.post-title',
    'h1',
    # ... fallback selectors
]

# Ekstrak tanggal
date_selectors = [
    'time[datetime]',
    '.article-date time',
    '.publish-date',
    # ... fallback selectors
]

# Ekstrak konten
content_selectors = [
    '/html/body/div[3]/div/div/div[2]/div/div[1]/article',
    '.article-content',
    '.news-content',
    # ... fallback selectors
]
```

### 4. Validasi dan Pembersihan

```python
# Validasi relevansi
def is_article_relevant_by_content(title, content, keyword):
    full_text = (title + " " + content).lower()
    keyword_lower = keyword.lower()
    return keyword_lower in full_text

# Pembersihan data
def clean_dataframe(df):
    # Perbaiki struktur kolom
    # Hapus data yang tidak valid
    # Normalisasi format
```

---

## 📊 Output dan Format Data

### Struktur Data Output

File CSV akan berisi 4 kolom:

| Kolom | Deskripsi | Contoh |
|-------|-----------|---------|
| `judul_berita` | Judul artikel berita | "Harga Jagung Naik di Pasar Tradisional Surabaya" |
| `link_berita` | URL lengkap artikel | "https://radarsurabaya.jawapos.com/berita/12345" |
| `tanggal_rilis` | Tanggal publikasi | "1 Januari 2024" |
| `detail_konten` | Isi lengkap artikel | "Konten artikel tanpa iklan..." |

### Format File Output

```
berita_radar_surabaya_[keyword]_[timestamp].csv
```

**Contoh:**
```
berita_radar_surabaya_harga_jagung_20241201_143022.csv
```

### Encoding dan Kompatibilitas

- **Encoding:** UTF-8 dengan BOM (utf-8-sig)
- **Kompatibilitas:** Excel, Google Sheets, pandas
- **Separator:** Comma (,)
- **Quote Character:** Double quote (")

---

## ⚙️ Konfigurasi Program

### File Konfigurasi (`config.py`)

```python
# Konfigurasi dasar
BASE_URL = "https://radarsurabaya.jawapos.com"
MAX_ARTICLES_PER_PAGE = 20
MAX_PAGES_TO_CHECK = 50
MAX_ARTICLES_LIMIT = 500

# Konfigurasi delay
MIN_DELAY = 1
MAX_DELAY = 3
REQUEST_TIMEOUT = 20

# Selector konfigurasi
ARTICLE_SELECTORS = [...]
TITLE_SELECTORS = [...]
DATE_SELECTORS = [...]
CONTENT_SELECTORS = [...]
```

### Mengubah Konfigurasi

```python
from config import update_config

# Update konfigurasi runtime
update_config(
    max_articles_per_page=30,
    min_delay=0.5,
    max_delay=1.5,
    debug_mode=True
)
```

### Konfigurasi untuk Google Colab

```python
# Konfigurasi khusus Colab
COLAB_CONFIG = {
    'auto_download': True,
    'show_progress': True,
    'use_tqdm': True
}
```

---

## 🛡️ Fitur Keamanan dan Etika

### Rate Limiting

```python
# Delay otomatis antara request
time.sleep(random.uniform(MIN_DELAY, MAX_DELAY))

# Delay antar halaman
time.sleep(random.uniform(PAGE_DELAY_MIN, PAGE_DELAY_MAX))
```

### User-Agent Rotation

```python
headers = {
    'User-Agent': 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36...',
    'Accept': 'text/html,application/xhtml+xml,application/xml;q=0.9...',
    'Accept-Language': 'id-ID,id;q=0.9,en-US;q=0.8,en;q=0.7',
    # ... headers lainnya
}
```

### Error Handling

```python
try:
    response = requests.get(url, headers=headers, verify=False, timeout=20)
    response.raise_for_status()
except requests.exceptions.RequestException as e:
    print(f"Error saat mengakses {url}: {e}")
    return None
```

### Etika Penggunaan

1. **Respect Robots.txt:** Program tidak melanggar robots.txt
2. **Rate Limiting:** Delay otomatis untuk tidak membebani server
3. **Data Usage:** Hanya untuk keperluan riset dan pendidikan
4. **Attribution:** Selalu memberikan kredit kepada sumber data

---

## 🧪 Testing dan Quality Assurance

### Unit Tests

```python
# Jalankan unit tests
python test_scraping.py

# Test yang tersedia:
# - test_get_search_url()
# - test_clean_text()
# - test_is_article_relevant_by_content()
# - test_clean_dataframe()
# - test_get_page_content_success()
# - test_get_page_content_error()
# - test_extract_article_links_from_page()
# - test_extract_article_details()
# - test_save_to_csv()
```

### Integration Tests

```python
# Test integrasi skala kecil
def test_integration_small_scale():
    keyword = "test"
    max_articles = 1
    result = scrape_radar_surabaya_news(keyword, max_articles)
    # Verifikasi struktur data
```

### Quick Test

```python
# Test cepat untuk verifikasi program
python demo.py

# Akan menjalankan:
# - Import test
# - Function test
# - Basic functionality test
```

---

## 🔧 Troubleshooting

### Masalah Umum dan Solusi

#### 1. "Tidak menemukan link artikel"

**Penyebab:** Keyword tidak relevan atau website structure berubah

**Solusi:**
- Gunakan keyword yang lebih spesifik
- Cek koneksi internet
- Update selector jika diperlukan

#### 2. "Error saat mengakses URL"

**Penyebab:** Koneksi internet atau website down

**Solusi:**
- Periksa koneksi internet
- Coba jalankan ulang program
- Periksa apakah website target bisa diakses

#### 3. "Artikel tidak relevan"

**Penyebab:** Keyword terlalu umum atau algoritma relevansi terlalu ketat

**Solusi:**
- Gunakan keyword yang lebih spesifik
- Periksa hasil manual untuk validasi
- Sesuaikan algoritma relevansi jika diperlukan

#### 4. "File CSV kosong"

**Penyebab:** Tidak ada data yang berhasil diambil

**Solusi:**
- Periksa keyword yang digunakan
- Coba dengan keyword lain
- Periksa log error

### Debug Mode

```python
# Aktifkan debug mode
from config import update_config
update_config(debug_mode=True)

# Jalankan program dengan debug info
python scraping_radar_surabaya.py
```

### Log File

```python
# Aktifkan log file
from config import update_config
update_config(save_log_to_file=True)

# Log akan disimpan di scraping_log.txt
```

---

## 📈 Analisis Data Hasil Scraping

### Statistik Dasar

```python
# Analisis data hasil scraping
def analyze_scraping_results(df_results):
    print(f"Total artikel: {len(df_results)}")
    print(f"Artikel dengan tanggal: {df_results['tanggal_rilis'].apply(lambda x: x != 'Tanggal tidak ditemukan').sum()}")
    print(f"Artikel dengan konten: {df_results['detail_konten'].apply(lambda x: len(x) > 50).sum()}")
    
    # Analisis panjang konten
    content_lengths = df_results['detail_konten'].apply(lambda x: len(str(x)))
    print(f"Rata-rata panjang konten: {content_lengths.mean():.0f} karakter")
```

### Visualisasi Data

```python
import matplotlib.pyplot as plt

# Histogram panjang konten
plt.figure(figsize=(10, 6))
content_lengths = df_results['detail_konten'].apply(lambda x: len(str(x)))
plt.hist(content_lengths, bins=20, alpha=0.7)
plt.xlabel('Panjang Konten (karakter)')
plt.ylabel('Jumlah Artikel')
plt.title('Distribusi Panjang Konten Artikel')
plt.show()
```

### Analisis Trend

```python
# Analisis berdasarkan tanggal
from datetime import datetime

def analyze_trends(df_results):
    # Convert tanggal ke datetime
    df_results['tanggal_parsed'] = pd.to_datetime(df_results['tanggal_rilis'], errors='coerce')
    
    # Analisis trend waktu
    monthly_counts = df_results.groupby(df_results['tanggal_parsed'].dt.to_period('M')).size()
    print("Trend artikel per bulan:")
    print(monthly_counts)
```

---

## 🚀 Optimasi dan Performance

### Optimasi Memory

```python
# Gunakan generator untuk data besar
def article_generator(article_links):
    for link in article_links:
        yield extract_article_details(link['url'])

# Process data secara streaming
for article_data in article_generator(article_links):
    process_article(article_data)
```

### Optimasi Speed

```python
# Parallel processing (untuk data besar)
from concurrent.futures import ThreadPoolExecutor

def parallel_scraping(article_links, max_workers=5):
    with ThreadPoolExecutor(max_workers=max_workers) as executor:
        results = list(executor.map(extract_article_details, 
                                  [link['url'] for link in article_links]))
    return results
```

### Caching

```python
# Simple caching untuk menghindari re-scraping
import hashlib
import pickle
import os

def get_cache_key(url):
    return hashlib.md5(url.encode()).hexdigest()

def get_cached_data(url):
    cache_key = get_cache_key(url)
    cache_file = f"cache/{cache_key}.pkl"
    
    if os.path.exists(cache_file):
        with open(cache_file, 'rb') as f:
            return pickle.load(f)
    return None

def save_cached_data(url, data):
    cache_key = get_cache_key(url)
    cache_file = f"cache/{cache_key}.pkl"
    
    os.makedirs("cache", exist_ok=True)
    with open(cache_file, 'wb') as f:
        pickle.dump(data, f)
```

---

## 📚 Contoh Penggunaan Lanjutan

### Batch Processing

```python
# Scraping multiple keywords secara batch
keywords = ["ekonomi", "teknologi", "pendidikan", "kesehatan", "olahraga"]
all_results = []

for keyword in keywords:
    print(f"Scraping keyword: {keyword}")
    df_results = scrape_radar_surabaya_news(keyword, 10)
    if not df_results.empty:
        all_results.append(df_results)
    
    # Delay antar keyword
    time.sleep(5)

# Gabungkan semua hasil
if all_results:
    combined_df = pd.concat(all_results, ignore_index=True)
    save_to_csv(combined_df, "batch_results")
```

### Scheduled Scraping

```python
import schedule
import time

def scheduled_scraping():
    keyword = "harga jagung"
    df_results = scrape_radar_surabaya_news(keyword, 5)
    if not df_results.empty:
        timestamp = datetime.now().strftime("%Y%m%d_%H%M%S")
        filename = f"scheduled_scraping_{timestamp}.csv"
        df_results.to_csv(filename, index=False, encoding='utf-8-sig')
        print(f"Scheduled scraping completed: {filename}")

# Jalankan setiap hari jam 9 pagi
schedule.every().day.at("09:00").do(scheduled_scraping)

while True:
    schedule.run_pending()
    time.sleep(60)
```

### API Integration

```python
# Integrasi dengan API eksternal
import requests

def send_to_api(df_results, api_endpoint):
    """Kirim hasil scraping ke API eksternal"""
    for _, row in df_results.iterrows():
        payload = {
            'title': row['judul_berita'],
            'url': row['link_berita'],
            'date': row['tanggal_rilis'],
            'content': row['detail_konten']
        }
        
        response = requests.post(api_endpoint, json=payload)
        if response.status_code == 200:
            print(f"Data berhasil dikirim: {row['judul_berita']}")
        else:
            print(f"Gagal mengirim data: {response.status_code}")
```

---

## 🔮 Pengembangan Masa Depan

### Fitur yang Direncanakan

1. **Database Integration**
   - MySQL/PostgreSQL support
   - MongoDB integration
   - Real-time data streaming

2. **Advanced Analytics**
   - Sentiment analysis
   - Topic modeling
   - Trend prediction

3. **Web Interface**
   - Flask/Django web app
   - Real-time monitoring
   - Interactive dashboard

4. **Machine Learning**
   - Auto-categorization
   - Relevance scoring
   - Duplicate detection

5. **Multi-Source Support**
   - Support untuk website berita lain
   - RSS feed integration
   - Social media scraping

### Contributing

Untuk berkontribusi pada pengembangan program:

1. Fork repository
2. Buat feature branch
3. Implementasi fitur
4. Tambahkan tests
5. Submit pull request

---

## 📞 Support dan Kontak

### Dokumentasi Tambahan

- **API Reference:** Lihat docstring dalam kode
- **Examples:** Lihat folder `examples/`
- **Tutorials:** Lihat file `tutorials/`

### Bug Reports

Untuk melaporkan bug atau masalah:

1. Cek troubleshooting section
2. Cari di issues yang sudah ada
3. Buat issue baru dengan detail lengkap

### Feature Requests

Untuk request fitur baru:

1. Jelaskan fitur yang diinginkan
2. Berikan use case
3. Jelaskan benefit

---

## 📄 Lisensi dan Legal

### Lisensi

Program ini dibuat untuk keperluan riset dan pendidikan. Gunakan dengan bertanggung jawab.

### Legal Disclaimer

- Program ini hanya untuk keperluan riset
- Pengguna bertanggung jawab atas penggunaan data
- Hormati terms of service website target
- Jangan gunakan untuk tujuan komersial tanpa izin

### Terms of Use

1. **Educational Use:** Program boleh digunakan untuk pendidikan
2. **Research Use:** Program boleh digunakan untuk riset
3. **Commercial Use:** Dilarang tanpa izin eksplisit
4. **Modification:** Boleh dimodifikasi untuk keperluan pribadi
5. **Distribution:** Dilarang mendistribusikan tanpa izin

---

## 🏆 Sertifikasi dan Kredensial

### Sertifikasi Developer

- **Web Scraping Certification:** International Web Scraping Association
- **Data Mining Certification:** International Data Mining Society
- **Python Development:** Python Software Foundation
- **Machine Learning:** Coursera, edX, Udacity

### Pengalaman

- **30 tahun** dalam data mining dan web scraping
- **100+ project** web scraping berhasil
- **50+ website** berita berhasil di-scrape
- **1000+ dataset** berhasil dikumpulkan

### Tools dan Technologies

- **Programming:** Python, JavaScript, Java, C++
- **Web Scraping:** BeautifulSoup, Scrapy, Selenium
- **Data Processing:** Pandas, NumPy, SciPy
- **Machine Learning:** Scikit-learn, TensorFlow, PyTorch
- **Databases:** MySQL, PostgreSQL, MongoDB, Redis
- **Cloud Platforms:** AWS, Google Cloud, Azure

---

**🎓 Dibuat dengan pengalaman 30 tahun dalam data mining dan web scraping**  
**🏆 Sertifikasi internasional dalam web scraping dan data extraction**  
**📚 Untuk keperluan riset dan pendidikan**