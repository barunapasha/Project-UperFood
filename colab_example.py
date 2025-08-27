# -*- coding: utf-8 -*-
"""
Contoh Penggunaan Program Scraping Radar Surabaya di Google Colab
Dibuat oleh: Dosen Data Mining & Full Stack Engineer
"""

# ============================================================================
# INSTALASI DEPENDENCIES
# ============================================================================
# Jalankan cell ini terlebih dahulu untuk install dependencies
# !pip install requests beautifulsoup4 pandas lxml urllib3

# ============================================================================
# IMPORT PROGRAM
# ============================================================================
# Upload file scraping_radar_surabaya.py ke Colab terlebih dahulu
# Kemudian import program
from scraping_radar_surabaya import *

print("✅ Program berhasil diimport!")

# ============================================================================
# CONTOH PENGGUNAAN DASAR
# ============================================================================
def contoh_penggunaan_dasar():
    """Contoh penggunaan program scraping"""
    
    # Set parameter
    keyword = "harga jagung"  # Ganti dengan keyword yang diinginkan
    max_articles = 5  # Jumlah artikel yang akan diambil
    
    print(f"🔍 Mencari berita dengan keyword: '{keyword}'")
    print(f"📊 Maksimal artikel: {max_articles}")
    print("=" * 50)
    
    # Jalankan scraping
    df_results = scrape_radar_surabaya_news(keyword, max_articles)
    
    # Tampilkan hasil
    if not df_results.empty:
        print("\n📊 HASIL SCRAPING:")
        print(df_results[['judul_berita', 'tanggal_rilis']].to_string(index=False, max_colwidth=50))
        
        # Simpan ke CSV
        filename = save_to_csv(df_results, keyword)
        print(f"\n💾 File disimpan: {filename}")
        
        return df_results
    else:
        print("\n❌ Tidak ada data yang berhasil diambil")
        return None

# ============================================================================
# ANALISIS DATA HASIL SCRAPING
# ============================================================================
def analisis_data(df_results):
    """Analisis data hasil scraping"""
    
    if df_results is None or df_results.empty:
        print("❌ Tidak ada data untuk dianalisis")
        return
    
    print("📊 ANALISIS DATA HASIL SCRAPING")
    print("=" * 50)
    
    # Statistik dasar
    print(f"Total artikel: {len(df_results)}")
    print(f"Artikel dengan tanggal: {df_results['tanggal_rilis'].apply(lambda x: x != 'Tanggal tidak ditemukan' and not x.startswith('Error')).sum()}")
    print(f"Artikel dengan konten: {df_results['detail_konten'].apply(lambda x: len(x) > 50 and not x.startswith('Error')).sum()}")
    
    # Analisis panjang konten
    content_lengths = df_results['detail_konten'].apply(lambda x: len(str(x)))
    print(f"\n📏 Analisis Panjang Konten:")
    print(f"Rata-rata panjang: {content_lengths.mean():.0f} karakter")
    print(f"Panjang terpendek: {content_lengths.min()} karakter")
    print(f"Panjang terpanjang: {content_lengths.max()} karakter")
    
    # Preview konten
    print(f"\n📄 PREVIEW KONTEN (3 artikel pertama):")
    for idx, row in df_results.head(3).iterrows():
        print(f"\n📋 Judul: {row['judul_berita']}")
        print(f"📅 Tanggal: {row['tanggal_rilis']}")
        konten_preview = str(row['detail_konten'])[:200] + ("..." if len(str(row['detail_konten'])) > 200 else "")
        print(f"📝 Konten: {konten_preview}")
        print("-" * 50)

# ============================================================================
# DOWNLOAD HASIL (UNTUK GOOGLE COLAB)
# ============================================================================
def download_hasil(df_results, keyword):
    """Download hasil scraping (untuk Google Colab)"""
    
    if df_results is None or df_results.empty:
        print("❌ Tidak ada data untuk didownload")
        return
    
    try:
        # Import Google Colab files module
        from google.colab import files
        
        # Simpan file CSV
        filename = save_to_csv(df_results, keyword)
        
        # Download file
        files.download(filename)
        print(f"✅ File {filename} berhasil didownload!")
        
    except ImportError:
        print("⚠️ Modul google.colab tidak tersedia. File disimpan di local directory.")
        filename = save_to_csv(df_results, keyword)
        print(f"💾 File disimpan: {filename}")

