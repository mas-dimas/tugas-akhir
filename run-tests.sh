#!/bin/bash

# Testing Setup Script
# This script sets up the test environment and runs tests

set -e

echo "🧪 Setting up testing environment..."

# Colors
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Check if .env exists
if [ ! -f .env ]; then
    echo "❌ .env file not found. Please create one first."
    exit 1
fi

# Create test database
echo "${YELLOW}Creating test database...${NC}"
php artisan migrate:fresh --database=testing 2>/dev/null || true

# Clear cache
echo "${YELLOW}Clearing cache...${NC}"
php artisan cache:clear
php artisan config:cache

# Run tests
echo "${GREEN}✅ Running tests...${NC}"
php artisan test

echo "${GREEN}✅ Testing setup complete!${NC}"
