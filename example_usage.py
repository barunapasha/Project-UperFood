#!/usr/bin/env python3
# -*- coding: utf-8 -*-
"""
Contoh penggunaan Radar Surabaya Scraper
"""

from radar_surabaya_scraper import RadarSurabayaScraper
import pandas as pd

def example_basic_usage():
    """Contoh penggunaan dasar"""
    print("🔍 Contoh Penggunaan Dasar Radar Surabaya Scraper")
    print("=" * 50)
    
    # Inisialisasi scraper
    scraper = RadarSurabayaScraper()
    
    # Keyword pencarian
    keyword = "surabaya"
    max_articles = 3
    
    print(f"📝 Keyword: {keyword}")
    print(f"📊 Maksimal artikel: {max_articles}")
    print("\n⏳ Memulai scraping...")
    
    # Lakukan scraping
    results = scraper.scrape_news(keyword, max_articles)
    
    if not results.empty:
        print(f"\n✅ Berhasil mengambil {len(results)} artikel")
        
        # Tampilkan hasil
        for idx, row in results.iterrows():
            print(f"\n📋 Artikel {idx + 1}:")
            print(f"   Judul: {row['judul_berita']}")
            print(f"   Tanggal: {row['tanggal_rilis']}")
            print(f"   URL: {row['link_berita']}")
            print(f"   Konten (preview): {row['detail_konten'][:100]}...")
        
        # Simpan ke CSV
        filename = scraper.save_to_csv(results, keyword)
        if filename:
            print(f"\n💾 Data disimpan ke: {filename}")
    else:
        print("❌ Tidak ada hasil yang ditemukan")

def example_multiple_keywords():
    """Contoh scraping dengan multiple keywords"""
    print("\n🔍 Contoh Scraping Multiple Keywords")
    print("=" * 50)
    
    scraper = RadarSurabayaScraper()
    
    keywords = ["pemilu", "pilpres", "pemilihan"]
    all_results = []
    
    for keyword in keywords:
        print(f"\n📝 Scraping untuk keyword: {keyword}")
        results = scraper.scrape_news(keyword, 2)
        
        if not results.empty:
            # Tambahkan kolom keyword
            results['keyword_pencarian'] = keyword
            all_results.append(results)
            print(f"   ✅ Ditemukan {len(results)} artikel")
        else:
            print(f"   ❌ Tidak ada hasil untuk '{keyword}'")
    
    if all_results:
        # Gabungkan semua hasil
        combined_results = pd.concat(all_results, ignore_index=True)
        print(f"\n📊 Total artikel dari semua keyword: {len(combined_results)}")
        
        # Simpan hasil gabungan
        filename = scraper.save_to_csv(combined_results, "multiple_keywords")
        if filename:
            print(f"💾 Data gabungan disimpan ke: {filename}")
    else:
        print("❌ Tidak ada hasil dari semua keyword")

def example_custom_config():
    """Contoh dengan konfigurasi custom"""
    print("\n🔧 Contoh dengan Konfigurasi Custom")
    print("=" * 50)
    
    # Import config untuk modifikasi
    import config
    
    # Modifikasi konfigurasi sementara
    original_timeout = config.REQUEST_TIMEOUT
    config.REQUEST_TIMEOUT = 15  # Timeout lebih pendek
    
    try:
        scraper = RadarSurabayaScraper()
        
        # Test dengan timeout yang lebih pendek
        keyword = "test"
        results = scraper.scrape_news(keyword, 1)
        
        if not results.empty:
            print(f"✅ Berhasil dengan timeout {config.REQUEST_TIMEOUT}s")
        else:
            print("⚠️ Tidak ada hasil (mungkin karena timeout)")
    
    finally:
        # Kembalikan konfigurasi asli
        config.REQUEST_TIMEOUT = original_timeout

def main():
    """Fungsi utama"""
    print("🎓 CONTOH PENGGUNAAN RADAR SURABAYA SCRAPER")
    print("=" * 60)
    
    try:
        # Contoh 1: Penggunaan dasar
        example_basic_usage()
        
        # Contoh 2: Multiple keywords
        example_multiple_keywords()
        
        # Contoh 3: Konfigurasi custom
        example_custom_config()
        
    except KeyboardInterrupt:
        print("\n⚠️ Program dihentikan oleh user")
    except Exception as e:
        print(f"\n❌ Error: {e}")
    
    print("\n👋 Selesai!")

if __name__ == "__main__":
    main()