# Radar Surabaya Web Scraper

## Deskripsi
Aplikasi web scraping profesional untuk website Radar Surabaya (https://radarsurabaya.jawapos.com/) yang dikembangkan oleh Data Mining Expert & Full Stack Engineer dengan pengalaman 30 tahun dan sertifikasi internasional web scraping.

## Fitur Utama
- ✅ Pencarian artikel berdasarkan kata kunci
- ✅ Ekstraksi judul berita dan tanggal publikasi
- ✅ Ekstraksi konten detail artikel (tanpa iklan)
- ✅ Export data ke format CSV dan JSON
- ✅ Logging lengkap untuk monitoring
- ✅ Error handling yang robust
- ✅ Rate limiting untuk menghormati server
- ✅ User-Agent yang profesional

## Instalasi

### 1. Clone Repository
```bash
git clone <repository-url>
cd radar-scraper
```

### 2. Install Dependencies
```bash
pip install -r requirements.txt
```

### 3. Jalankan Aplikasi
```bash
python radar_scraper.py
```

## Cara Penggunaan

### 1. Menjalankan Scraper
```bash
python radar_scraper.py
```

### 2. Input Kata Kunci
Ketika aplikasi berjalan, Anda akan diminta memasukkan kata kunci pencarian:
```
Masukkan kata kunci pencarian (contoh: harga jagung): 
```

### 3. Proses Scraping
Aplikasi akan:
1. Mengunjungi https://radarsurabaya.jawapos.com/
2. Melakukan pencarian dengan kata kunci yang dimasukkan
3. Mengekstrak link artikel dari hasil pencarian
4. Mengakses setiap artikel untuk mengambil konten detail
5. Menyimpan data dalam format CSV dan JSON

### 4. Output Files
Setelah selesai, aplikasi akan menghasilkan:
- `radar_surabaya_articles_YYYYMMDD_HHMMSS.csv` - Data dalam format CSV
- `radar_surabaya_articles_YYYYMMDD_HHMMSS.json` - Data dalam format JSON
- `radar_scraper.log` - Log file untuk monitoring

## Struktur Data Output

### CSV/JSON Format
```json
{
  "title": "Judul Artikel",
  "date": "Tanggal Publikasi",
  "content": "Konten lengkap artikel (tanpa iklan)",
  "url": "URL artikel",
  "scraped_at": "Timestamp scraping"
}
```

## Selector XPath yang Digunakan

### 1. Form Pencarian
```
/html/body/header/div[2]/div/div[3]/form
```

### 2. Link Artikel
```
/html/body/div[3]/div/div/div[2]/section/div[3]/div[1]/div[2]/h2/a
```

### 3. Tanggal Artikel
```
/html/body/div[3]/div/div/div[2]/section/div[3]/div[1]/div[2]/date
```

### 4. Konten Artikel
```
/html/body/div[3]/div/div/div[2]/div/div[1]/article
```

## Fitur Keamanan & Etika

### 1. Rate Limiting
- Delay 2 detik antara setiap request artikel
- Menghormati server target

### 2. User-Agent Professional
- Menggunakan User-Agent browser yang valid
- Menghindari deteksi sebagai bot

### 3. Error Handling
- Timeout handling (30 detik)
- Graceful error recovery
- Logging lengkap untuk debugging

### 4. Content Filtering
- Menghilangkan iklan dan elemen non-konten
- Fokus hanya pada konten artikel

## Troubleshooting

### 1. Jika Tidak Ada Artikel Ditemukan
- Coba kata kunci yang berbeda
- Periksa koneksi internet
- Cek log file untuk error details

### 2. Jika Ada Error Network
- Periksa koneksi internet
- Coba jalankan ulang aplikasi
- Periksa apakah website target bisa diakses

### 3. Jika Ada Error Parsing
- Website mungkin telah berubah struktur
- Periksa log file untuk detail error
- Update selector jika diperlukan

## Logging

Aplikasi menggunakan logging yang komprehensif:
- File log: `radar_scraper.log`
- Console output untuk monitoring real-time
- Level: INFO, WARNING, ERROR

## Dependencies

- `requests` - HTTP requests
- `beautifulsoup4` - HTML parsing
- `pandas` - Data manipulation dan export
- `lxml` - XML/HTML parser
- `urllib3` - HTTP client

## Lisensi

Aplikasi ini dikembangkan untuk tujuan edukasi dan penelitian. Pastikan untuk mematuhi Terms of Service website target.

## Kontak

Dikembangkan oleh Data Mining Expert & Full Stack Engineer dengan pengalaman 30 tahun dan sertifikasi internasional web scraping.

---

**Catatan**: Aplikasi ini dirancang dengan standar profesional dan mengikuti best practices dalam web scraping. Gunakan dengan bertanggung jawab dan hormati Terms of Service website target.

