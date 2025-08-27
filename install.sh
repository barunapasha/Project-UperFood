#!/bin/bash

echo "=========================================="
echo "RADAR SURABAYA WEB SCRAPER INSTALLATION"
echo "Professional Data Mining & Web Scraping Tool"
echo "=========================================="

# Check if Python 3 is installed
if ! command -v python3 &> /dev/null; then
    echo "❌ Python 3 is not installed. Please install Python 3 first."
    exit 1
fi

echo "✅ Python 3 is installed"

# Check if pip is installed
if ! command -v pip3 &> /dev/null; then
    echo "❌ pip3 is not installed. Please install pip3 first."
    exit 1
fi

echo "✅ pip3 is installed"

# Create virtual environment
echo "📦 Creating virtual environment..."
python3 -m venv venv

# Activate virtual environment
echo "🔧 Activating virtual environment..."
source venv/bin/activate

# Upgrade pip
echo "⬆️ Upgrading pip..."
pip install --upgrade pip

# Install dependencies
echo "📚 Installing dependencies..."
pip install -r requirements.txt

echo ""
echo "=========================================="
echo "✅ INSTALLATION COMPLETED SUCCESSFULLY!"
echo "=========================================="
echo ""
echo "To run the scraper:"
echo "1. Activate virtual environment: source venv/bin/activate"
echo "2. Run the scraper: python radar_scraper.py"
echo ""
echo "Or use the run script: ./run.sh"
echo "=========================================="