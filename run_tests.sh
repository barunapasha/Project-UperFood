#!/bin/bash

echo "=========================================="
echo "RUNNING RADAR SURABAYA SCRAPER TESTS"
echo "=========================================="

# Check if virtual environment exists
if [ ! -d "venv" ]; then
    echo "❌ Virtual environment not found. Please run ./install.sh first."
    exit 1
fi

# Activate virtual environment
echo "🔧 Activating virtual environment..."
source venv/bin/activate

# Check if test file exists
if [ ! -f "test_scraper.py" ]; then
    echo "❌ test_scraper.py not found!"
    exit 1
fi

# Run tests
echo "🧪 Running tests..."
echo "=========================================="
python test_scraper.py

# Check exit code
if [ $? -eq 0 ]; then
    echo ""
    echo "✅ All tests passed!"
else
    echo ""
    echo "❌ Some tests failed!"
    exit 1
fi