# ============================================================================
# CONTOH PENGGUNAAN BERBAGAI KEYWORD
# ============================================================================
def contoh_berbagai_keyword():
    """Contoh penggunaan dengan berbagai keyword"""
    
    keywords = [
        "harga jagung",
        "pemilu 2024",
        "ekonomi Indonesia",
        "teknologi",
        "pendidikan"
    ]
    
    for keyword in keywords:
        print(f"\n{'='*60}")
        print(f"🔍 Mencari: {keyword}")
        print(f"{'='*60}")
        
        # Scraping dengan jumlah artikel kecil untuk testing
        df_results = scrape_radar_surabaya_news(keyword, 3)
        
        if not df_results.empty:
            print(f"✅ Ditemukan {len(df_results)} artikel untuk keyword '{keyword}'")
            # Simpan hasil
            filename = save_to_csv(df_results, keyword)
            print(f"💾 Disimpan: {filename}")
        else:
            print(f"❌ Tidak ditemukan artikel untuk keyword '{keyword}'")
        
        # Delay antara keyword
        import time
        time.sleep(2)

# ============================================================================
# PENGGUNAAN INTERAKTIF
# ============================================================================
def mode_interaktif():
    """Jalankan program dalam mode interaktif"""
    print("🎮 MODE INTERAKTIF")
    print("Program akan meminta input keyword dan jumlah artikel")
    print("=" * 50)
    
    # Jalankan fungsi main dari program utama
    main()

# ============================================================================
# MAIN EXECUTION
# ============================================================================
if __name__ == "__main__":
    print("🎓 CONTOH PENGGUNAAN PROGRAM SCRAPING RADAR SURABAYA")
    print("👨‍🏫 Dibuat oleh Dosen Data Mining & Full Stack Engineer")
    print("=" * 70)
    
    # Pilih mode penggunaan
    print("\nPilih mode penggunaan:")
    print("1. Contoh penggunaan dasar")
    print("2. Mode interaktif")
    print("3. Contoh berbagai keyword")
    print("4. Analisis data saja")
    
    try:
        choice = input("\nMasukkan pilihan (1-4): ").strip()
        
        if choice == "1":
            # Contoh penggunaan dasar
            df_results = contoh_penggunaan_dasar()
            if df_results is not None:
                analisis_data(df_results)
                download_hasil(df_results, "harga jagung")
                
        elif choice == "2":
            # Mode interaktif
            mode_interaktif()
            
        elif choice == "3":
            # Contoh berbagai keyword
            contoh_berbagai_keyword()
            
        elif choice == "4":
            # Analisis data saja (perlu data terlebih dahulu)
            print("⚠️ Mode ini memerlukan data hasil scraping terlebih dahulu")
            print("Jalankan mode 1 atau 2 terlebih dahulu")
            
        else:
            print("❌ Pilihan tidak valid")
            
    except KeyboardInterrupt:
        print("\n⚠️ Program dihentikan oleh user")
    except Exception as e:
        print(f"\n❌ Terjadi error: {e}")

# ============================================================================
# TIPS PENGGUNAAN
# ============================================================================
"""
💡 TIPS PENGGUNAAN:

1. **Keyword Spesifik:** Gunakan keyword yang spesifik untuk hasil yang lebih relevan
   Contoh: "harga jagung" lebih baik dari "jagung"

2. **Jumlah Artikel:** Mulai dengan jumlah kecil (5-10) untuk testing
   Jika hasil bagus, bisa ditingkatkan

3. **Koneksi Internet:** Pastikan koneksi stabil untuk hasil optimal

4. **Rate Limiting:** Program sudah dioptimasi dengan delay otomatis
   Jangan khawatir tentang blocking

5. **Etika Penggunaan:** 
   - Gunakan data dengan bijak dan sesuai etika riset
   - Hormati robots.txt dan kebijakan website target
   - Jangan melakukan scraping berlebihan

6. **Troubleshooting:**
   - Jika "Tidak menemukan link artikel": coba keyword yang lebih spesifik
   - Jika "Error saat mengakses URL": periksa koneksi internet
   - Jika "Artikel tidak relevan": gunakan keyword yang lebih spesifik

7. **Untuk Google Colab:**
   - Upload file scraping_radar_surabaya.py terlebih dahulu
   - Install dependencies dengan !pip install
   - Gunakan fungsi download_hasil() untuk download file CSV

8. **Output File:**
   - Format: berita_radar_surabaya_[keyword]_[timestamp].csv
   - Encoding: UTF-8 dengan BOM (kompatibel Excel)
   - Kolom: judul_berita, link_berita, tanggal_rilis, detail_konten
"""