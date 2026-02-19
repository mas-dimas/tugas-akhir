# 🚀 Testing Setup & Troubleshooting Guide

**Complete guide untuk setup dan menjalankan tests**

---

## ⚡ Quick Setup (2 langkah)

### 1. Setup Test Database
```bash
# Sesuaikan .env untuk testing
# Atau gunakan database terpisah untuk testing

# Create test database (MySQL)
mysql -u dimsur -pdimsur -e "CREATE DATABASE tugas_akhir_test;"

# Or using Laravel command
php artisan migrate:fresh --database=testing
```

### 2. Run Tests
```bash
php artisan test
```

---

## 🔧 Database Configuration for Testing

### Option A: Use Separate MySQL Test Database (Recommended)

Edit `phpunit.xml`:
```xml
<env name="DB_CONNECTION" value="mysql"/>
<env name="DB_DATABASE" value="tugas_akhir_test"/>
<env name="DB_USERNAME" value="dimsur"/>
<env name="DB_PASSWORD" value="dimsur"/>
```

Create test database:
```bash
# Manual creation
mysql -u dimsur -pdimsur -e "CREATE DATABASE tugas_akhir_test;"

# Or run migrations
php artisan migrate --env=testing --database=testing
```

### Option B: Use SQLite File (If MySQL unavailable)

Edit `phpunit.xml`:
```xml
<env name="DB_CONNECTION" value="sqlite"/>
<env name="DB_DATABASE" value="database/testing.sqlite"/>
```

Create database file:
```bash
touch database/testing.sqlite
php artisan migrate --database=testing
```

---

## 📋 Test Environment Setup Checklist

- [ ] `.env` file exists
- [ ] Database credentials correct in `phpunit.xml`
- [ ] Test database created and empty
- [ ] Factories created (CompetitionFactory.php, RegistrationFactory.php)
- [ ] Models have `HasFactory` trait
- [ ] Tests have `RefreshDatabase` trait
- [ ] PHP extensions: pdo, pdo_mysql (or pdo_sqlite)

---

## 🏃 Running Tests

### All Tests
```bash
php artisan test
```

### Specific Test File
```bash
php artisan test tests/Feature/Api/AuthTest.php
```

### Specific Test Method
```bash
php artisan test --filter=test_user_can_login_with_valid_credentials
```

### With Coverage Report
```bash
php artisan test --coverage
```

### Watch Mode (Auto-rerun)
```bash
php artisan test --watch
```

### Stop on First Failure
```bash
php artisan test --stop-on-failure
```

---

## ❌ Common Issues & Solutions

### Issue 1: "Could not find driver (Connection: sqlite)"
**Cause**: SQLite PDO extension not available

**Solutions**:
```bash
# Option A: Use MySQL instead (update phpunit.xml)
# Option B: Install SQLite
sudo apt-get install php-sqlite3

# Then restart services
php artisan test
```

### Issue 2: "Access denied for database user"
**Cause**: Wrong credentials in phpunit.xml

**Solution**:
```bash
# Check credentials in .env
cat .env | grep DB_

# Update phpunit.xml with same credentials
# Verify you can login manually
mysql -u dimsur -pdimsur -e "SELECT 1;"
```

### Issue 3: "Base table or view not found"
**Cause**: Test database exists but tables not migrated

**Solution**:
```bash
# Run migrations in test environment
php artisan migrate:fresh --env=testing

# Or if using specific database connection
php artisan migrate --database=testing
```

### Issue 4: "Class not found" or "Method not found"
**Cause**: Autoloader not updated

**Solution**:
```bash
# Regenerate autoloader
composer dump-autoload

# Clear all caches
php artisan cache:clear
php artisan config:clear

# Try tests again
php artisan test
```

### Issue 5: "SQLSTATE[HY000]: General error"
**Cause**: Test database permissions or corruption

**Solution**:
```bash
# Drop and recreate test database
mysql -u dimsur -pdimsur -e "DROP DATABASE tugas_akhir_test; CREATE DATABASE tugas_akhir_test;"

# Run migrations fresh
php artisan migrate:fresh --database=testing

# Try tests
php artisan test
```

### Issue 6: "TokensMismatchException" in tests
**Cause**: CSRF tokens in web routes

**Solution**: Tests use JSON API, so CSRF not needed. If issue persists:
```php
// In TestCase.php
public function setUp(): void
{
    parent::setUp();
    $this->withoutMiddleware('csrf');  // Disable for tests
}
```

---

## 🛠️ Database Debugging

### Check Test Database Status
```bash
# Connect to test database
mysql -u dimsur -pdimsur tugas_akhir_test -e "SHOW TABLES;"

# Check table structure
mysql -u dimsur -pdimsur tugas_akhir_test -e "DESCRIBE users;"
```

### Verify Migrations
```bash
# Show migration history
php artisan migrate:status

# Rollback test database
php artisan migrate:reset --env=testing

# Re-run migrations
php artisan migrate --env=testing
```

### Check Factories
```bash
# Verify factories are registered
php artisan tinker

tinker> User::factory()->make()
```

---

## 📊 Test Execution Flow

