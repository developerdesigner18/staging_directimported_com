# System Architecture

## 1. Architectural Pattern
The application follows a **Classic Laravel MVC** architecture enhanced with a dedicated **Service Layer** for external integrations and complex business logic (e.g., CarSensor scraper pipelines, Gemini AI integrations).

```
[ HTTP Requests ]
        │
        ▼
[ Routing & Middleware ] (web.php / admin.php)
  ├── Multi-Guard Auth (web, admin, employee)
  ├── Profile Completion Check (profile)
  └── RBAC Permission Check (check_permission)
        │
        ▼
[ Controllers ] (Admin/, User/, CarController, etc.)
        │
        ├──► [ Service Layer ] (CarSensor, Gemini, HtmlSanitizer)
        │
        ├──► [ Eloquent Models ] (Car, Booking, ContactRequest, etc.)
        │           │
        │           ▼
        │     [ MySQL Database ]
        │
        └──► [ Blade Views ] (landing/, admin/, emails/)
```

## 2. Authentication & Guard Separation
The system configures three distinct guards in `config/auth.php`:
* **`web` Guard:** Public customers/clients (`App\Models\User`). Handles booking requests, vehicle inquiry history, and profile documents.
* **`admin` Guard:** Super administrators (`App\Models\Admin`). Has unrestricted access to site settings, employee assignments, and financial configurations.
* **`employee` Guard:** Internal staff and operators (`App\Models\Employee`). Subject to role-based access control (`spatie/laravel-permission`).

## 3. Core Domains & Eloquent Entities
* **Vehicle Inventory:**
  * `Car`: Primary entity tracking UUID, VIN, mileage, prices (tiered by rental/sale duration), auction grades, and media.
  * `CarSpec`: Structured specifications (transmission, engine capacity, fuel, drive type, interior/exterior grade).
  * Supporting Catalogs: `CarType`, `Manufacturer`, `Category`, `Color`, `AuctionGrade`, `AdminLabel`.
* **Lead Generation & Sales:**
  * `ContactRequest`: Captures vehicle import quotes and general inquiry requests with reCAPTCHA verification.
  * `Booking`: Tracks booking reservations, pricing calculations, accessory additions, and user references.
* **Content Management System (CMS):**
  * `BlogPost`, `Service`, `Faq`, `FaqCategory`, `Gallery`, `HeroSlider`, `HomeSection`, `SiteSettings`.

## 4. Routing Topography
* **`routes/web.php`:**
  * Public landing endpoints: `/`, `/available-vehicles`, `/contact`, `/terms-and-conditions`, etc.
  * Vehicle catalogue & detail: `/car`, `/car/{slug}`, `/car/request/quote`.
  * User Auth & Customer Dashboard: `/login`, `/register`, `/profile`, `/profile-bookings`.
* **`routes/admin.php`:**
  * Backoffice operations under `/admin` prefix.
  * Inventory CRUD, bulk CarSensor imports, media manager, permissions, and email templates.
* **`routes/console.php`:**
  * Artisan command schedules and maintenance tasks.
