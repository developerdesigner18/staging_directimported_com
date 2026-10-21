# Directory & Codebase Structure

```
c:\wamp64\www\staging_directimported_com
├── app/
│   ├── Console/             # Custom console commands & schedules
│   ├── Enum/                # Native PHP 8.1+ Enums (CarStatus, VehicleStatus, etc.)
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/       # Backoffice controllers (Car, Booking, Gallery, etc.)
│   │   │   ├── User/        # Customer portal controllers (Auth, Profile)
│   │   │   ├── ForgotPassword/
│   │   │   ├── CarController.php            # Public vehicle catalog & single car views
│   │   │   ├── ContactRequestController.php # Inquiry & quote submission handling
│   │   │   ├── HomeController.php           # Marketing and landing pages
│   │   │   └── ServiceController.php
│   │   ├── Helper/
│   │   │   └── helper.php   # Global helper functions (uploadFile, formatting, mail)
│   │   ├── Middleware/      # Custom middleware (Authenticate, CheckPermission, etc.)
│   │   └── Traits/          # Shared controller traits (ResponseTrait)
│   ├── Mail/                # Mailable notification classes
│   ├── Models/              # 34 Eloquent Models
│   ├── Providers/           # Service providers (AppServiceProvider, RouteServiceProvider)
│   └── Services/            # Business logic & integrations
│       ├── CarSensor/       # Japanese car auction scraper & ingestion pipeline
│       ├── Gemini/          # Google Gemini AI client
│       └── HtmlSanitizer.php
├── bootstrap/
│   └── app.php              # Laravel 12 application bootstrap & routing pipeline
├── config/
│   ├── auth.php             # Multi-guard auth configuration
│   ├── constants.php        # Application constants and static upload paths
│   └── services.php         # Third-party credentials (reCAPTCHA, Gemini, etc.)
├── database/
│   ├── factories/           # Model factories
│   ├── migrations/          # 70 migration files tracking schema evolution
│   └── seeders/             # Initial database seeders
├── public/                  # Public web root (CSS, JS, WebP uploads)
├── resources/
│   ├── views/
│   │   ├── admin/           # Admin panel Blade templates
│   │   ├── emails/          # Email notification templates
│   │   ├── landing/         # Public customer-facing views
│   │   │   ├── auth/        # Login and registration templates
│   │   │   ├── car/         # Vehicle catalogue and single detail templates
│   │   │   ├── layouts/     # Shared header, footer, navigation
│   │   │   ├── pages/       # Static marketing pages (about, terms, policies)
│   │   │   └── master.blade.php
│   │   └── welcome.blade.php
├── routes/
│   ├── admin.php            # Administration dashboard & CRUD routes
│   ├── console.php          # CLI commands
│   └── web.php              # Public frontend routes
├── storage/                 # Logs, framework cache, file sessions
└── tests/
    ├── Feature/             # Feature test suite (default ExampleTest only)
    └── Unit/                # Unit test suite (default ExampleTest only)
```
