# -*- coding: utf-8 -*-
"""
Contoh Penggunaan Program Scraping Berita Radar Surabaya di Google Colab
Dibuat oleh: Dosen Data Mining & Full Stack Engineer
"""

# ============================================================================
# LANGKAH 1: INSTALL DEPENDENCIES
# ============================================================================
print("🔧 Langkah 1: Install Dependencies")
print("Jalankan perintah berikut di cell Colab:")
print("!pip install requests beautifulsoup4 pandas lxml urllib3")
print()

# ============================================================================
# LANGKAH 2: UPLOAD FILE PROGRAM
# ============================================================================
print("📁 Langkah 2: Upload File Program")
print("1. Upload file 'scraping_radar_surabaya.py' ke Google Colab")
print("2. Gunakan menu Files di sidebar kiri")
print("3. Klik 'Upload to session storage'")
print()

# ============================================================================
# LANGKAH 3: CEK FILE TERUPLOAD
# ============================================================================
print("✅ Langkah 3: Cek File Terupload")
print("Jalankan kode berikut untuk memastikan file sudah terupload:")
print()

import os

def check_file_upload():
    """Fungsi untuk mengecek apakah file program sudah terupload"""
    if os.path.exists('scraping_radar_surabaya.py'):
        print("✅ File scraping_radar_surabaya.py berhasil ditemukan!")
        return True
    else:
        print("❌ File scraping_radar_surabaya.py tidak ditemukan.")
        print("📁 Silakan upload file tersebut menggunakan menu Files di sidebar kiri.")
        return False

# Jalankan pengecekan
file_ready = check_file_upload()
print()

# ============================================================================
# LANGKAH 4: JALANKAN PROGRAM
# ============================================================================
if file_ready:
    print("🚀 Langkah 4: Jalankan Program")
    print("Jalankan perintah berikut untuk memulai scraping:")
    print("!python scraping_radar_surabaya.py")
    print()
    
    print("📝 Contoh Input yang akan diminta:")
    print("🔍 Keyword: harga jagung")
    print("📊 Jumlah artikel: 10")
    print()
    
    print("⏳ Proses yang akan berjalan:")
    print("1. Mengunjungi halaman pencarian Radar Surabaya")
    print("2. Mengumpulkan link artikel yang relevan")
    print("3. Mengekstrak detail setiap artikel")
    print("4. Memvalidasi relevansi dengan keyword")
    print("5. Menyimpan hasil ke file CSV")
    print()

# ============================================================================
# LANGKAH 5: DOWNLOAD HASIL
# ============================================================================
print("📥 Langkah 5: Download Hasil")
print("Setelah program selesai, file CSV akan tersimpan.")
print("Cara download:")
print("1. Klik kanan file CSV di menu Files")
print("2. Pilih 'Download'")
print("3. Atau gunakan kode berikut untuk melihat file yang tersedia:")
print()

def list_csv_files():
    """Fungsi untuk menampilkan file CSV yang tersedia"""
    import glob
    csv_files = glob.glob('berita_radar_surabaya_*.csv')
    if csv_files:
        print(f"📁 Ditemukan {len(csv_files)} file CSV:")
        for file in csv_files:
            print(f"   • {file}")
        print("\n💾 Silakan download file-file tersebut dari menu Files.")
    else:
        print("❌ Belum ada file CSV yang dihasilkan.")
        print("Jalankan program terlebih dahulu.")

# ============================================================================
# LANGKAH 6: ANALISIS DATA (OPSIONAL)
# ============================================================================
print("📊 Langkah 6: Analisis Data (Opsional)")
print("Setelah mendapatkan file CSV, Anda dapat melakukan analisis:")
print()

def analyze_data_example():
    """Contoh analisis data sederhana"""
    import pandas as pd
    import glob
    
    csv_files = glob.glob('berita_radar_surabaya_*.csv')
    if csv_files:
        latest_file = max(csv_files, key=os.path.getctime)
        
        try:
            # Baca data
            df = pd.read_csv(latest_file)
            
            print(f"📊 Analisis Data dari file: {latest_file}")
            print(f"📈 Total artikel: {len(df)}")
            print(f"📅 Artikel dengan tanggal: {(df['tanggal_rilis'] != 'Tanggal tidak ditemukan').sum()}")
            print(f"📝 Artikel dengan konten: {(df['detail_konten'].str.len() > 50).sum()}")
            
            # Preview data
            print("\n📋 Preview 5 artikel pertama:")
            print(df[['judul_berita', 'tanggal_rilis']].head())
            
        except Exception as e:
            print(f"❌ Error saat menganalisis data: {e}")
    else:
        print("❌ Tidak ada file CSV yang ditemukan.")

# ============================================================================
# TIPS PENGGUNAAN
# ============================================================================
print("💡 Tips Penggunaan:")
print()
print("🔍 Keyword yang Efektif:")
print("- 'harga jagung Surabaya' (lebih spesifik)")
print("- 'inflasi Jawa Timur'")
print("- 'pemilu 2024 Surabaya'")
print("- 'ekonomi Jawa Timur'")
print("- 'investasi Surabaya'")
print()
print("📊 Jumlah Artikel yang Direkomendasikan:")
print("- 10-20 artikel: Untuk analisis cepat")
print("- 50-100 artikel: Untuk analisis mendalam")
print("- Maksimal 500 artikel: Batas program")
print()
print("🛠️ Troubleshooting:")
print("- Jika tidak menemukan artikel, coba keyword yang lebih spesifik")
print("- Pastikan koneksi internet stabil")
print("- Jika error, coba jalankan ulang program")
print()

# ============================================================================
# CONTOH OUTPUT
# ============================================================================
print("📄 Contoh Output File CSV:")
print("Nama file: berita_radar_surabaya_harga_jagung_20241201_143022.csv")
print()
print("Isi file:")
print("judul_berita,link_berita,tanggal_rilis,detail_konten")
print("\"Harga Jagung di Surabaya Naik 15 Persen\",\"https://radarsurabaya.jawapos.com/...\",\"2024-12-01\",\"Konten artikel lengkap...\"")
print("\"Petani Jagung Keluhkan Harga Jual\",\"https://radarsurabaya.jawapos.com/...\",\"2024-11-30\",\"Konten artikel lengkap...\"")
print()

print("🎯 Program siap digunakan!")
print("📚 Gunakan data dengan bijak dan sesuai etika riset.")
print("👨‍🏫 Dibuat oleh Dosen Data Mining & Full Stack Engineer")
print("🏆 Pengalaman 30 tahun dalam web scraping dan data mining")