# -*- coding: utf-8 -*-
"""
Test Program Scraping Berita Radar Surabaya
Dibuat oleh: Dosen Data Mining & Full Stack Engineer
"""

import sys
import os

def test_imports():
    """Test apakah semua library yang diperlukan dapat diimport"""
    print("🔧 Testing imports...")
    
    try:
        import requests
        print("✅ requests - OK")
    except ImportError:
        print("❌ requests - FAILED")
        return False
    
    try:
        from bs4 import BeautifulSoup
        print("✅ beautifulsoup4 - OK")
    except ImportError:
        print("❌ beautifulsoup4 - FAILED")
        return False
    
    try:
        import pandas as pd
        print("✅ pandas - OK")
    except ImportError:
        print("❌ pandas - FAILED")
        return False
    
    try:
        import urllib3
        print("✅ urllib3 - OK")
    except ImportError:
        print("❌ urllib3 - FAILED")
        return False
    
    return True

def test_file_exists():
    """Test apakah file program utama ada"""
    print("\n📁 Testing file existence...")
    
    if os.path.exists('scraping_radar_surabaya.py'):
        print("✅ scraping_radar_surabaya.py - FOUND")
        return True
    else:
        print("❌ scraping_radar_surabaya.py - NOT FOUND")
        print("💡 Silakan upload file scraping_radar_surabaya.py terlebih dahulu")
        return False

def test_basic_functionality():
    """Test fungsi dasar program"""
    print("\n🧪 Testing basic functionality...")
    
    try:
        # Import fungsi dari program utama
        from scraping_radar_surabaya import get_search_url, clean_text, get_page_content
        
        # Test fungsi get_search_url
        test_url = get_search_url("test keyword")
        if "radarsurabaya.jawapos.com" in test_url and "test+keyword" in test_url:
            print("✅ get_search_url - OK")
        else:
            print("❌ get_search_url - FAILED")
            return False
        
        # Test fungsi clean_text
        test_text = clean_text("  test   text  with   spaces  ")
        if test_text == "test text with spaces":
            print("✅ clean_text - OK")
        else:
            print("❌ clean_text - FAILED")
            return False
        
        # Test fungsi get_page_content (hanya test koneksi)
        print("🌐 Testing connection to Radar Surabaya...")
        test_response = get_page_content("https://radarsurabaya.jawapos.com/")
        if test_response and len(test_response) > 1000:
            print("✅ get_page_content - OK (Connection successful)")
        else:
            print("⚠️ get_page_content - WARNING (Connection may be slow)")
        
        return True
        
    except Exception as e:
        print(f"❌ Basic functionality test - FAILED: {e}")
        return False

def main():
    """Fungsi utama test"""
    print("🎓 TEST PROGRAM SCRAPING BERITA RADAR SURABAYA")
    print("👨‍🏫 Dibuat oleh Dosen Data Mining & Full Stack Engineer")
    print("=" * 60)
    
    # Test imports
    if not test_imports():
        print("\n❌ Import test failed. Install dependencies first:")
        print("pip install requests beautifulsoup4 pandas lxml urllib3")
        return
    
    # Test file existence
    if not test_file_exists():
        return
    
    # Test basic functionality
    if test_basic_functionality():
        print("\n🎉 SEMUA TEST BERHASIL!")
        print("✅ Program siap digunakan")
        print("\n📝 Langkah selanjutnya:")
        print("1. Jalankan: python scraping_radar_surabaya.py")
        print("2. Masukkan keyword yang ingin dicari")
        print("3. Tentukan jumlah artikel")
        print("4. Tunggu proses selesai")
    else:
        print("\n❌ Beberapa test gagal")
        print("💡 Periksa koneksi internet dan coba lagi")

if __name__ == "__main__":
    main()