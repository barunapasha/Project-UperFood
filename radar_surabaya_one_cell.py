# Radar Surabaya Web Scraper - One Cell Version for Google Colab
# Author: Data Mining Expert & Full Stack Engineer (30 years experience)
# Description: Complete web scraper for https://radarsurabaya.jawapos.com/

# Install required packages
!pip install requests beautifulsoup4 pandas lxml urllib3

import requests
from bs4 import BeautifulSoup
import time
import json
import re
from urllib.parse import quote_plus, urljoin
import pandas as pd
from datetime import datetime

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
            
            # Find article containers with multiple selectors
            article_containers = soup.find_all('div', class_='latest__wrap')
            
            if not article_containers:
                # Try alternative selectors
                article_containers = soup.find_all('article') or soup.find_all('div', class_='post')
            
            if not article_containers:
                print(f"   Tidak ada artikel ditemukan di halaman {page}")
                break
            
            # Extract articles from current page
            for container in article_containers:
                try:
                    # Extract title and link
                    title_element = None
                    title_selectors = ['h2 a', 'h1 a', 'h3 a', 'h4 a', '.title a', '.headline a']
                    
                    for title_sel in title_selectors:
                        title_element = container.select_one(title_sel)
                        if title_element:
                            break
                    
                    if not title_element:
                        continue
                        
                    title = title_element.get_text(strip=True)
                    article_url = urljoin(base_url, title_element.get('href', ''))
                    
                    # Extract date
                    date_text = "Unknown"
                    date_selectors = ['date', 'time', 'span.date', 'div.date', '.publish-date']
                    
                    for date_sel in date_selectors:
                        date_element = container.select_one(date_sel)
                        if date_element:
                            date_text = date_element.get_text(strip=True)
                            break
                    
                    # Extract summary
                    summary = ""
                    summary_element = container.select_one('p') or container.select_one('.excerpt')
                    if summary_element:
                        summary = summary_element.get_text(strip=True)
                    
                    article_data = {
                        'title': title,
                        'url': article_url,
                        'date': date_text,
                        'summary': summary,
                        'search_query': query
                    }
                    
                    articles.append(article_data)
                    
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
    
    print(f"   Ditemukan {len(articles)} artikel")
    
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
                'div[class*="article"]'
            ]
            
            article_content = None
            for selector in content_selectors:
                article_content = soup.select_one(selector)
                if article_content:
                    break
            
            if article_content:
                # Remove unwanted elements
                for unwanted in article_content.find_all(['script', 'style', 'iframe', 'ins', 'div[class*="ad"]']):
                    unwanted.decompose()
                
                # Extract text content
                content_text = article_content.get_text(separator='\n', strip=True)
                
                # Extract images
                images = []
                for img in article_content.find_all('img'):
                    src = img.get('src') or img.get('data-src')
                    if src:
                        images.append(urljoin(base_url, src))
                
                # Extract author
                author = "Unknown"
                author_element = soup.select_one('.author') or soup.select_one('span.author')
                if author_element:
                    author = author_element.get_text(strip=True)
                
                # Extract publish date
                publish_date = "Unknown"
                date_element = soup.select_one('time') or soup.select_one('span.date')
                if date_element:
                    publish_date = date_element.get_text(strip=True)
                
                # Combine data
                complete_article = {
                    **article,
                    'content': content_text,
                    'images': images,
                    'author': author,
                    'publish_date': publish_date
                }
                
                complete_articles.append(complete_article)
            else:
                print(f"   Tidak dapat menemukan konten untuk: {article['title']}")
            
        except Exception as e:
            print(f"   Error extracting content: {str(e)}")
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

print("Radar Surabaya Web Scraper - One Cell Version")
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
        
except Exception as e:
    print(f"Error: {str(e)}")

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