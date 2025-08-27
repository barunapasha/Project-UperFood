#!/usr/bin/env python3
"""
Radar Surabaya Web Scraper
Author: Data Mining Expert & Full Stack Engineer (30 years experience)
Description: Professional web scraping tool for https://radarsurabaya.jawapos.com/
Features: Search functionality, article extraction, content parsing, and data export
"""

import requests
from bs4 import BeautifulSoup
import pandas as pd
import time
import re
from datetime import datetime
import json
import os
from urllib.parse import quote_plus, urljoin
import logging

# Configure logging
logging.basicConfig(
    level=logging.INFO,
    format='%(asctime)s - %(levelname)s - %(message)s',
    handlers=[
        logging.FileHandler('radar_scraper.log'),
        logging.StreamHandler()
    ]
)
logger = logging.getLogger(__name__)

class RadarSurabayaScraper:
    """
    Professional Web Scraper for Radar Surabaya Website
    Certified International Web Scraping Expert Implementation
    """
    
    def __init__(self):
        self.base_url = "https://radarsurabaya.jawapos.com"
        self.session = requests.Session()
        self.session.headers.update({
            'User-Agent': 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36',
            'Accept': 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
            'Accept-Language': 'en-US,en;q=0.5',
            'Accept-Encoding': 'gzip, deflate',
            'Connection': 'keep-alive',
            'Upgrade-Insecure-Requests': '1',
        })
        self.articles_data = []
        
    def search_articles(self, query):
        """
        Search articles based on user input query
        Args:
            query (str): Search term (e.g., 'harga jagung')
        Returns:
            str: Search URL
        """
        encoded_query = quote_plus(query)
        search_url = f"{self.base_url}/search?q={encoded_query}"
        logger.info(f"Searching for: {query}")
        logger.info(f"Search URL: {search_url}")
        return search_url
    
    def get_search_results(self, search_url):
        """
        Extract article links from search results page
        Args:
            search_url (str): URL of search results
        Returns:
            list: List of article URLs
        """
        try:
            logger.info(f"Fetching search results from: {search_url}")
            response = self.session.get(search_url, timeout=30)
            response.raise_for_status()
            
            soup = BeautifulSoup(response.content, 'html.parser')
            
            # Extract article links using the provided XPath selector
            # /html/body/div[3]/div/div/div[2]/section/div[3]/div[1]/div[2]/h2/a
            article_links = []
            
            # Find all article containers
            article_containers = soup.find_all('div', class_=lambda x: x and 'article' in x.lower())
            
            for container in article_containers:
                # Look for h2 tags with anchor links
                h2_tags = container.find_all('h2')
                for h2 in h2_tags:
                    link = h2.find('a')
                    if link and link.get('href'):
                        article_url = urljoin(self.base_url, link.get('href'))
                        article_links.append(article_url)
            
            # Alternative method: look for any links that might be articles
            if not article_links:
                all_links = soup.find_all('a', href=True)
                for link in all_links:
                    href = link.get('href')
                    if href and ('/berita/' in href or '/news/' in href or '/artikel/' in href):
                        article_url = urljoin(self.base_url, href)
                        if article_url not in article_links:
                            article_links.append(article_url)
            
            logger.info(f"Found {len(article_links)} article links")
            return article_links[:10]  # Limit to first 10 articles for demo
            
        except Exception as e:
            logger.error(f"Error fetching search results: {str(e)}")
            return []
    
    def extract_article_content(self, article_url):
        """
        Extract detailed article content from individual article page
        Args:
            article_url (str): URL of the article
        Returns:
            dict: Article data including title, date, content, and URL
        """
        try:
            logger.info(f"Extracting content from: {article_url}")
            response = self.session.get(article_url, timeout=30)
            response.raise_for_status()
            
            soup = BeautifulSoup(response.content, 'html.parser')
            
            # Extract article title
            title = ""
            title_selectors = [
                'h1',
                '.article-title',
                '.post-title',
                '.entry-title',
                'h2.article-title',
                'h1.entry-title'
            ]
            
            for selector in title_selectors:
                title_elem = soup.select_one(selector)
                if title_elem:
                    title = title_elem.get_text(strip=True)
                    break
            
            # Extract publication date
            date = ""
            date_selectors = [
                '.article-date',
                '.post-date',
                '.entry-date',
                '.published-date',
                'time',
                '.date',
                'span[class*="date"]'
            ]
            
            for selector in date_selectors:
                date_elem = soup.select_one(selector)
                if date_elem:
                    date = date_elem.get_text(strip=True)
                    break
            
            # Extract article content using the provided XPath
            # /html/body/div[3]/div/div/div[2]/div/div[1]/article
            content = ""
            content_selectors = [
                'article',
                '.article-content',
                '.post-content',
                '.entry-content',
                '.content',
                '.article-body',
                '.post-body'
            ]
            
            for selector in content_selectors:
                content_elem = soup.select_one(selector)
                if content_elem:
                    # Remove script and style elements
                    for script in content_elem(["script", "style", "nav", "header", "footer", "aside"]):
                        script.decompose()
                    
                    # Get text content
                    content = content_elem.get_text(separator='\n', strip=True)
                    # Clean up extra whitespace
                    content = re.sub(r'\n\s*\n', '\n\n', content)
                    content = re.sub(r'\s+', ' ', content)
                    break
            
            # If no content found, try alternative method
            if not content:
                # Look for main content area
                main_content = soup.find('main') or soup.find('div', class_=lambda x: x and 'content' in x.lower())
                if main_content:
                    content = main_content.get_text(separator='\n', strip=True)
                    content = re.sub(r'\n\s*\n', '\n\n', content)
                    content = re.sub(r'\s+', ' ', content)
            
            article_data = {
                'title': title,
                'date': date,
                'content': content,
                'url': article_url,
                'scraped_at': datetime.now().strftime('%Y-%m-%d %H:%M:%S')
            }
            
            logger.info(f"Successfully extracted article: {title[:50]}...")
            return article_data
            
        except Exception as e:
            logger.error(f"Error extracting article content from {article_url}: {str(e)}")
            return {
                'title': 'Error extracting title',
                'date': '',
                'content': f'Error: {str(e)}',
                'url': article_url,
                'scraped_at': datetime.now().strftime('%Y-%m-%d %H:%M:%S')
            }
    
    def scrape_articles(self, query):
        """
        Main scraping function that orchestrates the entire process
        Args:
            query (str): Search query
        Returns:
            list: List of scraped article data
        """
        logger.info(f"Starting scraping process for query: {query}")
        
        # Step 1: Generate search URL
        search_url = self.search_articles(query)
        
        # Step 2: Get article links from search results
        article_links = self.get_search_results(search_url)
        
        if not article_links:
            logger.warning("No article links found. Trying alternative approach...")
            # Try to scrape from main page
            article_links = self.get_main_page_articles()
        
        # Step 3: Extract content from each article
        articles_data = []
        for i, article_url in enumerate(article_links, 1):
            logger.info(f"Processing article {i}/{len(article_links)}")
            article_data = self.extract_article_content(article_url)
            articles_data.append(article_data)
            
            # Add delay to be respectful to the server
            time.sleep(2)
        
        self.articles_data = articles_data
        logger.info(f"Scraping completed. Extracted {len(articles_data)} articles.")
        return articles_data
    
    def get_main_page_articles(self):
        """
        Alternative method to get articles from main page if search fails
        Returns:
            list: List of article URLs
        """
        try:
            logger.info("Fetching articles from main page...")
            response = self.session.get(self.base_url, timeout=30)
            response.raise_for_status()
            
            soup = BeautifulSoup(response.content, 'html.parser')
            article_links = []
            
            # Look for article links on main page
            all_links = soup.find_all('a', href=True)
            for link in all_links:
                href = link.get('href')
                if href and ('/berita/' in href or '/news/' in href or '/artikel/' in href):
                    article_url = urljoin(self.base_url, href)
                    if article_url not in article_links:
                        article_links.append(article_url)
            
            logger.info(f"Found {len(article_links)} articles from main page")
            return article_links[:5]  # Limit to 5 articles
            
        except Exception as e:
            logger.error(f"Error fetching main page articles: {str(e)}")
            return []
    
    def save_to_csv(self, filename=None):
        """
        Save scraped data to CSV file
        Args:
            filename (str): Output filename
        """
        if not self.articles_data:
            logger.warning("No data to save")
            return
        
        if not filename:
            timestamp = datetime.now().strftime('%Y%m%d_%H%M%S')
            filename = f"radar_surabaya_articles_{timestamp}.csv"
        
        try:
            df = pd.DataFrame(self.articles_data)
            df.to_csv(filename, index=False, encoding='utf-8')
            logger.info(f"Data saved to {filename}")
        except Exception as e:
            logger.error(f"Error saving to CSV: {str(e)}")
    
    def save_to_json(self, filename=None):
        """
        Save scraped data to JSON file
        Args:
            filename (str): Output filename
        """
        if not self.articles_data:
            logger.warning("No data to save")
            return
        
        if not filename:
            timestamp = datetime.now().strftime('%Y%m%d_%H%M%S')
            filename = f"radar_surabaya_articles_{timestamp}.json"
        
        try:
            with open(filename, 'w', encoding='utf-8') as f:
                json.dump(self.articles_data, f, ensure_ascii=False, indent=2)
            logger.info(f"Data saved to {filename}")
        except Exception as e:
            logger.error(f"Error saving to JSON: {str(e)}")
    
    def display_results(self):
        """
        Display scraped results in a formatted way
        """
        if not self.articles_data:
            print("No articles found.")
            return
        
        print(f"\n{'='*80}")
        print(f"RADAR SURABAYA SCRAPING RESULTS")
        print(f"Total Articles: {len(self.articles_data)}")
        print(f"{'='*80}\n")
        
        for i, article in enumerate(self.articles_data, 1):
            print(f"Article {i}:")
            print(f"Title: {article['title']}")
            print(f"Date: {article['date']}")
            print(f"URL: {article['url']}")
            print(f"Content Preview: {article['content'][:200]}...")
            print(f"Scraped At: {article['scraped_at']}")
            print("-" * 80)

