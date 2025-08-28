# Radar Surabaya Web Scraper - Fixed Version for Google Colab
# Author: Data Mining Expert & Full Stack Engineer (30 years experience)
# Description: Complete web scraper for https://radarsurabaya.jawapos.com/

# Install required packages first
!pip install requests beautifulsoup4 pandas lxml urllib3 cloudscraper

import time
import json
import re
from urllib.parse import quote_plus, urljoin
from datetime import datetime
import logging
import pandas as pd
import random

import cloudscraper
from bs4 import BeautifulSoup

# Setup logging
logging.basicConfig(level=logging.INFO, format='%(asctime)s - %(levelname)s - %(message)s')
logger = logging.getLogger(__name__)

BASE_URL = "https://radarsurabaya.jawapos.com"

# A small pool of realistic desktop Chrome user agents
USER_AGENTS = [
	"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/126.0.0.0 Safari/537.36",
	"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/125.0.0.0 Safari/537.36",
	"Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:127.0) Gecko/20100101 Firefox/127.0",
]

DEFAULT_HEADERS = {
	"accept": "text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,*/*;q=0.8",
	"accept-language": "id,en-US;q=0.9,en;q=0.8",
	"cache-control": "no-cache",
	"pragma": "no-cache",
	"upgrade-insecure-requests": "1",
	"sec-fetch-dest": "document",
	"sec-fetch-mode": "navigate",
	"sec-fetch-site": "same-origin",
	"sec-fetch-user": "?1",
}


def _create_scraper():
	"""Create a cloudscraper session with realistic headers and HTTP/2 support."""
	scraper = cloudscraper.create_scraper(
		browser={"browser": "chrome", "platform": "windows", "mobile": False},
		delay=5,
	)
	# Rotate UA
	ua = random.choice(USER_AGENTS)
	scraper.headers.update({"user-agent": ua, **DEFAULT_HEADERS})
	return scraper


def _warm_cookies(scraper):
	"""Warm up cookies by visiting the homepage and a couple of internal pages."""
	for path in ["/", "/berita", "/news"]:
		try:
			resp = scraper.get(urljoin(BASE_URL, path), timeout=30)
			if resp.status_code == 200:
				return True
			time.sleep(2)
		except Exception:
			time.sleep(2)
	return False


def _get_with_retry(scraper, url, max_retries=5, backoff_base=2, referer=BASE_URL):
	"""GET with retry/backoff for 403/429 and transient errors."""
	for attempt in range(1, max_retries + 1):
		try:
			headers = {"referer": referer}
			resp = scraper.get(url, timeout=45, headers=headers)
			if resp.status_code == 200:
				return resp
			if resp.status_code in (403, 429):
				# Rotate UA and wait
				scraper.headers.update({"user-agent": random.choice(USER_AGENTS)})
				delay = backoff_base ** attempt + random.uniform(0.5, 1.5)
				logger.info(f"Got {resp.status_code}. Backing off {delay:.1f}s and retrying...")
				time.sleep(delay)
				continue
			# For other 5xx, retry as well
			if 500 <= resp.status_code < 600:
				delay = backoff_base ** attempt + random.uniform(0.5, 1.5)
				logger.info(f"Got {resp.status_code}. Backing off {delay:.1f}s and retrying...")
				time.sleep(delay)
				continue
			# Non-retryable
			resp.raise_for_status()
			return resp
		except Exception as e:
			delay = backoff_base ** attempt + random.uniform(0.5, 1.5)
			logger.info(f"Request error on attempt {attempt}: {e}. Sleeping {delay:.1f}s...")
			time.sleep(delay)
	# Final attempt without raising
	return None


