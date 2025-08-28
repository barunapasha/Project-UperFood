# Radar Surabaya Web Scraper - Complete Package

## Overview
Web scraper profesional untuk website Radar Surabaya (https://radarsurabaya.jawapos.com/) dengan fitur pencarian dan ekstraksi konten artikel lengkap. Dibuat oleh Data Mining Expert & Full Stack Engineer dengan pengalaman 30 tahun.

## Files Created

### 1. `radar_surabaya_scraper.py`
**Versi Lengkap dengan Class**
- Scraper dengan struktur class yang profesional
- Fitur lengkap dengan error handling
- Cocok untuk penggunaan di environment lokal
- Dokumentasi lengkap dalam kode

### 2. `radar_surabaya_colab.py`
**Versi Google Colab dengan Class**
- Optimized untuk Google Colab
- Menggunakan `google.colab.files` untuk download otomatis
- Struktur class yang sama dengan versi lengkap
- Input interaktif

### 3. `radar_surabaya_simple_colab.py`
**Versi Sederhana untuk Google Colab**
- Tanpa class, menggunakan fungsi sederhana
- Input interaktif
- Mudah dipahami dan dimodifikasi
- Cocok untuk pemula

### 4. `radar_surabaya_one_cell.py`
**Versi Satu Cell untuk Google Colab**
- Semua kode dalam satu cell
- Konfigurasi parameter di bagian atas
- Tidak ada input interaktif
- Paling mudah untuk copy-paste

### 5. `requirements.txt`
**Dependencies**
- Daftar package yang diperlukan
- Versi yang kompatibel
- Mudah untuk install

### 6. `README.md`
**Dokumentasi Lengkap**
- Instruksi instalasi dan penggunaan
- Penjelasan fitur
- Troubleshooting
- Contoh penggunaan

### 7. `GOOGLE_COLAB_INSTRUCTIONS.md`
**Instruksi Khusus Google Colab**
- Langkah-langkah detail untuk Google Colab
- Tips dan trik
- Troubleshooting khusus Colab

### 8. `SUMMARY.md`
**File ini - Ringkasan Lengkap**
- Overview semua file
- Panduan pemilihan file
- Rekomendasi penggunaan

## Cara Memilih File yang Tepat

### Untuk Google Colab (Rekomendasi):
1. **Pemula**: Gunakan `radar_surabaya_one_cell.py`
   - Copy-paste langsung ke cell
   - Ubah parameter di bagian atas
   - Jalankan sekali

2. **Intermediate**: Gunakan `radar_surabaya_simple_colab.py`
   - Input interaktif
   - Lebih fleksibel
   - Mudah dimodifikasi

3. **Advanced**: Gunakan `radar_surabaya_colab.py`
   - Struktur class profesional
   - Fitur lengkap
   - Download otomatis

### Untuk Environment Lokal:
- Gunakan `radar_surabaya_scraper.py`
- Install dependencies dengan `pip install -r requirements.txt`
- Jalankan dengan `python radar_surabaya_scraper.py`

## Fitur Utama

### 🔍 Pencarian Artikel
- Mencari berdasarkan kata kunci
- Support multiple pages
- URL encoding otomatis

### 📰 Ekstraksi Konten
- Judul artikel
- Tanggal publikasi
- Konten lengkap
- URL gambar
- Penulis artikel

### 🚫 Filter Konten
- Menghilangkan iklan
- Menghilangkan script
- Menghilangkan elemen yang tidak diinginkan

### 💾 Export Data
- Format JSON
- Format CSV
- Timestamp otomatis
- Download otomatis di Google Colab

### ⏱️ Rate Limiting
- Delay antar request
- Timeout handling
- Error recovery

## Selector CSS yang Digunakan

### Search Form
```css
body > header > div.header__middle > div > div.flex.justify-end > form
```

### Article Title & Link
```css
body > div:nth-child(6) > div > div > div.col-bs10-7 > section > div.latest__wrap > div:nth-child(1) > div.latest__right > h2 > a
```

### Article Date
```css
body > div:nth-child(6) > div > div > div.col-bs10-7 > section > div.latest__wrap > div:nth-child(1) > div.latest__right > date
```

### Article Content
```css
body > div:nth-child(8) > div > div > div.col-bs10-7 > div > div.col-bs10-7.col-offset-0 > article
```

## Quick Start untuk Google Colab

1. **Install Dependencies**:
   ```python
   !pip install requests beautifulsoup4 pandas lxml urllib3
   ```

2. **Copy Kode**:
   - Copy isi file `radar_surabaya_one_cell.py`
   - Paste ke cell baru di Google Colab

3. **Konfigurasi**:
   - Ubah `SEARCH_QUERY = "harga jagung"` sesuai kebutuhan
   - Ubah `MAX_PAGES` dan `MAX_ARTICLES` sesuai kebutuhan

4. **Jalankan**:
   - Run cell
   - Tunggu proses selesai
   - File akan otomatis terdownload

## Contoh Penggunaan

### Kata Kunci yang Bisa Dicoba:
- "harga jagung"
- "inflasi"
- "ekonomi"
- "politik"
- "pendidikan"
- "kesehatan"
- "teknologi"
- "olahraga"

### Output yang Dihasilkan:
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

## Keamanan dan Etika

- **Rate Limiting**: Delay 2-3 detik antar request
- **User-Agent**: Menggunakan browser headers yang realistis
- **Error Handling**: Penanganan error yang robust
- **Respectful Scraping**: Tidak membebani server

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