# Troubleshooting Guide - Radar Surabaya Web Scraper

## Masalah Umum dan Solusi

### 1. Error "Module not found"

**Gejala:**
```
ModuleNotFoundError: No module named 'requests'
ModuleNotFoundError: No module named 'bs4'
```

**Solusi:**
```python
# Jalankan ini di cell pertama
!pip install requests beautifulsoup4 pandas lxml urllib3
```

### 2. Error "Connection timeout"

**Gejala:**
```
requests.exceptions.ConnectTimeout: HTTPSConnectionPool
```

**Solusi:**
- Periksa koneksi internet
- Coba jalankan ulang setelah beberapa menit
- Gunakan versi yang lebih sederhana (`radar_surabaya_ultra_simple.py`)

### 3. Tidak ada artikel yang ditemukan

**Gejala:**
```
Tidak ada artikel yang ditemukan.
```

**Solusi:**
- Coba kata kunci yang berbeda (contoh: "ekonomi", "politik", "pendidikan")
- Periksa apakah website masih accessible
- Gunakan kata kunci yang lebih umum

### 4. Error "Could not find article content"

**Gejala:**
```
Tidak dapat menemukan konten untuk: [judul artikel]
```

**Solusi:**
- Website mungkin mengubah struktur HTML
- Gunakan versi yang lebih robust (`radar_surabaya_fixed.py`)
- Coba artikel yang berbeda

### 5. File tidak terdownload otomatis

**Gejala:**
File tidak otomatis terdownload di Google Colab

**Solusi:**
- File tersimpan di Google Colab environment
- Gunakan menu "Files" di sidebar untuk download manual
- Atau gunakan versi dengan `files.download()`:

```python
from google.colab import files
files.download('filename.json')
files.download('filename.csv')
```

### 6. Error "Permission denied"

**Gejala:**
```
PermissionError: [Errno 13] Permission denied
```

**Solusi:**
- Pastikan tidak ada file yang sedang dibuka di aplikasi lain
- Coba nama file yang berbeda
- Restart runtime Google Colab

### 7. Error "SSL Certificate"

**Gejala:**
```
SSLError: [SSL: CERTIFICATE_VERIFY_FAILED]
```

**Solusi:**
```python
import ssl
ssl._create_default_https_context = ssl._create_unverified_context
```

### 8. Error "Memory limit exceeded"

**Gejala:**
```
MemoryError atau RuntimeError
```

**Solusi:**
- Kurangi `MAX_ARTICLES` (misal dari 10 ke 5)
- Kurangi `MAX_PAGES` (misal dari 3 ke 1)
- Restart runtime Google Colab

## Versi yang Direkomendasikan

### Untuk Pemula (Paling Mudah):
Gunakan `radar_surabaya_ultra_simple.py`
- Minimal dependencies
- Error handling yang baik
- Mudah dipahami

### Untuk Intermediate:
Gunakan `radar_surabaya_fixed.py`
- Lebih robust
- Multiple selectors
- Debug information

### Untuk Advanced:
Gunakan `radar_surabaya_colab.py`
- Fitur lengkap
- Class-based structure
- Download otomatis

## Langkah-langkah Debug

### 1. Test Koneksi
```python
import requests
response = requests.get("https://radarsurabaya.jawapos.com", timeout=10)
print(f"Status: {response.status_code}")
print(f"Title: {BeautifulSoup(response.content, 'html.parser').find('title').get_text()}")
```

### 2. Test Search URL
```python
from urllib.parse import quote_plus
query = "harga jagung"
encoded = quote_plus(query)
url = f"https://radarsurabaya.jawapos.com/search?q={encoded}"
print(f"Search URL: {url}")

response = requests.get(url)
soup = BeautifulSoup(response.content, 'html.parser')
links = soup.find_all('a', href=True)
print(f"Found {len(links)} links")
```

### 3. Test Article Content
```python
# Test dengan URL artikel langsung
article_url = "https://radarsurabaya.jawapos.com/news/2024/01/01/example-article"
response = requests.get(article_url)
soup = BeautifulSoup(response.content, 'html.parser')

# Try different selectors
selectors = ['article', '.content', '.post-content', 'div[class*="content"]']
for selector in selectors:
    content = soup.select_one(selector)
    if content:
        print(f"Found content with selector: {selector}")
        print(f"Length: {len(content.get_text())}")
        break
```

## Tips Penggunaan

### 1. Mulai dengan Jumlah Kecil
```python
MAX_ARTICLES = 3  # Mulai dengan 3 artikel
MAX_PAGES = 1     # Mulai dengan 1 halaman
```

### 2. Gunakan Kata Kunci yang Spesifik
```python
# Baik
SEARCH_QUERY = "harga jagung"

# Kurang baik
SEARCH_QUERY = "jagung"
```

### 3. Monitor Progress
- Perhatikan output di console
- Jika ada error, catat pesan errornya
- Coba jalankan ulang jika perlu

### 4. Backup Data
- Simpan hasil dalam format JSON dan CSV
- Download file segera setelah scraping selesai
- Backup di Google Drive jika perlu

## Contoh Penggunaan yang Berhasil

### Contoh 1: Scraping Berhasil
```
Memulai scraping untuk: 'harga jagung'
==================================================
1. Mencari artikel...
   Ditemukan 15 link artikel

2. Mengambil konten artikel...
   Processing 1/5: Harga Jagung Naik di Pasaran...
   ✓ Berhasil (1250 karakter)
   Processing 2/5: Analisis Harga Jagung...
   ✓ Berhasil (980 karakter)
   ...

HASIL SCRAPING
============================================================
Total artikel: 5

Contoh artikel:
1. Harga Jagung Naik di Pasaran
   URL: https://radarsurabaya.jawapos.com/news/...
   Konten: 1250 karakter
   Preview: Harga jagung di pasaran mengalami kenaikan...

Scraping selesai!
File JSON: radar_harga_jagung_20241201_143022.json
File CSV: radar_harga_jagung_20241201_143022.csv
```

### Contoh 2: Tidak Ada Hasil
```
Memulai scraping untuk: 'kata_kunci_tidak_ada'
==================================================
1. Mencari artikel...
   Ditemukan 0 link artikel

Tidak ada artikel yang ditemukan.
Coba kata kunci yang berbeda atau periksa koneksi internet.
```

## Kontak Support

Jika masih mengalami masalah:

1. **Catat error message** dengan lengkap
2. **Screenshot** output yang muncul
3. **Coba versi yang berbeda** dari scraper
4. **Test dengan kata kunci yang berbeda**
5. **Periksa koneksi internet**

## Disclaimer

- Scraper ini dibuat untuk tujuan penelitian dan pembelajaran
- Website mungkin berubah struktur HTMLnya sewaktu-waktu
- Gunakan dengan bijak dan hormati Terms of Service website
- Jangan melakukan scraping berlebihan yang dapat membebani server