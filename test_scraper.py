#!/usr/bin/env python3
# -*- coding: utf-8 -*-
"""
Test script untuk Radar Surabaya Scraper
"""

import sys
import os
from radar_surabaya_scraper import RadarSurabayaScraper

def test_scraper():
    """Test fungsi dasar scraper"""
    print("🧪 Testing Radar Surabaya Scraper...")
    
    # Inisialisasi scraper
    scraper = RadarSurabayaScraper()
    
    # Test 1: URL generation
    print("\n1. Testing URL generation...")
    test_keyword = "surabaya"
    search_url = scraper.get_search_url(test_keyword, 1)
    print(f"   Search URL: {search_url}")
    
    # Test 2: Page content retrieval
    print("\n2. Testing page content retrieval...")
    try:
        content = scraper.get_page_content(search_url)
        if content:
            print("   ✅ Berhasil mengambil konten halaman")
            print(f"   📄 Panjang konten: {len(content)} karakter")
        else:
            print("   ❌ Gagal mengambil konten halaman")
            return False
    except Exception as e:
        print(f"   ❌ Error: {e}")
        return False
    
    # Test 3: Article link extraction
    print("\n3. Testing article link extraction...")
    try:
        links = scraper.extract_article_links_from_page(content, search_url)
        print(f"   📊 Ditemukan {len(links)} link artikel")
        if links:
            print("   ✅ Berhasil mengekstrak link artikel")
            print(f"   📋 Contoh judul: {links[0]['title'][:50]}...")
        else:
            print("   ⚠️ Tidak ada link artikel yang ditemukan")
    except Exception as e:
        print(f"   ❌ Error: {e}")
        return False
    
    # Test 4: Small scraping test
    print("\n4. Testing small scraping (1 article)...")
    try:
        results = scraper.scrape_news(test_keyword, 1)
        if not results.empty:
            print("   ✅ Berhasil melakukan scraping")
            print(f"   📊 Hasil: {len(results)} artikel")
            print(f"   📋 Judul: {results.iloc[0]['judul_berita']}")
        else:
            print("   ⚠️ Tidak ada hasil scraping")
    except Exception as e:
        print(f"   ❌ Error: {e}")
        return False
    
    print("\n✅ Semua test berhasil!")
    return True

def main():
    """Main function"""
    print("🎓 RADAR SURABAYA SCRAPER - TEST SUITE")
    print("=" * 50)
    
    # Check if required files exist
    if not os.path.exists('radar_surabaya_scraper.py'):
        print("❌ File radar_surabaya_scraper.py tidak ditemukan!")
        print("   Pastikan file scraper ada di direktori yang sama")
        return
    
    # Run tests
    success = test_scraper()
    
    if success:
        print("\n🎉 Test selesai dengan sukses!")
        print("💡 Anda dapat menjalankan scraper dengan:")
        print("   python radar_surabaya_scraper.py")
    else:
        print("\n❌ Test gagal!")
        print("💡 Periksa koneksi internet dan coba lagi")

if __name__ == "__main__":
    main()