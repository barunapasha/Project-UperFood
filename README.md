
# Radar Surabaya News Scraper

Program web scraping untuk mengumpulkan data berita dari Radar Surabaya (radarsurabaya.jawapos.com) dengan fitur-fitur canggih dan error handling yang robust.

## 🚀 Fitur Utama

### Versi Original (`radar_surabaya_scraper.py`)
- ✅ Scraping berita berdasarkan keyword
- ✅ Ekstraksi judul, tanggal, dan konten artikel
- ✅ Validasi relevansi artikel
- ✅ Penyimpanan ke format CSV
- ✅ Error handling dasar
- ✅ Rate limiting untuk menghindari blocking

### Versi Improved (`radar_surabaya_scraper_improved.py`)
- 🆕 **Object-Oriented Design** dengan class-based architecture
- 🆕 **Multi-threading** untuk performa lebih cepat
- 🆕 **Advanced Error Handling** dengan logging system
- 🆕 **Relevance Scoring** untuk kualitas data yang lebih baik
- 🆕 **Configurable Settings** dengan dataclass configuration
- 🆕 **Retry Mechanism** dengan exponential backoff
- 🆕 **Random User-Agent Rotation** untuk menghindari detection
- 🆕 **Comprehensive Logging** ke file dan console
- 🆕 **Data Validation** dan cleaning yang lebih robust
- 🆕 **Performance Metrics** dan statistics

## 📋 Persyaratan Sistem

- Python 3.7+
- Koneksi internet yang stabil
- Library dependencies (lihat requirements.txt)

## 🛠️ Instalasi

1. **Clone atau download repository ini**

2. **Install dependencies:**
```bash
pip install -r requirements.txt
```

3. **Jalankan program:**
```bash
# Versi original
python radar_surabaya_scraper.py

# Versi improved (recommended)
python radar_surabaya_scraper_improved.py
```

## 📖 Cara Penggunaan

### 1. Input Keyword
Masukkan keyword pencarian berita yang ingin dicari:
```
🔍 Masukkan keyword pencarian berita: Surabaya
```

### 2. Jumlah Artikel
Tentukan jumlah maksimal artikel yang ingin diambil:
```
📊 Jumlah maksimal artikel (default 5, maks 500): 10
```

### 3. Proses Scraping
Program akan secara otomatis:
- Mencari artikel berdasarkan keyword
- Mengekstrak detail artikel (judul, tanggal, konten)
- Memvalidasi relevansi
- Menyimpan ke file CSV

### 4. Output
Hasil akan disimpan dalam file CSV dengan format:
```
berita_radar_surabaya_[keyword]_[timestamp].csv
```

## 📊 Format Output

### Versi Original
| Kolom | Deskripsi |
|-------|-----------|
| `judul_berita` | Judul artikel |
| `link_berita` | URL artikel |
| `tanggal_rilis` | Tanggal publikasi |
| `detail_konten` | Isi artikel |

### Versi Improved
| Kolom | Deskripsi |
|-------|-----------|
| `judul_berita` | Judul artikel |
| `link_berita` | URL artikel |
| `tanggal_rilis` | Tanggal publikasi |
| `detail_konten` | Isi artikel |
| `skor_relevansi` | Skor relevansi (0-1) |
| `jumlah_kata` | Jumlah kata dalam artikel |
| `error_ekstraksi` | Error yang terjadi saat ekstraksi |

## 🔧 Konfigurasi (Versi Improved)

Anda dapat mengubah konfigurasi scraping dengan memodifikasi `ScrapingConfig`:

```python
config = ScrapingConfig(
    max_articles_per_page=20,        # Artikel per halaman
    max_pages_to_check=30,           # Maksimal halaman yang dicek
    max_extra_articles_to_fetch=100, # Artikel tambahan untuk diproses
    request_timeout=30,              # Timeout request (detik)
    max_retries=3,                   # Jumlah retry jika gagal
    delay_between_requests=(1.0, 3.0), # Delay antar request (detik)
    max_concurrent_requests=3        # Jumlah request bersamaan
)
```

## 🚨 Fitur Keamanan

