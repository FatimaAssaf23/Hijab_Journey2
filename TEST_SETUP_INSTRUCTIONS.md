# Test Setup Instructions

## Option 1: Enable SQLite (Recommended for Testing)

SQLite is the preferred database for Laravel tests because it's fast and doesn't require a separate database server.

### Steps:

1. **Find your PHP configuration file:**
   ```bash
   php --ini
   ```
   Look for "Loaded Configuration File" path

2. **Edit php.ini:**
   - Open the php.ini file in a text editor
   - Find the line: `;extension=pdo_sqlite`
   - Remove the semicolon to uncomment it: `extension=pdo_sqlite`
   - Find the line: `;extension=sqlite3`
   - Remove the semicolon: `extension=sqlite3`

3. **Restart WAMP server:**
   - Click on WAMP icon → Restart All Services

4. **Verify SQLite is enabled:**
   ```bash
   php -m | findstr sqlite
   ```
   You should see: `pdo_sqlite` and `sqlite3`

5. **Update phpunit.xml** (already done - uses SQLite)

6. **Run tests:**
   ```bash
   php artisan test --testsuite=Unit
   ```

## Option 2: Use MySQL Test Database

If you prefer to use MySQL:

1. **Create test database:**
   ```sql
   CREATE DATABASE cap_hijab_journy_test;
   ```

2. **phpunit.xml is already configured** for MySQL test database

3. **Run migrations on test database:**
   ```bash
   php artisan migrate --database=mysql --env=testing
   ```

4. **Run tests:**
   ```bash
   php artisan test --testsuite=Unit
   ```

## Current Status

- ✅ Test files created (65 tests)
- ✅ Factories created (14 factories)
- ⚠️ Database driver issue (SQLite not enabled OR test database not created)

## Quick Fix (Temporary)

If you want to test immediately with your existing database (NOT RECOMMENDED for production):

Update `phpunit.xml` line 27 to use your existing database name:
```xml
<env name="DB_DATABASE" value="your_existing_database_name"/>
```

**Warning:** This will use your production database for tests, which is not recommended!
