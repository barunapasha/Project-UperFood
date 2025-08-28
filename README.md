
# Radar Surabaya News Scraper

Program untuk mengumpulkan data berita dari Radar Surabaya berdasarkan keyword pencarian.

## Fitur

- 🔍 Pencarian berita berdasarkan keyword
- 📊 Ekstraksi data lengkap (judul, tanggal, konten, URL)
- 🛡️ Penanganan error yang robust dengan retry mechanism
- 📝 Logging lengkap untuk monitoring
- 💾 Export hasil ke CSV dengan encoding UTF-8
- 🧹 Pembersihan data otomatis
- ⚡ Optimasi performa dengan session management

## Instalasi

1. Clone atau download repository ini
2. Install dependencies:
```bash
pip install -r requirements.txt
```

## Penggunaan

### Cara 1: Menjalankan Program Interaktif
```bash
python radar_surabaya_scraper.py
```

Program akan meminta input:
- Keyword pencarian
- Jumlah maksimal artikel (1-500)

### Cara 2: Menggunakan sebagai Module
```python
from radar_surabaya_scraper import RadarSurabayaScraper

# Inisialisasi scraper
scraper = RadarSurabayaScraper()

# Scraping berita
keyword = "pemilu 2024"
max_articles = 10
results = scraper.scrape_news(keyword, max_articles)

# Simpan ke CSV
filename = scraper.save_to_csv(results, keyword)
print(f"Data disimpan ke: {filename}")
```

## Output

Program akan menghasilkan:
1. **File CSV** di folder `output/` dengan format:
   - `berita_radar_surabaya_{keyword}_{timestamp}.csv`

2. **File Log** untuk monitoring:
   - `scraper.log`

### Format Data CSV
| Kolom | Deskripsi |
|-------|-----------|
| `judul_berita` | Judul artikel |
| `link_berita` | URL artikel |
| `tanggal_rilis` | Tanggal publikasi |
| `detail_konten` | Isi artikel lengkap |

## Konfigurasi

Anda dapat mengubah konfigurasi di bagian atas file `radar_surabaya_scraper.py`:

```python
# Konfigurasi global
MAX_ARTICLES_PER_PAGE = 20
MAX_PAGES_TO_CHECK = 50
MAX_EXTRA_ARTICLES_TO_FETCH = 200
BASE_URL = "https://radarsurabaya.jawapos.com"
REQUEST_TIMEOUT = 30
MAX_RETRIES = 3
```

## Fitur Keamanan

- **Rate Limiting**: Delay otomatis antara request
- **User-Agent Rotation**: Menggunakan browser headers yang realistis
- **Error Handling**: Retry mechanism untuk request yang gagal
- **SSL Verification**: Disabled untuk menghindari error SSL di beberapa environment

## Troubleshooting

### Error "Connection timeout"
- Periksa koneksi internet
- Coba jalankan ulang program
- Periksa apakah website target dapat diakses

### Error "No articles found"
- Coba keyword yang lebih spesifik
- Periksa apakah keyword mengandung kata yang umum digunakan di berita

### Error "SSL Certificate"
- Program sudah dikonfigurasi untuk mengabaikan error SSL
- Jika masih error, pastikan urllib3 terinstall dengan benar

## Logging

Program menggunakan logging untuk monitoring:
- **INFO**: Informasi proses normal
- **WARNING**: Peringatan yang tidak menghentikan program
- **ERROR**: Error yang perlu diperhatikan

Log disimpan di file `scraper.log` dan juga ditampilkan di console.

## Etika Penggunaan

⚠️ **Peringatan Penting:**
- Gunakan data dengan bijak dan sesuai etika riset
- Hormati robots.txt website target
- Jangan melakukan scraping berlebihan yang dapat membebani server
- Gunakan data hanya untuk tujuan penelitian atau analisis yang sah

## Dependencies

- `requests`: HTTP library untuk mengambil konten web
- `beautifulsoup4`: HTML parsing
- `pandas`: Data manipulation dan export CSV
- `urllib3`: HTTP client library
- `lxml`: XML/HTML parser (backend untuk BeautifulSoup)

## Versi

- **v2.0**: Refactored dengan class-based architecture
- **v1.0**: Versi awal dengan fungsi-fungsi terpisah

## Kontribusi

Silakan berkontribusi dengan:
1. Melaporkan bug
2. Menambahkan fitur baru
3. Memperbaiki dokumentasi
4. Optimasi performa

## Lisensi

Program ini dibuat untuk tujuan pendidikan dan penelitian. Gunakan dengan bertanggung jawab.

