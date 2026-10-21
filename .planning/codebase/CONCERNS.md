# Codebase Concerns & Technical Debt

## 1. High Priority Security Concerns
1. **Plain-Text Password Column in Database:**
   * **Location:** `database/migrations/2026_10_12_092033_add_plain_password_to_employees_table.php`, `app/Models/Employee.php`.
   * **Issue:** Migration added `plain_password` column to `employees` table, and `Employee.php` includes `plain_password` in `$fillable`.
   * **Risk:** Cleartext password storage poses a severe compliance and security breach risk.
2. **Synchronous Unshielded HTTP Call for reCAPTCHA:**
   * **Location:** `app/Http/Controllers/ContactRequestController.php` (line 40).
   * **Issue:** Uses `file_get_contents($url)` instead of Laravel's resilient `Http::timeout(5)->post(...)`.
   * **Risk:** Can freeze request workers, cause DoS under high traffic, or fail entirely if `allow_url_fopen` is restricted.
3. **Mass Assignment Vulnerability in Lead Ingestion:**
   * **Location:** `app/Http/Controllers/ContactRequestController.php` (line 52).
   * **Issue:** `ContactRequest::create($request->all())` passes all raw input without explicit filtering or validation array extraction.
4. **Direct Environment Variable Access in Application Logic:**
   * **Location:** Multiple controllers and helpers (e.g. `env('RECEIVER_MAIL')` in `ContactRequestController.php`).
   * **Issue:** Direct calls to `env()` will return `null` once configuration is cached in production (`php artisan config:cache`). Must use `config(...)`.

## 2. Technical Debt & Codebase Hygiene
1. **Legacy Bike Codebase Artifacts:**
   * The project originally served as a bike rental application before being renamed in April 2026 (`rename_bike_tables_to_car_tables.php`).
   * `app/Http/Controllers/BikeController_backup.php` (40KB) remains in production namespace.
2. **Redundant & Competing View Templates:**
   * Under `resources/views/landing/car/`:
     * `15_single.blade.php` (29KB)
     * `single.blade.php` (104KB)
     * `single2.blade.php` (10KB)
     * `cars_old.blade.php` (30KB)
   * Multiple versions of the vehicle detail view create confusion regarding which template is canonical.
3. **Loose Utility Scripts in Web Root:**
   * `search_db.php` (raw database inspection script directly in root).
   * `update_car_stock_ids.php` (one-off stock update script directly in root).
   * These should be converted into Artisan console commands (`app/Console/Commands/`) and removed from root.
4. **Monolithic Blade Templates:**
   * `welcome.blade.php` (114KB) and `single.blade.php` (104KB) contain deeply nested inline styling, script tags, and modal HTML that should be decomposed into reusable Blade components.
5. **Orphan Directory:**
   * `public_/` exists as an empty directory in the root.
