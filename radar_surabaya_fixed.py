# Radar Surabaya Web Scraper - Fixed Version for Google Colab
# Author: Data Mining Expert & Full Stack Engineer (30 years experience)
# Description: Complete web scraper for https://radarsurabaya.jawapos.com/

# Install required packages first
!pip install requests beautifulsoup4 pandas lxml urllib3

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

def scrape_radar_surabaya(query, max_pages=3, max_articles=10):
    """
    Complete scraping function for Radar Surabaya
    
    Args:
        query (str): Search term
        max_pages (int): Maximum pages to search
        max_articles (int): Maximum articles to process
        
    Returns:
        list: Complete article data with content
    """
    
    # Initialize session
    session = requests.Session()
    base_url = "https://radarsurabaya.jawapos.com"
    
    # Set headers to mimic real browser
    headers = {
        'User-Agent': 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
        'Accept': 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
        'Accept-Language': 'en-US,en;q=0.5',
        'Accept-Encoding': 'gzip, deflate',
        'Connection': 'keep-alive',
        'Upgrade-Insecure-Requests': '1',
        'Cache-Control': 'max-age=0'
    }
    session.headers.update(headers)
    
    print(f"Memulai scraping untuk: '{query}'")
    print(f"Maksimal {max_pages} halaman, {max_articles} artikel")
    print("-" * 40)
    
    # Step 1: Search for articles
    print("1. Mencari artikel...")
    encoded_query = quote_plus(query)
    search_url = f"{base_url}/search?q={encoded_query}"
    
    articles = []
    page = 1
    
    while page <= max_pages:
        print(f"   Scraping halaman {page}...")
        
        # Add page parameter if not first page
        if page > 1:
            page_url = f"{search_url}&page={page}"
        else:
            page_url = search_url
        
        try:
            # Get search results page
            response = session.get(page_url, timeout=30)
            response.raise_for_status()
            
            soup = BeautifulSoup(response.content, 'html.parser')
            
            # Debug: Print page title to verify we're on the right page
            page_title = soup.find('title')
            if page_title:
                print(f"   Halaman: {page_title.get_text()[:50]}...")
            
            # Find article containers with multiple selectors
            article_containers = soup.find_all('div', class_='latest__wrap')
            
            if not article_containers:
                # Try alternative selectors
                article_containers = soup.find_all('article') or soup.find_all('div', class_='post')
            
            if not article_containers:
                # Try more generic selectors
                article_containers = soup.find_all('div', class_=re.compile(r'post|article|news|item'))
            
            print(f"   Ditemukan {len(article_containers)} container artikel")
            
            if not article_containers:
                print(f"   Tidak ada artikel ditemukan di halaman {page}")
                # Try to find any links that might be articles
                all_links = soup.find_all('a', href=True)
                article_links = [link for link in all_links if '/news/' in link.get('href', '') or '/article/' in link.get('href', '')]
                print(f"   Mencoba mencari link artikel: {len(article_links)} ditemukan")
                
                if article_links:
                    for link in article_links[:10]:  # Limit to first 10
                        title = link.get_text(strip=True)
                        if title and len(title) > 10:  # Only if title is meaningful
                            article_data = {
                                'title': title,
                                'url': urljoin(base_url, link.get('href')),
                                'date': "Unknown",
                                'summary': "",
                                'search_query': query
                            }
                            articles.append(article_data)
                break
            
            # Extract articles from current page
            for container in article_containers:
                try:
                    # Extract title and link
                    title_element = None
                    title_selectors = ['h2 a', 'h1 a', 'h3 a', 'h4 a', '.title a', '.headline a', 'a']
                    
                    for title_sel in title_selectors:
                        title_element = container.select_one(title_sel)
                        if title_element and title_element.get('href'):
                            break
                    
                    if not title_element or not title_element.get('href'):
                        continue
                        
                    title = title_element.get_text(strip=True)
                    if not title or len(title) < 5:  # Skip if title is too short
                        continue
                        
                    article_url = urljoin(base_url, title_element.get('href'))
                    
                    # Skip if URL doesn't look like an article
                    if not any(keyword in article_url for keyword in ['/news/', '/article/', '/read/', '/detail/']):
                        continue
                    
                    # Extract date
                    date_text = "Unknown"
                    date_selectors = ['date', 'time', 'span.date', 'div.date', '.publish-date', '.post-date']
                    
                    for date_sel in date_selectors:
                        date_element = container.select_one(date_sel)
                        if date_element:
                            date_text = date_element.get_text(strip=True)
                            break
                    
                    # Extract summary
                    summary = ""
                    summary_selectors = ['p', '.excerpt', '.summary', '.description']
                    for summary_sel in summary_selectors:
                        summary_element = container.select_one(summary_sel)
                        if summary_element:
                            summary = summary_element.get_text(strip=True)
                            break
                    
                    article_data = {
                        'title': title,
                        'url': article_url,
                        'date': date_text,
                        'summary': summary,
                        'search_query': query
                    }
                    
                    articles.append(article_data)
                    print(f"   Artikel ditemukan: {title[:50]}...")
                    
                except Exception as e:
                    print(f"   Error extracting article: {str(e)}")
                    continue
            
            # Check if there are more pages
            next_page = soup.find('a', {'rel': 'next'}) or soup.find('a', string=re.compile(r'next', re.I))
            if not next_page:
                print("   Tidak ada halaman selanjutnya")
                break
            
            page += 1
            time.sleep(2)  # Be respectful to the server
            
        except Exception as e:
            print(f"   Error during search: {str(e)}")
            break
    
    print(f"   Total ditemukan {len(articles)} artikel")
    
    # Step 2: Get full content for each article
    print("\n2. Mengambil konten lengkap artikel...")
    complete_articles = []
    articles = articles[:max_articles]  # Limit articles
    
    for i, article in enumerate(articles, 1):
        print(f"   Processing artikel {i}/{len(articles)}: {article['title'][:50]}...")
        
        try:
            # Get article content
            response = session.get(article['url'], timeout=30)
            response.raise_for_status()
            
            soup = BeautifulSoup(response.content, 'html.parser')
            
            # Try multiple content selectors
            content_selectors = [
                "body > div:nth-child(8) > div > div > div.col-bs10-7 > div > div.col-bs10-7.col-offset-0 > article",
                'article',
                '.article-content',
                '.post-content',
                '.entry-content',
                'div[class*="content"]',
                'div[class*="article"]',
                '.main-content',
                '.content-body',
                '.post-body',
                '.news-content'
            ]
            
            article_content = None
            for selector in content_selectors:
                article_content = soup.select_one(selector)
                if article_content:
                    break
            
            if not article_content:
                # Try to find any div with substantial text content
                all_divs = soup.find_all('div')
                for div in all_divs:
                    text = div.get_text(strip=True)
                    if len(text) > 500:  # If div has substantial text
                        article_content = div
                        break
            
            if article_content:
                # Remove unwanted elements
                for unwanted in article_content.find_all(['script', 'style', 'iframe', 'ins', 'div[class*="ad"]']):
                    unwanted.decompose()
                
                # Extract text content
                content_text = article_content.get_text(separator='\n', strip=True)
                
                # Clean up content
                content_text = re.sub(r'\n\s*\n', '\n\n', content_text)  # Remove excessive newlines
                content_text = re.sub(r'\s+', ' ', content_text)  # Normalize whitespace
                
                # Extract images
                images = []
                for img in article_content.find_all('img'):
                    src = img.get('src') or img.get('data-src') or img.get('data-lazy-src')
                    if src:
                        images.append(urljoin(base_url, src))
                
                # Extract author
                author = "Unknown"
                author_selectors = ['.author', 'span.author', 'div.author', '.byline', '.writer']
                for author_sel in author_selectors:
                    author_element = soup.select_one(author_sel)
                    if author_element:
                        author = author_element.get_text(strip=True)
                        break
                
                # Extract publish date
                publish_date = "Unknown"
                date_selectors = ['time', 'span.date', 'div.date', '.publish-date', '.post-date', '.article-date']
                for date_sel in date_selectors:
                    date_element = soup.select_one(date_sel)
                    if date_element:
                        publish_date = date_element.get_text(strip=True)
                        break
                
                # Combine data
                complete_article = {
                    **article,
                    'content': content_text,
                    'images': images,
                    'author': author,
                    'publish_date': publish_date
                }
                
                complete_articles.append(complete_article)
                print(f"   ✓ Konten berhasil diambil ({len(content_text)} karakter)")
            else:
                print(f"   ✗ Tidak dapat menemukan konten untuk: {article['title']}")
            
        except Exception as e:
            print(f"   ✗ Error extracting content: {str(e)}")
            continue
        
        time.sleep(3)  # Be respectful to the server
    
    print(f"   Berhasil mengambil konten {len(complete_articles)} artikel")
    return complete_articles

