#!/usr/bin/env python3
"""
Test file untuk Radar Surabaya Web Scraper
Test file for Radar Surabaya Web Scraper
"""

import unittest
from unittest.mock import patch, MagicMock
import tempfile
import os
import json
import pandas as pd
from radar_scraper import RadarSurabayaScraper
from bs4 import BeautifulSoup

class TestRadarSurabayaScraper(unittest.TestCase):
    """
    Test cases untuk RadarSurabayaScraper
    """
    
    def setUp(self):
        """
        Setup untuk setiap test
        """
        self.scraper = RadarSurabayaScraper()
        self.test_query = "harga jagung"
        
    def test_init(self):
        """
        Test inisialisasi scraper
        """
        self.assertEqual(self.scraper.base_url, "https://radarsurabaya.jawapos.com")
        self.assertIsNotNone(self.scraper.session)
        self.assertEqual(self.scraper.articles_data, [])
        
    def test_search_articles(self):
        """
        Test fungsi search_articles
        """
        search_url = self.scraper.search_articles(self.test_query)
        expected_url = f"{self.scraper.base_url}/search?q=harga+jagung"
        self.assertEqual(search_url, expected_url)
        
    def test_extract_article_content_with_mock_html(self):
        """
        Test ekstraksi konten artikel dengan HTML mock
        """
        # Mock HTML content
        mock_html = """
        <html>
            <body>
                <h1>Test Article Title</h1>
                <div class="article-date">2024-01-15</div>
                <article>
                    <p>This is the article content.</p>
                    <p>More content here.</p>
                </article>
            </body>
        </html>
        """
        
        with patch('requests.Session.get') as mock_get:
            mock_response = MagicMock()
            mock_response.content = mock_html.encode('utf-8')
            mock_response.raise_for_status.return_value = None
            mock_get.return_value = mock_response
            
            article_data = self.scraper.extract_article_content("http://test.com/article")
            
            self.assertEqual(article_data['title'], "Test Article Title")
            self.assertEqual(article_data['date'], "2024-01-15")
            self.assertIn("This is the article content", article_data['content'])
            self.assertEqual(article_data['url'], "http://test.com/article")
            
    def test_save_to_csv(self):
        """
        Test penyimpanan ke CSV
        """
        # Setup test data
        self.scraper.articles_data = [
            {
                'title': 'Test Article 1',
                'date': '2024-01-15',
                'content': 'Test content 1',
                'url': 'http://test1.com',
                'scraped_at': '2024-01-15 10:00:00'
            },
            {
                'title': 'Test Article 2',
                'date': '2024-01-16',
                'content': 'Test content 2',
                'url': 'http://test2.com',
                'scraped_at': '2024-01-15 10:01:00'
            }
        ]
        
        # Test dengan temporary file
        with tempfile.NamedTemporaryFile(mode='w', suffix='.csv', delete=False) as tmp_file:
            tmp_filename = tmp_file.name
            
        try:
            self.scraper.save_to_csv(tmp_filename)
            
            # Verify file exists and has content
            self.assertTrue(os.path.exists(tmp_filename))
            
            # Read and verify CSV content
            df = pd.read_csv(tmp_filename)
            self.assertEqual(len(df), 2)
            self.assertEqual(df.iloc[0]['title'], 'Test Article 1')
            self.assertEqual(df.iloc[1]['title'], 'Test Article 2')
            
        finally:
            # Cleanup
            if os.path.exists(tmp_filename):
                os.unlink(tmp_filename)
                
    def test_save_to_json(self):
        """
        Test penyimpanan ke JSON
        """
        # Setup test data
        self.scraper.articles_data = [
            {
                'title': 'Test Article 1',
                'date': '2024-01-15',
                'content': 'Test content 1',
                'url': 'http://test1.com',
                'scraped_at': '2024-01-15 10:00:00'
            }
        ]
        
        # Test dengan temporary file
        with tempfile.NamedTemporaryFile(mode='w', suffix='.json', delete=False) as tmp_file:
            tmp_filename = tmp_file.name
            
        try:
            self.scraper.save_to_json(tmp_filename)
            
            # Verify file exists and has content
            self.assertTrue(os.path.exists(tmp_filename))
            
            # Read and verify JSON content
            with open(tmp_filename, 'r', encoding='utf-8') as f:
                data = json.load(f)
                
            self.assertEqual(len(data), 1)
            self.assertEqual(data[0]['title'], 'Test Article 1')
            self.assertEqual(data[0]['date'], '2024-01-15')
            
        finally:
            # Cleanup
            if os.path.exists(tmp_filename):
                os.unlink(tmp_filename)
                
    def test_get_search_results_empty(self):
        """
        Test get_search_results dengan hasil kosong
        """
        with patch('requests.Session.get') as mock_get:
            mock_response = MagicMock()
            mock_response.content = "<html><body><div>No articles found</div></body></html>"
            mock_response.raise_for_status.return_value = None
            mock_get.return_value = mock_response
            
            results = self.scraper.get_search_results("http://test.com/search")
            self.assertEqual(results, [])
            
    def test_error_handling(self):
        """
        Test error handling
        """
        with patch('requests.Session.get') as mock_get:
            mock_get.side_effect = Exception("Network error")
            
            # Test extract_article_content dengan error
            result = self.scraper.extract_article_content("http://test.com/article")
            self.assertIn("Error", result['title'])
            self.assertIn("Network error", result['content'])
            
    def test_content_filtering(self):
        """
        Test filtering konten (menghapus iklan, script, dll)
        """
        mock_html = """
        <html>
            <body>
                <h1>Test Article</h1>
                <article>
                    <p>Real content here.</p>
                    <script>alert('ad');</script>
                    <div class="advertisement">Ad content</div>
                    <p>More real content.</p>
                </article>
            </body>
        </html>
        """
        
        with patch('requests.Session.get') as mock_get:
            mock_response = MagicMock()
            mock_response.content = mock_html.encode('utf-8')
            mock_response.raise_for_status.return_value = None
            mock_get.return_value = mock_response
            
            article_data = self.scraper.extract_article_content("http://test.com/article")
            
            # Verify iklan dan script dihapus
            self.assertIn("Real content here", article_data['content'])
            self.assertIn("More real content", article_data['content'])
            self.assertNotIn("alert('ad')", article_data['content'])
            self.assertNotIn("Ad content", article_data['content'])

def run_tests():
    """
    Jalankan semua test
    """
    print("="*60)
    print("RUNNING RADAR SURABAYA SCRAPER TESTS")
    print("="*60)
    
    # Create test suite
    test_suite = unittest.TestLoader().loadTestsFromTestCase(TestRadarSurabayaScraper)
    
    # Run tests
    runner = unittest.TextTestRunner(verbosity=2)
    result = runner.run(test_suite)
    
    # Print summary
    print("\n" + "="*60)
    print("TEST SUMMARY")
    print("="*60)
    print(f"Tests run: {result.testsRun}")
    print(f"Failures: {len(result.failures)}")
    print(f"Errors: {len(result.errors)}")
    
    if result.failures:
        print("\nFAILURES:")
        for test, traceback in result.failures:
            print(f"- {test}: {traceback}")
            
    if result.errors:
        print("\nERRORS:")
        for test, traceback in result.errors:
            print(f"- {test}: {traceback}")
    
    return result.wasSuccessful()

if __name__ == "__main__":
    success = run_tests()
    exit(0 if success else 1)