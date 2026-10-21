# Technology Stack

## 1. Runtime & Infrastructure
* **Language & Runtime:** PHP 8.2+ (Active local: PHP 8.2.26)
* **Framework:** Laravel 12.0 (`laravel/framework: ^12.0`)
* **Web Server Environment:** Apache (WAMP64 stack on Windows)
* **Database Engine:** MySQL / MariaDB (accessed via Eloquent ORM & PDO)

## 2. Frontend & Build Pipeline
* **Bundler & Tooling:** Vite 6.2.4 (`vite: ^6.2.4`, `laravel-vite-plugin: ^1.2.0`)
* **CSS Framework:** Tailwind CSS v4.0.0 (`@tailwindcss/vite: ^4.0.0`, `tailwindcss: ^4.0.0`)
* **Templating Engine:** Laravel Blade
* **HTTP Client (Client-Side):** Axios 1.8.2
* **Build Concurrency:** Concurrently 9.0.1

## 3. Core PHP / Composer Dependencies
* **Image Processing:** `intervention/image: ^3.11` (v3 GD driver with WebP scaling)
* **Role & Permission Management:** `spatie/laravel-permission: ^6.23`
* **Datatables:** `yajra/laravel-datatables-oracle: ^12.2` (Server-side tabular processing for Admin portal)
* **Markdown Parsing:** `league/html-to-markdown: ^5.1`
* **REPL:** `laravel/tinker: ^2.10.1`

## 4. Development & Testing Dependencies
* **Testing Suite:** `phpunit/phpunit: ^11.5.3`, `mockery/mockery: ^1.6`
* **Diagnostics & Debugging:** `fruitcake/laravel-debugbar: ^4.1`, `laravel/pail: ^1.2.2`
* **Code Styling:** `laravel/pint: ^1.13`
* **Local Development CLI:** `laravel/sail: ^1.41`
* **Mock Data:** `fakerphp/faker: ^1.23`
