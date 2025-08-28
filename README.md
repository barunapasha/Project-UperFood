# Radar Surabaya Web Scraper

Web scraper profesional untuk website Radar Surabaya (https://radarsurabaya.jawapos.com/) dengan fitur pencarian dan ekstraksi konten artikel lengkap.

## Fitur Utama

- 🔍 **Pencarian Artikel**: Mencari artikel berdasarkan kata kunci
- 📰 **Ekstraksi Konten**: Mengambil judul, tanggal, dan konten lengkap artikel
- 🚫 **Filter Iklan**: Otomatis menghilangkan iklan dan elemen yang tidak diinginkan
- 💾 **Export Data**: Menyimpan hasil dalam format JSON dan CSV
- ⏱️ **Rate Limiting**: Menghormati server dengan delay antar request
- 📊 **Logging**: Sistem logging yang detail untuk monitoring

## Instalasi

### 1. Install Dependencies

```bash
pip install -r requirements.txt
```

### 2. Dependencies yang Diperlukan

- `requests`: Untuk HTTP requests
- `beautifulsoup4`: Untuk parsing HTML
- `pandas`: Untuk export data ke CSV
- `lxml`: Parser HTML yang cepat
- `urllib3`: HTTP client library

## Cara Penggunaan

### 1. Menjalankan Scraper

```bash
python radar_surabaya_scraper.py
```

### 2. Input yang Diminta

1. **Kata Kunci Pencarian**: Masukkan kata kunci yang ingin dicari (contoh: "harga jagung")
2. **Jumlah Halaman**: Maksimal halaman yang akan di-scrape (default: 3)
3. **Jumlah Artikel**: Maksimal artikel yang akan diproses (default: 10)

### 3. Contoh Penggunaan

```python
# Import scraper
from radar_surabaya_scraper import RadarSurabayaScraper

# Inisialisasi scraper
scraper = RadarSurabayaScraper()

# Cari artikel dengan kata kunci
articles = scraper.scrape_with_full_content("harga jagung", max_pages=3, max_articles=10)

# Simpan hasil
scraper.save_to_json(articles)
scraper.save_to_csv(articles)
```

## Output Data

Setiap artikel yang di-scrape akan menghasilkan data dengan struktur:

```json
{
  "title": "Judul Artikel",
  "url": "https://radarsurabaya.jawapos.com/...",
  "date": "Tanggal Publikasi",
  "summary": "Ringkasan Artikel",
  "search_query": "Kata Kunci Pencarian",
  "content": "Konten Lengkap Artikel",
  "images": ["URL Gambar 1", "URL Gambar 2"],
  "author": "Nama Penulis",
  "publish_date": "Tanggal Publikasi Detail"
}
```

## File Output

Scraper akan menghasilkan 2 file:

1. **JSON File**: `radar_surabaya_scraped_YYYYMMDD_HHMMSS.json`
2. **CSV File**: `radar_surabaya_scraped_YYYYMMDD_HHMMSS.csv`

## Selector CSS yang Digunakan

### 1. Search Form
```css
body > header > div.header__middle > div > div.flex.justify-end > form
```

### 2. Article Title & Link
```css
body > div:nth-child(6) > div > div > div.col-bs10-7 > section > div.latest__wrap > div:nth-child(1) > div.latest__right > h2 > a
```

### 3. Article Date
```css
body > div:nth-child(6) > div > div > div.col-bs10-7 > section > div.latest__wrap > div:nth-child(1) > div.latest__right > date
```

### 4. Article Content
```css
body > div:nth-child(8) > div > div > div.col-bs10-7 > div > div.col-bs10-7.col-offset-0 > article
```

## Fitur Keamanan

- **User-Agent Rotation**: Menggunakan browser headers yang realistis
- **Rate Limiting**: Delay 2-3 detik antar request
- **Error Handling**: Penanganan error yang robust
- **Timeout**: Request timeout 30 detik
- **Session Management**: Menggunakan session untuk efisiensi

## Penggunaan di Google Colab

1. Upload file `radar_surabaya_scraper.py` ke Google Colab
2. Install dependencies:
   ```python
   !pip install requests beautifulsoup4 pandas lxml urllib3
   ```
3. Jalankan scraper:
   ```python
   !python radar_surabaya_scraper.py
   ```

## Troubleshooting

### Error "Connection timeout"
- Periksa koneksi internet
- Coba jalankan ulang setelah beberapa menit

### Error "No articles found"
- Periksa kata kunci pencarian
- Coba kata kunci yang berbeda
- Periksa apakah website masih accessible

### Error "Could not find article content"
- Website mungkin mengubah struktur HTML
- Scraper akan mencoba selector alternatif secara otomatis

## Disclaimer

- Scraper ini dibuat untuk tujuan penelitian dan pembelajaran
- Gunakan dengan bijak dan hormati Terms of Service website
- Jangan melakukan scraping berlebihan yang dapat membebani server
- Pastikan penggunaan sesuai dengan hukum yang berlaku

## Author

Data Mining Expert & Full Stack Engineer (30 years experience)
Specialized in web scraping with international certifications

## License

Educational use only. Please respect website terms of service.