### Anti-Detection
- **Random User-Agent**: Rotasi User-Agent browser
- **Rate Limiting**: Delay random antar request
- **Session Management**: Menggunakan session untuk efisiensi
- **SSL Verification**: Disabled untuk menghindari error SSL

### Error Handling
- **Retry Mechanism**: Otomatis retry jika request gagal
- **Graceful Degradation**: Tetap berjalan meski ada error
- **Comprehensive Logging**: Log semua aktivitas ke file
- **Data Validation**: Validasi data sebelum disimpan

## 📈 Perbandingan Versi

| Fitur | Original | Improved |
|-------|----------|----------|
| Architecture | Procedural | Object-Oriented |
| Performance | Sequential | Multi-threaded |
| Error Handling | Basic | Advanced |
| Logging | Print statements | File + Console logging |
| Configuration | Hard-coded | Configurable |
| Data Quality | Basic filtering | Relevance scoring |
| User-Agent | Static | Random rotation |
| Retry Logic | Manual | Automatic |
| Metrics | Basic | Comprehensive |

## 🐛 Troubleshooting

### Error Umum

1. **Connection Error**
   ```
   ❌ Error saat mengakses URL: Connection timeout
   ```
   **Solusi**: Periksa koneksi internet dan coba lagi

2. **No Articles Found**
   ```
   ❌ Tidak menemukan link artikel
   ```
   **Solusi**: Coba keyword yang lebih spesifik atau periksa apakah website masih accessible

3. **SSL Certificate Error**
   ```
   ❌ SSL certificate verification failed
   ```
   **Solusi**: Program sudah mengatasi ini dengan `verify=False`

4. **Rate Limiting**
   ```
   ❌ 429 Too Many Requests
   ```
   **Solusi**: Program sudah memiliki rate limiting, tunggu beberapa menit dan coba lagi

### Tips Penggunaan

1. **Keyword yang Efektif**
   - Gunakan keyword spesifik: "Surabaya" bukan "berita"
   - Kombinasikan kata: "Surabaya banjir" bukan hanya "banjir"
   - Hindari keyword terlalu umum

2. **Jumlah Artikel**
   - Mulai dengan jumlah kecil (5-10) untuk testing
   - Tingkatkan secara bertahap jika diperlukan
   - Maksimal 500 artikel per session

3. **Waktu Eksekusi**
   - Program membutuhkan waktu tergantung jumlah artikel
   - Rata-rata 2-5 detik per artikel
   - Gunakan versi improved untuk performa lebih cepat

## 📝 Logging

### Versi Improved
Program akan membuat file log `scraper.log` dengan informasi detail:
- Request/response status
- Error messages
- Performance metrics
- Extraction results

### Contoh Log
```
2024-01-15 10:30:15 - INFO - Starting scraping for keyword: 'Surabaya'
2024-01-15 10:30:16 - INFO - Collecting links from page 1
2024-01-15 10:30:18 - INFO - Added 15 new links (total: 15)
2024-01-15 10:30:20 - INFO - Successfully extracted: Artikel tentang Surabaya...
```

## 🔒 Etika dan Legal

### Penggunaan yang Bertanggung Jawab
- ✅ Gunakan untuk tujuan penelitian dan analisis
- ✅ Hormati robots.txt website
- ✅ Jangan overload server dengan request berlebihan
- ✅ Berikan credit kepada sumber data

### Batasan
- ❌ Jangan gunakan untuk scraping komersial tanpa izin
- ❌ Jangan redistribute data tanpa permission
- ❌ Jangan bypass rate limiting yang disengaja

## 🤝 Kontribusi

Jika Anda ingin berkontribusi untuk meningkatkan program ini:

1. Fork repository
2. Buat feature branch
3. Commit perubahan
4. Push ke branch
5. Buat Pull Request

## 📞 Support

Untuk pertanyaan atau masalah:
- Buat issue di repository
- Periksa file log untuk debugging
- Pastikan semua dependencies terinstall

## 📄 License

Program ini dibuat untuk tujuan edukasi dan penelitian. Gunakan dengan bijak dan sesuai etika.

---

**Dibuat oleh: Dosen Data Mining & Full Stack Engineer**  
**Pengalaman: 30 tahun dalam data mining dan web scraping**  
**Sertifikasi: Internasional dalam web scraping dan data extraction**

