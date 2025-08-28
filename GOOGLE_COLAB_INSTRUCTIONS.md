# Cara Menggunakan Radar Surabaya Scraper di Google Colab

## Langkah 1: Install Dependencies
Jalankan cell pertama untuk menginstall package yang diperlukan:

```python
!pip install requests beautifulsoup4 pandas lxml urllib3
```

## Langkah 2: Copy-Paste Kode Scraper
Copy seluruh kode dari file `radar_surabaya_simple_colab.py` ke cell baru di Google Colab.

## Langkah 3: Jalankan Scraper
Setelah kode di-paste, jalankan cell tersebut. Program akan meminta input:

1. **Kata Kunci Pencarian**: Masukkan kata kunci yang ingin dicari (contoh: "harga jagung")
2. **Jumlah Halaman**: Maksimal halaman yang akan di-scrape (default: 3)
3. **Jumlah Artikel**: Maksimal artikel yang akan diproses (default: 10)

## Langkah 4: Tunggu Proses Selesai
Scraper akan:
- Mencari artikel berdasarkan kata kunci
- Mengambil konten lengkap setiap artikel
- Menyimpan hasil dalam format JSON dan CSV
- Menampilkan ringkasan hasil

## Contoh Penggunaan Cepat
Jika ingin langsung menjalankan tanpa input interaktif, gunakan:

```python
# Langsung jalankan dengan parameter
articles = scrape_radar_surabaya("harga jagung", max_pages=3, max_articles=10)
save_results(articles, "harga jagung")
```

## Output yang Dihasilkan
1. **File JSON**: `radar_surabaya_harga_jagung_20241201_143022.json`
2. **File CSV**: `radar_surabaya_harga_jagung_20241201_143022.csv`

## Struktur Data Output
Setiap artikel akan berisi:
- `title`: Judul artikel
- `url`: Link artikel
- `date`: Tanggal publikasi
- `summary`: Ringkasan artikel
- `content`: Konten lengkap artikel
- `images`: List URL gambar
- `author`: Penulis artikel
- `publish_date`: Tanggal publikasi detail
- `search_query`: Kata kunci pencarian

## Troubleshooting

### Error "Module not found"
Pastikan sudah menjalankan:
```python
!pip install requests beautifulsoup4 pandas lxml urllib3
```

### Error "Connection timeout"
- Coba jalankan ulang setelah beberapa menit
- Periksa koneksi internet

### Tidak ada artikel ditemukan
- Coba kata kunci yang berbeda
- Periksa apakah website masih accessible

### File tidak terdownload otomatis
- File akan tersimpan di Google Colab
- Gunakan menu "Files" di sidebar untuk download manual

## Tips Penggunaan
1. **Mulai dengan jumlah kecil**: Gunakan max_articles=5 untuk testing
2. **Gunakan kata kunci spesifik**: "harga jagung" lebih baik dari "jagung"
3. **Monitor progress**: Program akan menampilkan progress scraping
4. **Respect rate limiting**: Program sudah diatur untuk tidak membebani server

## Contoh Kata Kunci yang Bisa Dicoba
- "harga jagung"
- "inflasi"
- "ekonomi"
- "politik"
- "pendidikan"
- "kesehatan"
- "teknologi"
- "olahraga"

## Disclaimer
- Scraper ini dibuat untuk tujuan penelitian dan pembelajaran
- Gunakan dengan bijak dan hormati Terms of Service website
- Jangan melakukan scraping berlebihan yang dapat membebani server