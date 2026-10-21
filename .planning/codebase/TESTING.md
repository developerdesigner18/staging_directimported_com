# Testing Strategy & Coverage Status

## 1. Test Harness
* **Framework:** PHPUnit 11.5.3 (`phpunit/phpunit`)
* **Mocking:** Mockery 1.6 (`mockery/mockery`)
* **Configuration:** `phpunit.xml` in project root

## 2. Current Test Coverage
* **Coverage Level:** **0% Custom Test Coverage (Nominal/Default Only)**
* **Existing Files:**
  * `tests/Feature/ExampleTest.php`: Default boilerplate root HTTP 200 assertion.
  * `tests/Unit/ExampleTest.php`: Default boilerplate `assertTrue(true)` assertion.
* **Missing Test Suites:**
  * No unit tests for `CarSensorScraperService`, `CarSensorMappingService`, or `CarSensorUrlValidator`.
  * No tests for authentication guards, password resets, or role permissions.
  * No integration tests for quote submission or contact request reCAPTCHA logic.
  * No tests for booking calculation logic or payment/pricing tiers.

## 3. Recommended Test Suite Additions
1. **Unit Tests:**
   * URL validation logic for CarSensor URLs.
   * Vehicle pricing calculation algorithms across duration brackets.
   * Image processing and WebP conversion helpers.
2. **Feature Tests:**
   * Vehicle listing filter and pagination API responses.
   * Contact request submission with valid and invalid reCAPTCHA responses.
   * Multi-guard authentication boundary isolation (`admin`, `employee`, `user`).
