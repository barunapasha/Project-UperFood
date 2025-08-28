# 🎓 Program Scraping Berita Radar Surabaya

Program web scraping untuk mengumpulkan data berita dari website [Radar Surabaya](https://radarsurabaya.jawapos.com/) yang dikembangkan oleh Dosen Data Mining & Full Stack Engineer dengan pengalaman 30 tahun dalam web scraping dan data mining.

## 🏆 Fitur Utama

- **Scraping Otomatis**: Mengambil data berita berdasarkan keyword pencarian
- **Ekstraksi Lengkap**: Judul, tanggal rilis, link berita, dan detail konten
- **Validasi Relevansi**: Hanya mengambil artikel yang benar-benar relevan dengan keyword
- **Pembersihan Data**: Otomatis membersihkan dan memperbaiki data sebelum disimpan
- **Export CSV**: Menyimpan hasil dalam format CSV yang kompatibel dengan Excel
- **Penanganan Error**: Robust error handling untuk berbagai kondisi

## 📋 Persyaratan Sistem

- Python 3.7 atau lebih baru
- Koneksi internet yang stabil
- Google Colab (direkomendasikan) atau environment Python lokal

## 🚀 Instalasi

### Untuk Google Colab:

1. Upload file `scraping_radar_surabaya.py` ke Google Colab
2. Install dependencies:
```python
!pip install requests beautifulsoup4 pandas lxml urllib3
```
3. Jalankan program:
```python
!python scraping_radar_surabaya.py
```

### Untuk Environment Lokal:

1. Clone atau download repository ini
2. Install dependencies:
```bash
pip install -r requirements.txt
```
3. Jalankan program:
```bash
python scraping_radar_surabaya.py
```

## 📖 Cara Penggunaan

1. **Masukkan Keyword**: Ketik keyword yang ingin dicari (contoh: "harga jagung")
2. **Tentukan Jumlah Artikel**: Masukkan jumlah maksimal artikel yang ingin diambil (1-500)
3. **Tunggu Proses**: Program akan otomatis:
   - Mengunjungi halaman pencarian
   - Mengumpulkan link artikel
   - Mengekstrak detail setiap artikel
   - Memvalidasi relevansi
   - Menyimpan ke file CSV

## 🔧 Cara Kerja Program

### 1. Pencarian Artikel
- Mengunjungi: `https://radarsurabaya.jawapos.com/search?q={keyword}`
- Menggunakan selector: `/html/body/div[3]/div/div/div[2]/section/div[3]/div[1]/div[2]/h2/a`
- Mengumpulkan semua link artikel yang relevan

### 2. Ekstraksi Detail
- **Judul Berita**: Menggunakan berbagai selector untuk memastikan akurasi
- **Tanggal Rilis**: Selector `/html/body/div[3]/div/div/div[2]/section/div[3]/div[1]/div[2]/date`
- **Detail Konten**: Selector `/html/body/div[3]/div/div/div[2]/div/div[1]/article`
- **Filtering**: Otomatis menghapus iklan dan elemen tidak diinginkan

### 3. Validasi dan Pembersihan
- Memeriksa relevansi artikel dengan keyword
- Membersihkan teks dari karakter tidak diinginkan
- Memperbaiki struktur data sebelum export

## 📊 Output Data

Program menghasilkan file CSV dengan kolom:
- `judul_berita`: Judul lengkap artikel
- `link_berita`: URL lengkap artikel
- `tanggal_rilis`: Tanggal publikasi artikel
- `detail_konten`: Isi lengkap artikel (tanpa iklan)

## ⚙️ Konfigurasi

### Parameter yang Dapat Disesuaikan:

```python
MAX_ARTICLES_PER_PAGE = 20      # Artikel per halaman
MAX_PAGES_TO_CHECK = 50         # Maksimal halaman yang dicek
MAX_EXTRA_ARTICLES_TO_FETCH = 200  # Artikel tambahan untuk validasi
BASE_URL = "https://radarsurabaya.jawapos.com"
```

### Headers HTTP:
Program menggunakan headers yang menyerupai browser asli untuk menghindari blocking.

## 🛡️ Etika dan Legal

- **Respect Robots.txt**: Program menghormati aturan robots.txt website
- **Rate Limiting**: Delay otomatis antara request untuk tidak membebani server
- **Data Usage**: Data hanya untuk keperluan riset dan analisis
- **Attribution**: Selalu mencantumkan sumber data

## 🔍 Troubleshooting

### Masalah Umum:

1. **"Tidak menemukan artikel"**
   - Pastikan keyword spesifik dan relevan
   - Cek koneksi internet
   - Coba keyword lain

2. **"Error saat mengakses URL"**
   - Website mungkin sedang maintenance
   - Coba lagi beberapa saat kemudian
   - Periksa apakah URL masih valid

3. **"Artikel tidak relevan"**
   - Program hanya mengambil artikel yang benar-benar mengandung keyword
   - Ini normal untuk memastikan kualitas data

### Tips Penggunaan:

- Gunakan keyword yang spesifik (contoh: "harga jagung Surabaya" lebih baik dari "jagung")
- Untuk hasil maksimal, gunakan keyword dalam bahasa Indonesia
- Jumlah artikel 10-50 biasanya memberikan hasil optimal

## 📈 Contoh Penggunaan

```python
# Contoh keyword yang efektif:
- "harga jagung"
- "inflasi Surabaya"
- "pemilu 2024"
- "ekonomi Jawa Timur"
- "investasi Surabaya"
```

## 🤝 Kontribusi

Program ini dikembangkan untuk keperluan akademis dan riset. Untuk pertanyaan atau saran, silakan hubungi developer.

## 📄 Lisensi

Program ini dibuat untuk keperluan edukasi dan riset. Gunakan dengan bijak dan sesuai etika.

---

**Dibuat oleh: Dosen Data Mining & Full Stack Engineer**  
**Pengalaman: 30 tahun dalam web scraping dan data mining**  
**Sertifikasi: Internasional Web Scraping & Data Mining**  
**Tanggal: 2024**

