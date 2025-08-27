# -*- coding: utf-8 -*-
"""
Demo Program Scraping Radar Surabaya
Dibuat oleh: Dosen Data Mining & Full Stack Engineer
"""

from scraping_radar_surabaya import *
import time

def demo_simple_scraping():
    """Demo scraping sederhana"""
    print("🎯 DEMO 1: SCRAPING SEDERHANA")
    print("=" * 50)
    
    # Parameter
    keyword = "harga jagung"
    max_articles = 3
    
    print(f"🔍 Keyword: {keyword}")
    print(f"📊 Jumlah artikel: {max_articles}")
    print("⏳ Memulai scraping...")
    
    # Jalankan scraping
    start_time = time.time()
    df_results = scrape_radar_surabaya_news(keyword, max_articles)
    end_time = time.time()
    
    # Tampilkan hasil
    if not df_results.empty:
        print(f"\n✅ Berhasil mengambil {len(df_results)} artikel dalam {end_time - start_time:.2f} detik")
        print("\n📋 HASIL:")
        for idx, row in df_results.iterrows():
            print(f"\n{idx+1}. {row['judul_berita']}")
            print(f"   📅 {row['tanggal_rilis']}")
            print(f"   🔗 {row['link_berita']}")
            print(f"   📝 {str(row['detail_konten'])[:100]}...")
        
        # Simpan hasil
        filename = save_to_csv(df_results, keyword)
        print(f"\n💾 File disimpan: {filename}")
    else:
        print("❌ Tidak ada data yang berhasil diambil")

def demo_multiple_keywords():
    """Demo scraping dengan multiple keyword"""
    print("\n🎯 DEMO 2: SCRAPING MULTIPLE KEYWORDS")
    print("=" * 50)
    
    keywords = ["ekonomi", "teknologi", "pendidikan"]
    max_articles_per_keyword = 2
    
    all_results = []
    
    for keyword in keywords:
        print(f"\n🔍 Mencari: {keyword}")
        df_results = scrape_radar_surabaya_news(keyword, max_articles_per_keyword)
        
        if not df_results.empty:
            print(f"✅ Ditemukan {len(df_results)} artikel")
            all_results.append(df_results)
            
            # Simpan per keyword
            filename = save_to_csv(df_results, keyword)
            print(f"💾 Disimpan: {filename}")
        else:
            print("❌ Tidak ditemukan artikel")
        
        # Delay antar keyword
        time.sleep(2)
    
    # Gabungkan semua hasil
    if all_results:
        combined_df = pd.concat(all_results, ignore_index=True)
        combined_filename = save_to_csv(combined_df, "combined_keywords")
        print(f"\n📊 Total artikel dari semua keyword: {len(combined_df)}")
        print(f"💾 File gabungan: {combined_filename}")

def demo_analysis():
    """Demo analisis data hasil scraping"""
    print("\n🎯 DEMO 3: ANALISIS DATA")
    print("=" * 50)
    
    # Ambil data terlebih dahulu
    keyword = "pemilu"
    df_results = scrape_radar_surabaya_news(keyword, 5)
    
    if not df_results.empty:
        print(f"📊 ANALISIS DATA UNTUK KEYWORD: {keyword}")
        print("=" * 40)
        
        # Statistik dasar
        print(f"Total artikel: {len(df_results)}")
        print(f"Artikel dengan tanggal: {df_results['tanggal_rilis'].apply(lambda x: x != 'Tanggal tidak ditemukan' and not x.startswith('Error')).sum()}")
        print(f"Artikel dengan konten: {df_results['detail_konten'].apply(lambda x: len(x) > 50 and not x.startswith('Error')).sum()}")
        
        # Analisis panjang konten
        content_lengths = df_results['detail_konten'].apply(lambda x: len(str(x)))
        print(f"\n📏 ANALISIS PANJANG KONTEN:")
        print(f"Rata-rata: {content_lengths.mean():.0f} karakter")
        print(f"Terpendek: {content_lengths.min()} karakter")
        print(f"Terpanjang: {content_lengths.max()} karakter")
        
        # Analisis judul
        title_lengths = df_results['judul_berita'].apply(lambda x: len(str(x)))
        print(f"\n📋 ANALISIS PANJANG JUDUL:")
        print(f"Rata-rata: {title_lengths.mean():.0f} karakter")
        print(f"Terpendek: {title_lengths.min()} karakter")
        print(f"Terpanjang: {title_lengths.max()} karakter")
        
        # Preview konten
        print(f"\n📄 PREVIEW KONTEN:")
        for idx, row in df_results.head(2).iterrows():
            print(f"\n{idx+1}. {row['judul_berita']}")
            print(f"   {str(row['detail_konten'])[:150]}...")
    else:
        print("❌ Tidak ada data untuk dianalisis")

