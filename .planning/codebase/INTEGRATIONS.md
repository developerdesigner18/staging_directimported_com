# Integrations & External Services

## 1. CarSensor Japanese Vehicle Scraper & Ingestion
* **Implementation Path:** `app/Services/CarSensor/`
* **Components:**
  * `CarSensorScraperService.php`: Crawls vehicle detail listings, extracts specifications, auction grades, pricing, and image URLs.
  * `CarSensorUrlValidator.php`: Validates CarSensor URL syntax and schema consistency.
  * `CarSensorDuplicateChecker.php`: Checks whether a CarSensor vehicle or listing has already been imported.
  * `CarSensorMappingService.php`: Normalizes scraped Japanese specifications to local database enumerations, car types, and models.
  * `CarSensorImageService.php`: Downloads remote image assets and processes them into local WebP storage.
  * `CarSensorImportService.php`: Orchestrates the import workflow and logs status to `carsensor_import_logs`.

## 2. Google Gemini AI API
* **Implementation Path:** `app/Services/Gemini/`
* **Components:**
  * `GeminiClient.php`: Encapsulates REST communication with the Google Gemini model endpoint.
  * `GeminiException.php`: Custom exception wrapper for Gemini API call failures.
  * **Use Cases:** Generates localized vehicle marketing copy, translates technical Japanese inspection specs, and formats car descriptions.

## 3. Google reCAPTCHA v2
* **Target Areas:** `ContactRequestController.php` (vehicle inquiry forms).
* **Configuration:** `services.recaptcha.secret_key` / `site_key`.
* **Execution:** Validates `g-recaptcha-response` token against `https://www.google.com/recaptcha/api/siteverify`.

## 4. Mail & Notification Services
* **Implementation Path:** `app/Mail/`, `app/Http/Helper/helper.php` (`sendDynamicEmail`)
* **Templates:** Database-driven email templates via `EmailTemplates` and `CustomMail` models, complemented by Blade notification layouts in `resources/views/emails/`.

## 5. Rich Text & Media Management
* **TinyMCE:** Admin content editor integrated for blog posts, home sections, and vehicle rich descriptions (`tinymce.image.upload`).
* **Intervention Image (v3):** Automatically converts uploaded photos to WebP format, limits dimensions to 2000px, and dynamically allocates 512MB RAM for processing.
