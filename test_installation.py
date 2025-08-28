#!/usr/bin/env python3
# -*- coding: utf-8 -*-
"""
Test script untuk memverifikasi instalasi dependencies dan import modules
"""

import sys
import importlib

def test_imports():
    """Test import semua dependencies yang diperlukan"""
    print("🔍 Testing imports...")
    
    required_modules = [
        'requests',
        'bs4',
        'pandas',
        'urllib3',
        'lxml',
        'html5lib'
    ]
    
    failed_imports = []
    
    for module in required_modules:
        try:
            importlib.import_module(module)
            print(f"✅ {module} - OK")
        except ImportError as e:
            print(f"❌ {module} - FAILED: {e}")
            failed_imports.append(module)
    
    if failed_imports:
        print(f"\n❌ Failed to import: {', '.join(failed_imports)}")
        print("💡 Install missing dependencies with: pip install -r requirements.txt")
        return False
    else:
        print("\n✅ All dependencies imported successfully!")
        return True

def test_scraper_import():
    """Test import scraper modules"""
    print("\n🔍 Testing scraper imports...")
    
    try:
        # Test import original scraper
        import radar_surabaya_scraper
        print("✅ radar_surabaya_scraper - OK")
    except ImportError as e:
        print(f"❌ radar_surabaya_scraper - FAILED: {e}")
        return False
    
    try:
        # Test import improved scraper
        import radar_surabaya_scraper_improved
        print("✅ radar_surabaya_scraper_improved - OK")
    except ImportError as e:
        print(f"❌ radar_surabaya_scraper_improved - FAILED: {e}")
        return False
    
    print("\n✅ All scraper modules imported successfully!")
    return True

def test_basic_functionality():
    """Test basic functionality tanpa melakukan scraping"""
    print("\n🔍 Testing basic functionality...")
    
    try:
        from radar_surabaya_scraper_improved import RadarSurabayaScraper, ScrapingConfig
        
        # Test config creation
        config = ScrapingConfig()
        print("✅ ScrapingConfig creation - OK")
        
        # Test scraper initialization
        scraper = RadarSurabayaScraper(config)
        print("✅ RadarSurabayaScraper initialization - OK")
        
        # Test URL generation
        search_url = scraper.get_search_url("test", 1)
        print(f"✅ URL generation - OK: {search_url}")
        
        print("\n✅ Basic functionality test passed!")
        return True
        
    except Exception as e:
        print(f"❌ Basic functionality test failed: {e}")
        return False

def main():
    """Main test function"""
    print("🧪 RADAR SURABAYA SCRAPER - INSTALLATION TEST")
    print("=" * 50)
    
    # Test Python version
    print(f"🐍 Python version: {sys.version}")
    
    # Run tests
    tests = [
        ("Dependencies Import", test_imports),
        ("Scraper Import", test_scraper_import),
        ("Basic Functionality", test_basic_functionality)
    ]
    
    passed = 0
    total = len(tests)
    
    for test_name, test_func in tests:
        print(f"\n📋 Running: {test_name}")
        if test_func():
            passed += 1
        else:
            print(f"❌ {test_name} failed!")
    
    print("\n" + "=" * 50)
    print(f"📊 Test Results: {passed}/{total} tests passed")
    
    if passed == total:
        print("🎉 All tests passed! Installation is successful.")
        print("🚀 You can now run the scraper:")
        print("   python radar_surabaya_scraper.py")
        print("   python radar_surabaya_scraper_improved.py")
    else:
        print("❌ Some tests failed. Please check the errors above.")
        print("💡 Make sure to install all dependencies:")
        print("   pip install -r requirements.txt")
    
    return passed == total

if __name__ == "__main__":
    success = main()
    sys.exit(0 if success else 1)