def save_results(articles, query):
    """Save results to files"""
    timestamp = datetime.now().strftime("%Y%m%d_%H%M%S")
    
    # Save to JSON
    json_filename = f"radar_surabaya_{query.replace(' ', '_')}_{timestamp}.json"
    with open(json_filename, 'w', encoding='utf-8') as f:
        json.dump(articles, f, ensure_ascii=False, indent=2)
    print(f"Data disimpan ke: {json_filename}")
    
    # Save to CSV
    csv_filename = f"radar_surabaya_{query.replace(' ', '_')}_{timestamp}.csv"
    df = pd.DataFrame(articles)
    df.to_csv(csv_filename, index=False, encoding='utf-8')
    print(f"Data disimpan ke: {csv_filename}")
    
    return json_filename, csv_filename

def print_summary(articles):
    """Print summary of scraped articles"""
    print(f"\n{'='*60}")
    print(f"RINGKASAN SCRAPING")
    print(f"{'='*60}")
    print(f"Total artikel: {len(articles)}")
    
    if articles:
        print(f"\nContoh artikel:")
        for i, article in enumerate(articles[:3], 1):
            print(f"{i}. {article.get('title', 'No title')}")
            print(f"   Tanggal: {article.get('date', 'Unknown')}")
            print(f"   URL: {article.get('url', 'No URL')}")
            print(f"   Panjang konten: {len(article.get('content', ''))} karakter")
            print()

