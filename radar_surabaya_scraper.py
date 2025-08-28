#!/usr/bin/env python3
# -*- coding: utf-8 -*-
"""
Radar Surabaya Web Scraper
Author: Data Mining Expert & Full Stack Engineer (30 years experience)
Description: Comprehensive web scraper for https://radarsurabaya.jawapos.com/
Features: Search functionality, article extraction, content scraping
"""

import requests
from bs4 import BeautifulSoup
import time
import json
import re
from urllib.parse import quote_plus, urljoin
import pandas as pd
from datetime import datetime
import logging

# Setup logging
logging.basicConfig(level=logging.INFO, format='%(asctime)s - %(levelname)s - %(message)s')
logger = logging.getLogger(__name__)

class RadarSurabayaScraper:
    def __init__(self):
        """Initialize the scraper with headers and session"""
        self.base_url = "https://radarsurabaya.jawapos.com"
        self.session = requests.Session()
        
        # Set headers to mimic real browser
        self.headers = {
            'User-Agent': 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
            'Accept': 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
            'Accept-Language': 'en-US,en;q=0.5',
            'Accept-Encoding': 'gzip, deflate',
            'Connection': 'keep-alive',
            'Upgrade-Insecure-Requests': '1',
            'Cache-Control': 'max-age=0'
        }
        
        self.session.headers.update(self.headers)
        
    def search_articles(self, query, max_pages=5):
        """
        Search for articles based on query
        
        Args:
            query (str): Search term
            max_pages (int): Maximum number of pages to scrape
            
        Returns:
            list: List of article data dictionaries
        """
        logger.info(f"Searching for articles with query: {query}")
        
        # Encode query for URL
        encoded_query = quote_plus(query)
        search_url = f"{self.base_url}/search?q={encoded_query}"
        
        articles = []
        page = 1
        
        try:
            while page <= max_pages:
                logger.info(f"Scraping page {page} of search results")
                
                # Add page parameter if not first page
                if page > 1:
                    page_url = f"{search_url}&page={page}"
                else:
                    page_url = search_url
                
                # Get search results page
                response = self.session.get(page_url, timeout=30)
                response.raise_for_status()
                
                soup = BeautifulSoup(response.content, 'html.parser')
                
                # Find article containers
                article_containers = soup.find_all('div', class_='latest__wrap')
                
                if not article_containers:
                    logger.info(f"No more articles found on page {page}")
                    break
                
                # Extract articles from current page
                page_articles = self._extract_articles_from_page(soup)
                articles.extend(page_articles)
                
                # Check if there are more pages
                next_page = soup.find('a', {'rel': 'next'}) or soup.find('a', string=re.compile(r'next', re.I))
                if not next_page:
                    logger.info("No more pages available")
                    break
                
                page += 1
                time.sleep(2)  # Be respectful to the server
                
        except Exception as e:
            logger.error(f"Error during search: {str(e)}")
            
        logger.info(f"Found {len(articles)} articles total")
        return articles
    
    def _extract_articles_from_page(self, soup):
        """
        Extract article information from a single page
        
        Args:
            soup (BeautifulSoup): Parsed HTML content
            
        Returns:
            list: List of article data dictionaries
        """
        articles = []
        
        try:
            # Find all article containers
            article_containers = soup.find_all('div', class_='latest__wrap')
            
            for container in article_containers:
                try:
                    # Extract title and link
                    title_element = container.find('h2').find('a') if container.find('h2') else None
                    if not title_element:
                        continue
                        
                    title = title_element.get_text(strip=True)
                    article_url = urljoin(self.base_url, title_element.get('href', ''))
                    
                    # Extract date
                    date_element = container.find('date')
                    if date_element:
                        date_text = date_element.get_text(strip=True)
                    else:
                        # Try alternative date selectors
                        date_element = container.find('time') or container.find('span', class_='date')
                        date_text = date_element.get_text(strip=True) if date_element else "Unknown"
                    
                    # Extract summary/excerpt if available
                    summary_element = container.find('p') or container.find('div', class_='excerpt')
                    summary = summary_element.get_text(strip=True) if summary_element else ""
                    
                    article_data = {
                        'title': title,
                        'url': article_url,
                        'date': date_text,
                        'summary': summary,
                        'search_query': getattr(self, 'current_query', '')
                    }
                    
                    articles.append(article_data)
                    
                except Exception as e:
                    logger.warning(f"Error extracting article: {str(e)}")
                    continue
                    
        except Exception as e:
            logger.error(f"Error extracting articles from page: {str(e)}")
            
        return articles
    
    def get_article_content(self, article_url):
        """
        Extract full article content from article URL
        
        Args:
            article_url (str): URL of the article
            
        Returns:
            dict: Article content data
        """
        logger.info(f"Extracting content from: {article_url}")
        
        try:
            response = self.session.get(article_url, timeout=30)
            response.raise_for_status()
            
            soup = BeautifulSoup(response.content, 'html.parser')
            
            # Extract article content using the specified selector
            content_selector = "body > div:nth-child(8) > div > div > div.col-bs10-7 > div > div.col-bs10-7.col-offset-0 > article"
            article_content = soup.select_one(content_selector)
            
            if not article_content:
                # Try alternative selectors
                alternative_selectors = [
                    'article',
                    '.article-content',
                    '.post-content',
                    '.entry-content',
                    'div[class*="content"]',
                    'div[class*="article"]'
                ]
                
                for selector in alternative_selectors:
                    article_content = soup.select_one(selector)
                    if article_content:
                        break
            
            if article_content:
                # Remove unwanted elements (ads, social media buttons, etc.)
                for unwanted in article_content.find_all(['script', 'style', 'iframe', 'ins', 'div[class*="ad"]']):
                    unwanted.decompose()
                
                # Extract text content
                content_text = article_content.get_text(separator='\n', strip=True)
                
                # Extract images
                images = []
                for img in article_content.find_all('img'):
                    src = img.get('src') or img.get('data-src')
                    if src:
                        images.append(urljoin(self.base_url, src))
                
                # Extract author if available
                author_element = soup.find('span', class_='author') or soup.find('div', class_='author')
                author = author_element.get_text(strip=True) if author_element else "Unknown"
                
                # Extract publish date if available
                date_element = soup.find('time') or soup.find('span', class_='date') or soup.find('div', class_='date')
                publish_date = date_element.get_text(strip=True) if date_element else "Unknown"
                
                return {
                    'content': content_text,
                    'images': images,
                    'author': author,
                    'publish_date': publish_date,
                    'url': article_url
                }
            else:
                logger.warning(f"Could not find article content for: {article_url}")
                return None
                
        except Exception as e:
            logger.error(f"Error extracting article content: {str(e)}")
            return None
    
    def scrape_with_full_content(self, query, max_pages=3, max_articles=20):
        """
        Complete scraping process with full article content
        
        Args:
            query (str): Search term
            max_pages (int): Maximum pages to search
            max_articles (int): Maximum articles to process
            
        Returns:
            list: Complete article data with content
        """
        logger.info(f"Starting complete scraping process for query: {query}")
        
        # Store current query for reference
        self.current_query = query
        
        # Search for articles
        articles = self.search_articles(query, max_pages)
        
        # Limit number of articles to process
        articles = articles[:max_articles]
        
        complete_articles = []
        
        for i, article in enumerate(articles, 1):
            logger.info(f"Processing article {i}/{len(articles)}: {article['title']}")
            
            # Get full content
            content_data = self.get_article_content(article['url'])
            
            if content_data:
                # Combine search result data with content data
                complete_article = {**article, **content_data}
                complete_articles.append(complete_article)
            
            # Be respectful to the server
            time.sleep(3)
        
        logger.info(f"Completed scraping {len(complete_articles)} articles with full content")
        return complete_articles
    
    def save_to_json(self, data, filename=None):
        """
        Save scraped data to JSON file
        
        Args:
            data (list): Scraped data
            filename (str): Output filename
        """
        if not filename:
            timestamp = datetime.now().strftime("%Y%m%d_%H%M%S")
            filename = f"radar_surabaya_scraped_{timestamp}.json"
        
        try:
            with open(filename, 'w', encoding='utf-8') as f:
                json.dump(data, f, ensure_ascii=False, indent=2)
            logger.info(f"Data saved to {filename}")
        except Exception as e:
            logger.error(f"Error saving to JSON: {str(e)}")
    
    def save_to_csv(self, data, filename=None):
        """
        Save scraped data to CSV file
        
        Args:
            data (list): Scraped data
            filename (str): Output filename
        """
        if not filename:
            timestamp = datetime.now().strftime("%Y%m%d_%H%M%S")
            filename = f"radar_surabaya_scraped_{timestamp}.csv"
        
        try:
            df = pd.DataFrame(data)
            df.to_csv(filename, index=False, encoding='utf-8')
            logger.info(f"Data saved to {filename}")
        except Exception as e:
            logger.error(f"Error saving to CSV: {str(e)}")
    
    def print_summary(self, articles):
        """
        Print summary of scraped articles
        
        Args:
            articles (list): Scraped articles
        """
        print(f"\n{'='*60}")
        print(f"SCRAPING SUMMARY")
        print(f"{'='*60}")
        print(f"Total articles scraped: {len(articles)}")
        
        if articles:
            print(f"\nSample articles:")
            for i, article in enumerate(articles[:5], 1):
                print(f"{i}. {article.get('title', 'No title')}")
                print(f"   Date: {article.get('date', 'Unknown')}")
                print(f"   URL: {article.get('url', 'No URL')}")
                print(f"   Content length: {len(article.get('content', ''))} characters")
                print()

