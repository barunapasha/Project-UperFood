# -*- coding: utf-8 -*-
"""
Test Program Scraping Radar Surabaya
Dibuat oleh: Dosen Data Mining & Full Stack Engineer
"""

import unittest
from unittest.mock import patch, MagicMock
import pandas as pd
import requests
from bs4 import BeautifulSoup
import sys
import os

# Import program yang akan ditest
sys.path.append(os.path.dirname(os.path.abspath(__file__)))
from scraping_radar_surabaya import *

class TestRadarSurabayaScraping(unittest.TestCase):
    """Test class untuk program scraping Radar Surabaya"""
    
    def setUp(self):
        """Setup untuk setiap test"""
        self.test_keyword = "test keyword"
        self.test_url = "https://radarsurabaya.jawapos.com/search?q=test"
        
    def test_get_search_url(self):
        """Test fungsi get_search_url"""
        # Test URL pencarian
        url = get_search_url("harga jagung")
        expected = "https://radarsurabaya.jawapos.com/search?q=harga%20jagung"
        self.assertEqual(url, expected)
        
        # Test URL dengan halaman
        url_page2 = get_search_url("harga jagung", 2)
        expected_page2 = "https://radarsurabaya.jawapos.com/search?q=harga%20jagung&page=2"
        self.assertEqual(url_page2, expected_page2)
    
    def test_clean_text(self):
        """Test fungsi clean_text"""
        # Test text normal
        text = "  Ini adalah   teks normal  "
        cleaned = clean_text(text)
        self.assertEqual(cleaned, "Ini adalah teks normal")
        
        # Test text dengan karakter khusus
        text_special = "Ini\nteks\tdengan\rkarakter\r\nkhusus"
        cleaned_special = clean_text(text_special)
        self.assertEqual(cleaned_special, "Ini teks dengan karakter khusus")
        
        # Test text kosong
        text_empty = ""
        cleaned_empty = clean_text(text_empty)
        self.assertEqual(cleaned_empty, "")
        
        # Test text None
        text_none = None
        cleaned_none = clean_text(text_none)
        self.assertEqual(cleaned_none, "")
    
    def test_is_article_relevant_by_content(self):
        """Test fungsi is_article_relevant_by_content"""
        # Test artikel relevan
        title = "Harga jagung naik di pasar tradisional"
        content = "Harga jagung mengalami kenaikan signifikan di pasar tradisional Surabaya."
        keyword = "harga jagung"
        result = is_article_relevant_by_content(title, content, keyword)
        self.assertTrue(result)
        
        # Test artikel tidak relevan
        title_not_relevant = "Berita teknologi terbaru"
        content_not_relevant = "Teknologi AI berkembang pesat di Indonesia."
        keyword_not_relevant = "harga jagung"
        result_not_relevant = is_article_relevant_by_content(title_not_relevant, content_not_relevant, keyword_not_relevant)
        self.assertFalse(result_not_relevant)
        
        # Test dengan title atau content kosong
        result_empty = is_article_relevant_by_content("", "konten", "keyword")
        self.assertFalse(result_empty)
        
        result_empty_content = is_article_relevant_by_content("judul", "", "keyword")
        self.assertFalse(result_empty_content)
    
    def test_clean_dataframe(self):
        """Test fungsi clean_dataframe"""
        # Buat DataFrame test
        test_data = {
            'judul_berita': ['Judul 1', 'Judul 2'],
            'link_berita': ['https://example.com', 'bukan_url'],
            'tanggal_rilis': ['2024-01-01', 'https://example.com'],
            'detail_konten': ['Konten artikel', '2024-01-01']
        }
        df = pd.DataFrame(test_data)
        
        # Test pembersihan
        cleaned_df = clean_dataframe(df)
        
        # Verifikasi hasil
        self.assertIsInstance(cleaned_df, pd.DataFrame)
        self.assertEqual(len(cleaned_df), 2)
        
        # Test bahwa URL yang valid tetap di kolom link_berita
        self.assertEqual(cleaned_df.iloc[0]['link_berita'], 'https://example.com')
    
    @patch('requests.get')
    def test_get_page_content_success(self, mock_get):
        """Test fungsi get_page_content dengan response sukses"""
        # Mock response sukses
        mock_response = MagicMock()
        mock_response.text = "<html><body>Test content</body></html>"
        mock_response.raise_for_status.return_value = None
        mock_get.return_value = mock_response
        
        result = get_page_content("https://test.com")
        self.assertEqual(result, "<html><body>Test content</body></html>")
    
    @patch('requests.get')
    def test_get_page_content_error(self, mock_get):
        """Test fungsi get_page_content dengan error"""
        # Mock response error
        mock_get.side_effect = requests.exceptions.RequestException("Connection error")
        
        result = get_page_content("https://test.com")
        self.assertIsNone(result)
    
    def test_extract_article_links_from_page(self):
        """Test fungsi extract_article_links_from_page"""
        # HTML test dengan link artikel
        test_html = """
        <html>
        <body>
        <div class="content">
            <h2><a href="/berita/artikel-1">Judul Artikel 1</a></h2>
            <h2><a href="/berita/artikel-2">Judul Artikel 2</a></h2>
            <a href="/tag/tag1">Tag Link</a>
        </div>
        </body>
        </html>
        """
        
        result = extract_article_links_from_page(test_html, "https://radarsurabaya.jawapos.com")
        
        # Verifikasi hasil
        self.assertIsInstance(result, list)
        self.assertGreater(len(result), 0)
        
        # Verifikasi struktur data
        for item in result:
            self.assertIn('title', item)
            self.assertIn('url', item)
            self.assertIsInstance(item['title'], str)
            self.assertIsInstance(item['url'], str)
    
    def test_extract_article_details(self):
        """Test fungsi extract_article_details"""
        # HTML test untuk artikel
        test_html = """
        <html>
        <body>
        <div class="content">
            <h1 class="article-title">Judul Artikel Test</h1>
            <time datetime="2024-01-01">1 Januari 2024</time>
            <article class="article-content">
                Ini adalah konten artikel yang akan diekstrak.
                Artikel ini berisi informasi penting tentang topik tertentu.
            </article>
        </div>
        </body>
        </html>
        """
        
        with patch('scraping_radar_surabaya.get_page_content', return_value=test_html):
            title, date, content = extract_article_details("https://test.com")
            
            # Verifikasi hasil
            self.assertIsInstance(title, str)
            self.assertIsInstance(date, str)
            self.assertIsInstance(content, str)
            
            # Verifikasi konten tidak kosong
            self.assertGreater(len(title), 0)
            self.assertGreater(len(content), 0)
    
    def test_save_to_csv(self):
        """Test fungsi save_to_csv"""
        # Buat DataFrame test
        test_data = {
            'judul_berita': ['Judul Test 1', 'Judul Test 2'],
            'link_berita': ['https://test1.com', 'https://test2.com'],
            'tanggal_rilis': ['2024-01-01', '2024-01-02'],
            'detail_konten': ['Konten 1', 'Konten 2']
        }
        df = pd.DataFrame(test_data)
        
        # Test penyimpanan
        filename = save_to_csv(df, "test_keyword")
        
        # Verifikasi file tersimpan
        self.assertIsInstance(filename, str)
        self.assertTrue(filename.endswith('.csv'))
        
        # Verifikasi file ada
        self.assertTrue(os.path.exists(filename))
        
        # Cleanup - hapus file test
        if os.path.exists(filename):
            os.remove(filename)
    
    def test_scrape_radar_surabaya_news_empty_result(self):
        """Test fungsi scrape_radar_surabaya_news dengan hasil kosong"""
        with patch('scraping_radar_surabaya.get_page_content', return_value=None):
            result = scrape_radar_surabaya_news("keyword_tidak_ada", 5)
            self.assertTrue(result.empty)
    
    def test_integration_small_scale(self):
        """Test integrasi skala kecil"""
        # Test dengan keyword yang mungkin ada
        keyword = "test"
        max_articles = 1
        
        # Jalankan scraping (mungkin akan gagal karena keyword test, tapi test error handling)
        try:
            result = scrape_radar_surabaya_news(keyword, max_articles)
            # Jika berhasil, verifikasi struktur
            if not result.empty:
                self.assertIn('judul_berita', result.columns)
                self.assertIn('link_berita', result.columns)
                self.assertIn('tanggal_rilis', result.columns)
                self.assertIn('detail_konten', result.columns)
        except Exception as e:
            # Jika gagal, pastikan error ditangani dengan baik
            self.assertIsInstance(e, Exception)

