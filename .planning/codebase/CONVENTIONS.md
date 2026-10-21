# Code Conventions & Quality Standards

## 1. Controller Patterns & Response Formatting
* Controllers widely utilize `App\Http\Traits\ResponseTrait` for standardized JSON responses:
  * `sendSuccess($message, $data = [])`
  * `sendValidationError($errors, $message = 'Validation Error')`
  * `sendError($error, $code = 404)`
* Form validation is predominantly executed using explicit `Validator::make($request->all(), [...])` blocks directly inside controller actions rather than dedicated `FormRequest` classes.

## 2. File & Media Uploads
* Media processing is centralized in `app/Http/Helper/helper.php` via `uploadFile()`:
  * Employs Intervention Image v3 with the GD driver.
  * Overrides PHP memory limit dynamically to `512M` (`ini_set('memory_limit', '512M')`).
  * Auto-scales images down if dimensions exceed 2000px on either axis.
  * Encodes all incoming images to WebP format at quality 85.
  * Generates uniform naming: `{prefix}_{timestamp}_{uniqid}.webp`.

## 3. View Composition & Blade Architecture
* Public views extend `landing.master` with `@section('content')` and push styles/scripts using `@push('style')` and `@push('script')`.
* Many single car views embed custom scoped CSS directly in `@push('style')` blocks rather than compiling exclusively through Vite/Tailwind.

## 4. Eloquent Model Conventions
* Models use `$fillable` arrays to specify mass-assignable attributes.
* UUID generation is handled in Eloquent model boot lifecycles (`static::creating`).
* State values utilize PHP 8.1 native Enums (`App\Enum\CarStatus`, `App\Enum\VehicleStatus`, etc.).
