# 🎓 Program Scraping Berita Radar Surabaya

Program web scraping untuk mengumpulkan data berita dari website [Radar Surabaya](https://radarsurabaya.jawapos.com/) dengan fitur pencarian berdasarkan keyword.

## 👨‍🏫 Tentang Developer

- **Dibuat oleh:** Dosen Data Mining & Full Stack Engineer
- **Pengalaman:** 30 tahun dalam data mining dan web scraping
- **Sertifikasi:** Internasional dalam web scraping dan data extraction
- **Tujuan:** Untuk keperluan riset dan analisis data berita

## 🚀 Fitur Utama

- ✅ **Pencarian Berita:** Mencari berita berdasarkan keyword yang diinput user
- ✅ **Ekstraksi Data Lengkap:** Mengambil judul, tanggal, link, dan konten berita
- ✅ **Validasi Relevansi:** Memastikan artikel yang diambil relevan dengan keyword
- ✅ **Pembersihan Data:** Membersihkan dan memperbaiki data sebelum disimpan
- ✅ **Export CSV:** Menyimpan hasil dalam format CSV yang kompatibel dengan Excel
- ✅ **Penanganan Error:** Robust error handling untuk berbagai kondisi
- ✅ **Rate Limiting:** Delay otomatis untuk menghindari blocking

## 📋 Persyaratan Sistem

- Python 3.7 atau lebih baru
- Koneksi internet yang stabil
- Google Colab (direkomendasikan) atau environment Python lokal

## 🔧 Instalasi

### Untuk Google Colab:

1. Upload file `scraping_radar_surabaya.py` ke Google Colab
2. Install dependencies:
```python
!pip install requests beautifulsoup4 pandas lxml urllib3
```

### Untuk Environment Lokal:

1. Clone atau download repository ini
2. Install dependencies:
```bash
pip install -r requirements.txt
```

## 🎯 Cara Penggunaan

### 1. Menjalankan Program

```python
# Jalankan program
python scraping_radar_surabaya.py
```

### 2. Input yang Diperlukan

Program akan meminta input:
- **Keyword pencarian:** Kata kunci berita yang ingin dicari (contoh: "harga jagung")
- **Jumlah artikel:** Maksimal artikel yang akan diambil (default: 5, maksimal: 500)

### 3. Output

Program akan menghasilkan:
- **File CSV:** `berita_radar_surabaya_[keyword]_[timestamp].csv`
- **Preview data:** Tampilan preview hasil scraping di console
- **Statistik:** Informasi jumlah artikel yang berhasil diambil

## 📊 Struktur Data Output

File CSV akan berisi kolom:
- `judul_berita`: Judul artikel berita
- `link_berita`: URL lengkap artikel
- `tanggal_rilis`: Tanggal publikasi artikel
- `detail_konten`: Isi lengkap artikel (tanpa iklan)

## 🔍 Cara Kerja Program

### 1. Pencarian Berita
- Mengakses URL: `https://radarsurabaya.jawapos.com/search?q=[keyword]`
- Menggunakan selector XPath yang telah dioptimasi
- Mendukung pagination untuk mengambil lebih banyak artikel

### 2. Ekstraksi Data
- **Judul:** Menggunakan multiple selector untuk memastikan akurasi
- **Tanggal:** Mencari dalam berbagai format tanggal Indonesia
- **Konten:** Mengambil konten artikel dengan filter iklan dan elemen tidak diinginkan

### 3. Validasi dan Pembersihan
- Memvalidasi relevansi artikel berdasarkan keyword
- Membersihkan data dari karakter tidak diinginkan
- Memperbaiki struktur data yang salah

## 🛡️ Fitur Keamanan

- **User-Agent Rotation:** Menggunakan browser headers yang realistis
- **Rate Limiting:** Delay otomatis antara request
- **Error Handling:** Penanganan error yang robust
- **SSL Verification:** Disabled untuk kompatibilitas Colab

## 📝 Contoh Penggunaan

```python
# Contoh input
Keyword: harga jagung
Jumlah artikel: 10

# Output yang diharapkan
✅ Berhasil mengumpulkan 15 link artikel kandidat
✅ Artikel relevan ditemukan (10/10)
💾 Data disimpan ke: berita_radar_surabaya_harga_jagung_20241201_143022.csv
```

## ⚠️ Penting untuk Diperhatikan

1. **Etika Penggunaan:** Gunakan data dengan bijak dan sesuai etika riset
2. **Rate Limiting:** Program sudah dioptimasi untuk tidak membebani server
3. **Koneksi Internet:** Pastikan koneksi stabil untuk hasil optimal
4. **Keyword Spesifik:** Gunakan keyword yang spesifik untuk hasil yang lebih relevan

## 🔧 Troubleshooting

### Masalah Umum:

1. **"Tidak menemukan link artikel"**
   - Pastikan keyword yang dimasukkan relevan
   - Coba keyword yang lebih spesifik

2. **"Error saat mengakses URL"**
   - Periksa koneksi internet
   - Coba jalankan ulang program

3. **"Artikel tidak relevan"**
   - Gunakan keyword yang lebih spesifik
   - Program hanya mengambil artikel yang benar-benar mengandung keyword

## 📞 Support

Untuk pertanyaan atau masalah teknis, silakan hubungi developer.

## 📄 Lisensi

Program ini dibuat untuk keperluan riset dan pendidikan. Gunakan dengan bertanggung jawab.

---

**🎓 Dibuat dengan pengalaman 30 tahun dalam data mining dan web scraping**
**🏆 Sertifikasi internasional dalam web scraping dan data extraction**