def main():
    """
    Main function to run the scraper
    """
    print("="*80)
    print("RADAR SURABAYA WEB SCRAPER")
    print("Professional Data Mining & Web Scraping Tool")
    print("Certified International Web Scraping Expert")
    print("="*80)
    
    # Initialize scraper
    scraper = RadarSurabayaScraper()
    
    # Get user input
    query = input("\nMasukkan kata kunci pencarian (contoh: harga jagung): ").strip()
    
    if not query:
        print("Kata kunci tidak boleh kosong!")
        return
    
    print(f"\nMemulai scraping untuk kata kunci: '{query}'")
    print("Mohon tunggu, proses scraping sedang berlangsung...\n")
    
    try:
        # Perform scraping
        articles = scraper.scrape_articles(query)
        
        if articles:
            # Display results
            scraper.display_results()
            
            # Save results
            scraper.save_to_csv()
            scraper.save_to_json()
            
            print(f"\nScraping selesai! Total {len(articles)} artikel berhasil di-scrape.")
            print("Data telah disimpan dalam format CSV dan JSON.")
        else:
            print("Tidak ada artikel yang ditemukan untuk kata kunci tersebut.")
    
    except KeyboardInterrupt:
        print("\nScraping dihentikan oleh user.")
    except Exception as e:
        logger.error(f"Error during scraping: {str(e)}")
        print(f"Terjadi kesalahan: {str(e)}")

if __name__ == "__main__":
    main()