def demo_error_handling():
    """Demo penanganan error"""
    print("\n🎯 DEMO 4: PENANGANAN ERROR")
    print("=" * 50)
    
    # Test dengan keyword yang tidak mungkin ada
    keyword = "keyword_yang_sangat_tidak_mungkin_ada_12345"
    print(f"🔍 Testing dengan keyword: {keyword}")
    
    try:
        df_results = scrape_radar_surabaya_news(keyword, 1)
        if df_results.empty:
            print("✅ Program menangani keyword tidak ditemukan dengan baik")
        else:
            print("⚠️ Program menemukan data (tidak diharapkan)")
    except Exception as e:
        print(f"❌ Error: {e}")
        print("⚠️ Program mengalami error (perlu diperbaiki)")

def demo_configuration():
    """Demo konfigurasi program"""
    print("\n🎯 DEMO 5: KONFIGURASI PROGRAM")
    print("=" * 50)
    
    # Import config
    try:
        from config import print_config, update_config
        print_config()
        
        print("\n🔧 UPDATE KONFIGURASI:")
        print("Mengubah delay menjadi lebih cepat untuk demo...")
        update_config(min_delay=0.5, max_delay=1.0)
        print("✅ Konfigurasi berhasil diupdate")
        
    except ImportError:
        print("⚠️ File config.py tidak ditemukan")

def demo_interactive():
    """Demo mode interaktif"""
    print("\n🎯 DEMO 6: MODE INTERAKTIF")
    print("=" * 50)
    
    print("🎮 Mode interaktif akan meminta input dari user")
    print("Untuk mencoba, jalankan: main()")
    print("Atau uncomment baris di bawah ini:")
    # main()

def run_all_demos():
    """Jalankan semua demo"""
    print("🎓 DEMO PROGRAM SCRAPING RADAR SURABAYA")
    print("👨‍🏫 Dibuat oleh Dosen Data Mining & Full Stack Engineer")
    print("=" * 70)
    
    demos = [
        ("Scraping Sederhana", demo_simple_scraping),
        ("Multiple Keywords", demo_multiple_keywords),
        ("Analisis Data", demo_analysis),
        ("Penanganan Error", demo_error_handling),
        ("Konfigurasi", demo_configuration),
        ("Mode Interaktif", demo_interactive)
    ]
    
    print("\nPilih demo yang ingin dijalankan:")
    for i, (name, _) in enumerate(demos, 1):
        print(f"{i}. {name}")
    print("0. Jalankan semua demo")
    
    try:
        choice = input("\nMasukkan pilihan (0-6): ").strip()
        
        if choice == "0":
            # Jalankan semua demo
            for name, demo_func in demos:
                print(f"\n{'='*70}")
                print(f"🎯 MENJALANKAN: {name}")
                print(f"{'='*70}")
                try:
                    demo_func()
                except Exception as e:
                    print(f"❌ Error dalam demo {name}: {e}")
                time.sleep(2)
        elif choice.isdigit() and 1 <= int(choice) <= len(demos):
            # Jalankan demo tertentu
            idx = int(choice) - 1
            name, demo_func = demos[idx]
            print(f"\n🎯 MENJALANKAN: {name}")
            demo_func()
        else:
            print("❌ Pilihan tidak valid")
            
    except KeyboardInterrupt:
        print("\n⚠️ Demo dihentikan oleh user")
    except Exception as e:
        print(f"\n❌ Error: {e}")

def quick_test():
    """Test cepat untuk memverifikasi program berfungsi"""
    print("🧪 QUICK TEST - Verifikasi Program")
    print("=" * 40)
    
    # Test import
    try:
        from scraping_radar_surabaya import *
        print("✅ Import berhasil")
    except Exception as e:
        print(f"❌ Import gagal: {e}")
        return False
    
    # Test fungsi dasar
    try:
        url = get_search_url("test")
        print(f"✅ get_search_url: {url}")
    except Exception as e:
        print(f"❌ get_search_url gagal: {e}")
        return False
    
    # Test clean_text
    try:
        cleaned = clean_text("  test  text  ")
        print(f"✅ clean_text: '{cleaned}'")
    except Exception as e:
        print(f"❌ clean_text gagal: {e}")
        return False
    
    # Test relevansi
    try:
        is_relevant = is_article_relevant_by_content("Test title", "Test content", "test")
        print(f"✅ is_article_relevant_by_content: {is_relevant}")
    except Exception as e:
        print(f"❌ is_article_relevant_by_content gagal: {e}")
        return False
    
    print("✅ Semua test berhasil! Program siap digunakan.")
    return True

if __name__ == "__main__":
    print("🎓 DEMO PROGRAM SCRAPING RADAR SURABAYA")
    print("=" * 70)
    
    # Quick test terlebih dahulu
    if quick_test():
        print("\n" + "=" * 70)
        run_all_demos()
    else:
        print("\n❌ Program tidak siap digunakan. Periksa error di atas.")