def scrape_radar_surabaya(query, max_pages=3, max_articles=10):
	"""
	Complete scraping function for Radar Surabaya with anti-403 hardening
	"""
	print(f"Memulai scraping untuk: '{query}'")
	print(f"Maksimal {max_pages} halaman, {max_articles} artikel")
	print("-" * 40)

	# Create hardened scraper and warm cookies
	scraper = _create_scraper()
	_warm_cookies(scraper)

	encoded_query = quote_plus(query)
	search_url = f"{BASE_URL}/search?q={encoded_query}"

	articles = []
	page = 1

	while page <= max_pages:
		print(f"   Scraping halaman {page}...")
		page_url = f"{search_url}&page={page}" if page > 1 else search_url

		resp = _get_with_retry(scraper, page_url, referer=BASE_URL)
		if not resp:
			print(f"   Error mengakses halaman pencarian (403/429). Coba lagi nanti.")
			break

		soup = BeautifulSoup(resp.content, 'lxml')

		# Debug page title
		title_tag = soup.find('title')
		if title_tag:
			logger.info(f"Halaman: {title_tag.get_text(strip=True)[:80]}")

		# Try multiple containers
		containers = soup.select('div.latest__wrap')
		if not containers:
			containers = soup.select('article, div.post, div.news-item, div.article-item')
		if not containers:
			containers = soup.find_all('div', class_=re.compile(r'post|article|news|item', re.I))

		print(f"   Ditemukan {len(containers)} container artikel")
		if not containers:
			break

		for container in containers:
			try:
				title_el = None
				for sel in ['h2 a', 'h3 a', 'h1 a', 'h4 a', '.title a', '.headline a', 'a']:
					title_el = container.select_one(sel)
					if title_el and title_el.get('href'):
						break
				if not title_el or not title_el.get('href'):
					continue

				title = title_el.get_text(strip=True)
				if not title or len(title) < 5:
					continue
				url = urljoin(BASE_URL, title_el.get('href'))
				if not any(k in url for k in ['/news/', '/article/', '/read/', '/detail/']):
					continue

				date_text = "Unknown"
				for dsel in ['date', 'time', 'span.date', 'div.date', '.publish-date', '.post-date']:
					de = container.select_one(dsel)
					if de:
						date_text = de.get_text(strip=True)
						break

				summary = ""
				for ssel in ['p', '.excerpt', '.summary', '.description']:
					se = container.select_one(ssel)
					if se:
						summary = se.get_text(strip=True)
						break

				articles.append({
					'title': title,
					'url': url,
					'date': date_text,
					'summary': summary,
					'search_query': query
				})
			except Exception:
				continue

		# Pagination detection (best-effort)
		next_link = soup.find('a', {'rel': 'next'}) or soup.find('a', string=re.compile(r'next', re.I))
		if not next_link:
			print("   Tidak ada halaman selanjutnya")
			break
		page += 1
		time.sleep(2 + random.uniform(0.2, 0.8))

	print(f"   Total ditemukan {len(articles)} artikel")

	# Fetch contents
	print("\n2. Mengambil konten lengkap artikel...")
	complete = []
	for i, article in enumerate(articles[:max_articles], 1):
		print(f"   Processing {i}/{min(len(articles), max_articles)}: {article['title'][:60]}...")
		resp = _get_with_retry(scraper, article['url'], referer=search_url)
		if not resp:
			print("   ✗ Gagal membuka halaman artikel (403/429)")
			continue
		soup = BeautifulSoup(resp.content, 'lxml')

		content = None
		for sel in [
			"body > div:nth-child(8) > div > div > div.col-bs10-7 > div > div.col-bs10-7.col-offset-0 > article",
			'article', '.article-content', '.post-content', '.entry-content',
			'div[class*="content"]', 'div[class*="article"]', '.main-content', '.content-body',
			'.post-body', '.news-content'
		]:
			content = soup.select_one(sel)
			if content:
				break
		if not content:
			for div in soup.find_all('div'):
				text = div.get_text(strip=True)
				if len(text) > 500:
					content = div
					break
		if content:
			for unwanted in content.select('script, style, iframe, ins, div[class*="ad" i]'):
				unwanted.decompose()
			text = content.get_text(separator='\n', strip=True)
			text = re.sub(r'\n\s*\n', '\n\n', text)
			text = re.sub(r'\s+', ' ', text)
			complete.append({**article, 'content': text, 'content_length': len(text)})
			print(f"   ✓ Konten ({len(text)} karakter)")
		else:
			print("   ✗ Konten tidak ditemukan")
		time.sleep(2 + random.uniform(0.2, 0.8))

	print(f"   Berhasil mengambil konten {len(complete)} artikel")
	return complete


def save_results(articles, query):
	"""Save results to files"""
	timestamp = datetime.now().strftime("%Y%m%d_%H%M%S")
	json_filename = f"radar_surabaya_{query.replace(' ', '_')}_{timestamp}.json"
	csv_filename = f"radar_surabaya_{query.replace(' ', '_')}_{timestamp}.csv"
	with open(json_filename, 'w', encoding='utf-8') as f:
		json.dump(articles, f, ensure_ascii=False, indent=2)
	pd.DataFrame(articles).to_csv(csv_filename, index=False, encoding='utf-8')
	print(f"Data disimpan ke: {json_filename} dan {csv_filename}")
	return json_filename, csv_filename


def print_summary(articles):
	print(f"\n{'='*60}")
	print("RINGKASAN SCRAPING")
	print(f"{'='*60}")
	print(f"Total artikel: {len(articles)}")
	for i, a in enumerate(articles[:3], 1):
		print(f"{i}. {a.get('title')}")
		print(f"   Tanggal: {a.get('date')}")
		print(f"   URL: {a.get('url')}")
		print(f"   Panjang konten: {len(a.get('content',''))} karakter")

# ============================================================================
# CONFIGURATION - UBAH PARAMETER DI SINI
# ============================================================================
SEARCH_QUERY = "harga jagung"
MAX_PAGES = 2
MAX_ARTICLES = 5

# ============================================================================
# EXECUTION - JALANKAN SCRAPING
# ============================================================================
print("Radar Surabaya Web Scraper - Fixed Version (Anti-403)")
print("=" * 50)
print("Data Mining Expert & Full Stack Engineer (30 years experience)")
print("=" * 50)

try:
	articles = scrape_radar_surabaya(SEARCH_QUERY, MAX_PAGES, MAX_ARTICLES)
	if articles:
		print_summary(articles)
		print("Menyimpan hasil...")
		save_results(articles, SEARCH_QUERY)
		print("\nScraping selesai!")
	else:
		print("Tidak ada artikel yang ditemukan.")
		print("Jika tetap 403/429, jalankan ulang setelah beberapa menit.")
except Exception as e:
	print(f"Error: {e}")
	print("Coba jalankan ulang atau periksa koneksi internet.")