# ============================================================================
# CONFIGURATION - UBAH PARAMETER DI SINI
# ============================================================================

# Kata kunci pencarian
SEARCH_QUERY = "harga jagung"  # Ganti dengan kata kunci yang diinginkan

# Jumlah halaman maksimal untuk dicari
MAX_PAGES = 3

# Jumlah artikel maksimal untuk diproses
MAX_ARTICLES = 10

# ============================================================================
# EXECUTION - JALANKAN SCRAPING
# ============================================================================

print("Radar Surabaya Web Scraper - Fixed Version")
print("=" * 50)
print("Data Mining Expert & Full Stack Engineer (30 years experience)")
print("=" * 50)

try:
    # Perform scraping
    articles = scrape_radar_surabaya(SEARCH_QUERY, MAX_PAGES, MAX_ARTICLES)
    
    if articles:
        # Print summary
        print_summary(articles)
        
        # Save results
        print("Menyimpan hasil...")
        json_file, csv_file = save_results(articles, SEARCH_QUERY)
        
        print("\nScraping selesai!")
        print(f"File JSON: {json_file}")
        print(f"File CSV: {csv_file}")
        
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
        print("Coba kata kunci yang berbeda atau periksa koneksi internet.")
        
except Exception as e:
    print(f"Error: {str(e)}")
    print("Coba jalankan ulang atau periksa koneksi internet.")

# ============================================================================
# ALTERNATIVE USAGE - JALANKAN FUNGSI LANGSUNG
# ============================================================================

# Jika ingin menjalankan dengan kata kunci berbeda, uncomment dan ganti parameter:

# articles = scrape_radar_surabaya("inflasi", max_pages=2, max_articles=5)
# save_results(articles, "inflasi")

# articles = scrape_radar_surabaya("ekonomi", max_pages=3, max_articles=8)
# save_results(articles, "ekonomi")

# articles = scrape_radar_surabaya("politik", max_pages=2, max_articles=6)
# save_results(articles, "politik")