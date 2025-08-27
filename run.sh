#!/bin/bash

echo "=========================================="
echo "RADAR SURABAYA WEB SCRAPER"
echo "Professional Data Mining & Web Scraping Tool"
echo "=========================================="

# Check if virtual environment exists
if [ ! -d "venv" ]; then
    echo "❌ Virtual environment not found. Please run ./install.sh first."
    exit 1
fi

# Activate virtual environment
echo "🔧 Activating virtual environment..."
source venv/bin/activate

# Check if main script exists
if [ ! -f "radar_scraper.py" ]; then
    echo "❌ radar_scraper.py not found!"
    exit 1
fi

# Run the scraper
echo "🚀 Starting Radar Surabaya Web Scraper..."
echo "=========================================="
python radar_scraper.py