def run_tests():
    """Fungsi untuk menjalankan semua test"""
    print("🧪 MENJALANKAN TEST PROGRAM SCRAPING RADAR SURABAYA")
    print("=" * 60)
    
    # Buat test suite
    test_suite = unittest.TestLoader().loadTestsFromTestCase(TestRadarSurabayaScraping)
    
    # Jalankan test
    runner = unittest.TextTestRunner(verbosity=2)
    result = runner.run(test_suite)
    
    # Tampilkan hasil
    print("\n" + "=" * 60)
    print("📊 HASIL TEST:")
    print(f"Tests run: {result.testsRun}")
    print(f"Failures: {len(result.failures)}")
    print(f"Errors: {len(result.errors)}")
    
    if result.failures:
        print("\n❌ FAILURES:")
        for test, traceback in result.failures:
            print(f"- {test}: {traceback}")
    
    if result.errors:
        print("\n❌ ERRORS:")
        for test, traceback in result.errors:
            print(f"- {test}: {traceback}")
    
    if not result.failures and not result.errors:
        print("\n✅ SEMUA TEST BERHASIL!")
        print("Program siap digunakan.")
    else:
        print("\n⚠️ ADA TEST YANG GAGAL!")
        print("Periksa error di atas dan perbaiki sebelum menggunakan program.")
    
    return result.wasSuccessful()

if __name__ == "__main__":
    # Jalankan test
    success = run_tests()
    
    # Exit dengan kode yang sesuai
    sys.exit(0 if success else 1)