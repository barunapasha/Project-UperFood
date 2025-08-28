# Radar Surabaya Web Scraper - Ultra Simple Version
# Author: Data Mining Expert & Full Stack Engineer (30 years experience)
# Description: Simple and reliable web scraper for https://radarsurabaya.jawapos.com/

# Install packages
!pip install requests beautifulsoup4 pandas

import requests
from bs4 import BeautifulSoup
import time
import json
import pandas as pd
from datetime import datetime
from urllib.parse import quote_plus, urljoin

def simple_scrape_radar(query="harga jagung", max_articles=5):
    """
    Simple scraping function that should work reliably
    """
    print(f"Memulai scraping untuk: '{query}'")
    print("=" * 50)
    
    # Setup
    session = requests.Session()
    base_url = "https://radarsurabaya.jawapos.com"
    
    # Headers
    headers = {
        'User-Agent': 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36'
    }
    session.headers.update(headers)
    
    # Step 1: Search
    print("1. Mencari artikel...")
    encoded_query = quote_plus(query)
    search_url = f"{base_url}/search?q={encoded_query}"
    
    try:
        response = session.get(search_url, timeout=30)
        response.raise_for_status()
        
        soup = BeautifulSoup(response.content, 'html.parser')
        
        # Find all links that might be articles
        all_links = soup.find_all('a', href=True)
        article_links = []
        
        for link in all_links:
            href = link.get('href', '')
            title = link.get_text(strip=True)
            
            # Check if this looks like an article link
            if (any(keyword in href for keyword in ['/news/', '/article/', '/read/', '/detail/']) and 
                len(title) > 10 and 
                not any(skip in title.lower() for skip in ['login', 'register', 'advertisement', 'iklan'])):
                
                article_links.append({
                    'title': title,
                    'url': urljoin(base_url, href)
                })
        
        print(f"   Ditemukan {len(article_links)} link artikel")
        
        # Step 2: Get content
        print("\n2. Mengambil konten artikel...")
        articles = []
        
        for i, link_data in enumerate(article_links[:max_articles], 1):
            print(f"   Processing {i}/{min(len(article_links), max_articles)}: {link_data['title'][:50]}...")
            
            try:
                response = session.get(link_data['url'], timeout=30)
                response.raise_for_status()
                
                soup = BeautifulSoup(response.content, 'html.parser')
                
                # Try to find content
                content = ""
                
                # Method 1: Look for article tag
                article_tag = soup.find('article')
                if article_tag:
                    content = article_tag.get_text(strip=True)
                
                # Method 2: Look for content divs
                if not content:
                    content_divs = soup.find_all('div', class_=lambda x: x and any(word in x.lower() for word in ['content', 'article', 'post', 'body']))
                    for div in content_divs:
                        text = div.get_text(strip=True)
                        if len(text) > 200:  # Substantial content
                            content = text
                            break
                
                # Method 3: Look for any div with substantial text
                if not content:
                    all_divs = soup.find_all('div')
                    for div in all_divs:
                        text = div.get_text(strip=True)
                        if len(text) > 500:  # Substantial content
                            content = text
                            break
                
                # Clean content
                if content:
                    # Remove excessive whitespace
                    content = ' '.join(content.split())
                    
                    article_data = {
                        'title': link_data['title'],
                        'url': link_data['url'],
                        'content': content,
                        'content_length': len(content),
                        'search_query': query,
                        'scraped_at': datetime.now().strftime("%Y-%m-%d %H:%M:%S")
                    }
                    
                    articles.append(article_data)
                    print(f"   ✓ Berhasil ({len(content)} karakter)")
                else:
                    print(f"   ✗ Tidak ada konten ditemukan")
                
            except Exception as e:
                print(f"   ✗ Error: {str(e)}")
                continue
            
            time.sleep(2)  # Be respectful
        
        return articles
        
    except Exception as e:
        print(f"Error: {str(e)}")
        return []

def save_data(articles, query):
    """Save data to files"""
    if not articles:
        print("Tidak ada data untuk disimpan")
        return
    
    timestamp = datetime.now().strftime("%Y%m%d_%H%M%S")
    
    # Save JSON
    json_file = f"radar_{query.replace(' ', '_')}_{timestamp}.json"
    with open(json_file, 'w', encoding='utf-8') as f:
        json.dump(articles, f, ensure_ascii=False, indent=2)
    print(f"JSON saved: {json_file}")
    
    # Save CSV
    csv_file = f"radar_{query.replace(' ', '_')}_{timestamp}.csv"
    df = pd.DataFrame(articles)
    df.to_csv(csv_file, index=False, encoding='utf-8')
    print(f"CSV saved: {csv_file}")
    
    return json_file, csv_file

def print_results(articles):
    """Print results summary"""
    print(f"\n{'='*60}")
    print(f"HASIL SCRAPING")
    print(f"{'='*60}")
    print(f"Total artikel: {len(articles)}")
    
    if articles:
        print(f"\nContoh artikel:")
        for i, article in enumerate(articles[:3], 1):
            print(f"{i}. {article['title']}")
            print(f"   URL: {article['url']}")
            print(f"   Konten: {article['content_length']} karakter")
            print(f"   Preview: {article['content'][:100]}...")
            print()

# ============================================================================
# MAIN EXECUTION
# ============================================================================

print("Radar Surabaya Web Scraper - Ultra Simple Version")
print("Data Mining Expert & Full Stack Engineer (30 years experience)")
print("=" * 60)

# Configuration
SEARCH_QUERY = "harga jagung"  # Change this to your search term
MAX_ARTICLES = 5  # Change this to number of articles you want

# Run scraper
articles = simple_scrape_radar(SEARCH_QUERY, MAX_ARTICLES)

if articles:
    # Show results
    print_results(articles)
    
    # Save data
    print("Menyimpan data...")
    json_file, csv_file = save_data(articles, SEARCH_QUERY)
    
    print(f"\nScraping selesai!")
    print(f"File JSON: {json_file}")
    print(f"File CSV: {csv_file}")
    
else:
    print("Tidak ada artikel yang ditemukan.")
    print("Coba kata kunci yang berbeda atau periksa koneksi internet.")

# ============================================================================
# ALTERNATIVE SEARCHES
# ============================================================================

# Uncomment lines below to try different searches:

# articles = simple_scrape_radar("inflasi", 3)
# save_data(articles, "inflasi")

# articles = simple_scrape_radar("ekonomi", 3)
# save_data(articles, "ekonomi")

# articles = simple_scrape_radar("politik", 3)
# save_data(articles, "politik")