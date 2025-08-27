#!/usr/bin/env python3
"""
Contoh Penggunaan Radar Surabaya Web Scraper
Example Usage of Radar Surabaya Web Scraper
"""

from radar_scraper import RadarSurabayaScraper
import json

def example_basic_usage():
    """
    Contoh penggunaan dasar scraper
    """
    print("=== CONTOH PENGGUNAAN DASAR ===")
    
    # Inisialisasi scraper
    scraper = RadarSurabayaScraper()
    
    # Kata kunci pencarian
    query = "harga jagung"
    
    print(f"Pencarian untuk: {query}")
    
    # Lakukan scraping
    articles = scraper.scrape_articles(query)
    
    # Tampilkan hasil
    if articles:
        print(f"\nDitemukan {len(articles)} artikel:")
        for i, article in enumerate(articles, 1):
            print(f"\n{i}. {article['title']}")
            print(f"   Tanggal: {article['date']}")
            print(f"   URL: {article['url']}")
            print(f"   Preview: {article['content'][:100]}...")
        
        # Simpan ke file
        scraper.save_to_csv("contoh_hasil.csv")
        scraper.save_to_json("contoh_hasil.json")
        print(f"\n✅ Data disimpan ke contoh_hasil.csv dan contoh_hasil.json")
    else:
        print("❌ Tidak ada artikel yang ditemukan")

def example_multiple_queries():
    """
    Contoh penggunaan dengan multiple queries
    """
    print("\n=== CONTOH MULTIPLE QUERIES ===")
    
    scraper = RadarSurabayaScraper()
    
    # List kata kunci
    queries = ["harga jagung", "berita surabaya", "ekonomi"]
    
    all_articles = []
    
    for query in queries:
        print(f"\n🔍 Mencari: {query}")
        articles = scraper.scrape_articles(query)
        all_articles.extend(articles)
        print(f"   Ditemukan: {len(articles)} artikel")
    
    # Simpan semua hasil
    if all_articles:
        scraper.articles_data = all_articles
        scraper.save_to_csv("semua_hasil.csv")
        scraper.save_to_json("semua_hasil.json")
        print(f"\n✅ Total {len(all_articles)} artikel disimpan")

def example_custom_search():
    """
    Contoh penggunaan dengan custom search
    """
    print("\n=== CONTOH CUSTOM SEARCH ===")
    
    scraper = RadarSurabayaScraper()
    
    # Custom query
    custom_query = input("Masukkan kata kunci custom: ").strip()
    
    if custom_query:
        articles = scraper.scrape_articles(custom_query)
        
        if articles:
            print(f"\n📊 STATISTIK HASIL:")
            print(f"Total artikel: {len(articles)}")
            
            # Analisis sederhana
            titles = [article['title'] for article in articles]
            dates = [article['date'] for article in articles if article['date']]
            
            print(f"Artikel dengan tanggal: {len(dates)}")
            print(f"Artikel tanpa tanggal: {len(articles) - len(dates)}")
            
            # Simpan dengan nama custom
            filename = f"hasil_{custom_query.replace(' ', '_')}"
            scraper.save_to_csv(f"{filename}.csv")
            scraper.save_to_json(f"{filename}.json")
            print(f"✅ Data disimpan ke {filename}.csv dan {filename}.json")
        else:
            print("❌ Tidak ada hasil untuk kata kunci tersebut")

def main():
    """
    Main function untuk menjalankan contoh
    """
    print("="*60)
    print("CONTOH PENGGUNAAN RADAR SURABAYA WEB SCRAPER")
    print("="*60)
    
    while True:
        print("\nPilih contoh yang ingin dijalankan:")
        print("1. Penggunaan Dasar")
        print("2. Multiple Queries")
        print("3. Custom Search")
        print("4. Keluar")
        
        choice = input("\nPilihan (1-4): ").strip()
        
        if choice == "1":
            example_basic_usage()
        elif choice == "2":
            example_multiple_queries()
        elif choice == "3":
            example_custom_search()
        elif choice == "4":
            print("Terima kasih telah menggunakan Radar Surabaya Web Scraper!")
            break
        else:
            print("❌ Pilihan tidak valid!")

if __name__ == "__main__":
    main()