```
1. PHPUnit starts
   ↓
2. Load phpunit.xml
   ↓
3. Set environment variables (APP_ENV=testing, DB_CONNECTION, etc.)
   ↓
4. Bootstrap Laravel framework
   ↓
5. Connect to test database
   ↓
6. For each test:
   - Run migrations (RefreshDatabase)
   - Create test data (Factories)
   - Execute test
   - Rollback changes
   ↓
7. Generate report
```

---

## 🔄 Test Lifecycle with RefreshDatabase

```php
use Illuminate\Foundation\Testing\RefreshDatabase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;  // ← This does:
    
    // 1. Before each test:
    // - Drop all tables
    // - Run all migrations
    
    // 2. After each test:
    // - Drop all tables
    // Result: Clean database for each test
}
```

---

## 📝 Database Configuration Files

### .env (Development)
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_DATABASE=tugas-akhir
DB_USERNAME=dimsur
DB_PASSWORD=dimsur
```

### phpunit.xml (Testing)
```xml
<env name="DB_CONNECTION" value="mysql"/>
<env name="DB_DATABASE" value="tugas_akhir_test"/>
<env name="DB_USERNAME" value="dimsur"/>
<env name="DB_PASSWORD" value="dimsur"/>
```

**Key difference**: 
- Development uses `tugas-akhir` database
- Testing uses separate `tugas_akhir_test` database

---

## 💡 Pro Tips

### 1. Keep Tests Fast
```bash
# Run only changed tests
php artisan test --previous

# Parallel execution (if available)
php artisan test --parallel
```

### 2. Debug Specific Test
```bash
# Add dd() in test
public function test_something()
{
    $response = $this->postJson('/api/endpoint', []);
    dump($response->json());
}

# Then run that test
php artisan test --filter=test_something
```

### 3. Generate Factories Quickly
```bash
# Generate factory for model
php artisan make:factory CompetitionFactory

# Automatically linked to model if naming correct
```

### 4. Seed Test Data
```php
// In test
public function test_something()
{
    \Database\Seeders\DatabaseSeeder::class)->call();  // Run seeder
    
    $response = $this->postJson('/api/endpoint', []);
}
```

### 5. Save Test Results
```bash
# Output to file
php artisan test > test-results.txt 2>&1

# Generate XML report
php artisan test --log-junit=test-results.xml
```

---

## 📈 Continuous Integration Setup

### GitHub Actions Example
```yaml
name: Tests

on: [push, pull_request]

jobs:
  test:
    runs-on: ubuntu-latest
    
    services:
      mysql:
        image: mysql:8.0
        env:
          MYSQL_ROOT_PASSWORD: root
          MYSQL_DATABASE: tugas_akhir_test
        options: >-
          --health-cmd="mysqladmin ping"
          --health-interval=10s
          --health-timeout=5s
          --health-retries=3
    
    steps:
      - uses: actions/checkout@v2
      - uses: shivammathur/setup-php@v2
        with:
          php-version: '8.2'
          extensions: mysql, sqlite
      - run: composer install
      - run: php artisan migrate --env=testing
      - run: php artisan test
```

---

## ✅ Pre-Deployment Testing Checklist

- [ ] All tests passing locally
- [ ] Code coverage > 85%
- [ ] No console errors during tests
- [ ] No database warnings
- [ ] Tests pass on multiple runs
- [ ] Can run tests in isolation
- [ ] CI/CD pipeline configured
- [ ] Performance acceptable (< 10s for all tests)

---

## 📞 Helpful Commands Reference

```bash
# Setup & Run
composer install
php artisan migrate --env=testing
php artisan test

# Watch mode (development)
php artisan test --watch

# Coverage report
php artisan test --coverage

# Specific tests
php artisan test tests/Feature/Api/AuthTest.php
php artisan test --filter=test_user_can_login

# Debug
php artisan tinker
>>> User::factory()->create()

# Cache management
php artisan cache:clear
php artisan config:clear

# Database
php artisan migrate:status
php artisan migrate:fresh --env=testing
```

---

## 🔗 Related Files

- [TESTING_GUIDE.md](TESTING_GUIDE.md) - Comprehensive testing documentation
- [phpunit.xml](phpunit.xml) - Test configuration
- [tests/Feature/Api/AuthTest.php](tests/Feature/Api/AuthTest.php) - Auth tests
- [tests/Feature/Api/CompetitionTest.php](tests/Feature/Api/CompetitionTest.php) - Competition tests
- [tests/Feature/Api/RegistrationTest.php](tests/Feature/Api/RegistrationTest.php) - Registration tests

---

## 📱 Quick Troubleshooting Flowchart

```
Tests failing?
    ├─ Database error?
    │  └─ Drop & recreate: mysql -u dimsur -pdimsur -e "DROP DATABASE tugas_akhir_test; CREATE DATABASE tugas_akhir_test;"
    │
    ├─ Driver not found?
    │  └─ Install PDO: apt-get install php-sqlite3 (or use MySQL)
    │
    ├─ Class not found?
    │  └─ Dump autoloader: composer dump-autoload
    │
    ├─ Test not running?
    │  └─ Check filter: php artisan test --filter=test_name
    │
    └─ Timeout?
       └─ Check long operations or increase timeout
```

---

**Last Updated**: 2026-02-19  
**Status**: ✅ Ready for Testing

Happy Testing! 🎉