def main():
    """
    Main function to run the scraper
    """
    print("Radar Surabaya Web Scraper")
    print("=" * 40)
    print("Data Mining Expert & Full Stack Engineer (30 years experience)")
    print("=" * 40)
    
    # Initialize scraper
    scraper = RadarSurabayaScraper()
    
    # Get user input
    query = input("Masukkan kata kunci pencarian (contoh: harga jagung): ").strip()
    
    if not query:
        print("Kata kunci tidak boleh kosong!")
        return
    
    max_pages = input("Jumlah halaman maksimal untuk dicari (default: 3): ").strip()
    max_pages = int(max_pages) if max_pages.isdigit() else 3
    
    max_articles = input("Jumlah artikel maksimal untuk diproses (default: 10): ").strip()
    max_articles = int(max_articles) if max_articles.isdigit() else 10
    
    print(f"\nMemulai scraping untuk: '{query}'")
    print(f"Maksimal {max_pages} halaman, {max_articles} artikel")
    print("-" * 40)
    
    try:
        # Perform complete scraping
        articles = scraper.scrape_with_full_content(query, max_pages, max_articles)
        
        if articles:
            # Print summary
            scraper.print_summary(articles)
            
            # Save results
            print("Menyimpan hasil...")
            scraper.save_to_json(articles)
            scraper.save_to_csv(articles)
            
            print("\nScraping selesai! File hasil telah disimpan.")
            
            # Show sample content
            if articles:
                print(f"\nContoh konten artikel pertama:")
                print("-" * 40)
                first_article = articles[0]
                print(f"Judul: {first_article.get('title', 'No title')}")
                print(f"Tanggal: {first_article.get('date', 'Unknown')}")
                print(f"URL: {first_article.get('url', 'No URL')}")
                print(f"Konten (200 karakter pertama):")
                content = first_article.get('content', '')
                print(content[:200] + "..." if len(content) > 200 else content)
                
        else:
            print("Tidak ada artikel yang ditemukan.")
            
    except KeyboardInterrupt:
        print("\nScraping dihentikan oleh user.")
    except Exception as e:
        print(f"Error: {str(e)}")

if __name__ == "__main__":
    main()