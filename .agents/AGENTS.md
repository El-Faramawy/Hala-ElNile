# HalaElNile — Project Agent Rules

## Project Overview

HalaElNile is a **Laravel web application** consisting of:
1. **Website** — public-facing pages (contact form, city listing, job categories, etc.)
2. **Admin Dashboard** — session-based admin panel (CRUD management)

> **No REST API. No JWT. No mobile app. No localization — Arabic only.**

---

## Folder Structure

```
HalaElNile/
├── .agents/              # Agent rules and guidelines (AGENTS.md)
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   └── Admin/    # Admin dashboard controllers (thin controllers)
│   │   ├── Middleware/   # Admin session middleware
│   │   └── Requests/     # FormRequest classes (validation only)
│   │       └── Admin/    # Admin-specific requests grouped by domain
│   ├── Models/           # Eloquent models ($fillable defined)
│   ├── Providers/        # Service providers
│   ├── Repositories/     # Database abstraction layer (all DB queries here)
│   ├── Services/         # Business logic services (organized by domain)
│   │   ├── Admin/        # AdminService, DashboardService
│   │   ├── City/         # CityService
│   │   ├── Contact/      # ContactService + DTO
│   │   ├── Faq/          # FaqService
│   │   ├── JobCategory/  # JobCategoryService
│   │   └── Setting/      # SettingService
│   └── Traits/           # Shared traits (UploadTrait)
├── database/             # Migrations, seeders, and factories
├── resources/views/      # Blade views
│   ├── Admin/            # Admin panel views (grouped by domain)
│   └── layouts/          # Shared layouts and partials
├── routes/
│   ├── web.php           # Website routes
│   └── admin.php         # Admin dashboard routes (included via web.php)
└── vendor/               # Third-party PHP dependencies
```

---

## In-Scope Modules

| Module | Description |
|---|---|
| **Admin** | Session-auth admin user CRUD (admin guard) |
| **Auth** | Admin login / logout via Laravel session |
| **JobCategory** | Admin CRUD (job sectors in Italy: agriculture, construction, restaurants, etc.) |
| **Job** | Admin CRUD (work contracts in Italy: perks, requirements, salary, city, image) |
| **JobApplication** | Job applications received from candidates with status and admin notes |
| **ContractTracker** | Contract & visa tracking (Nulla Osta, 5-step progress, flight details) |
| **Traveler** | Stories and testimonials of travelers in Italy with photos |
| **ContactMessage** | Website contact inquiries with mark-as-read and notes |
| **City** | 27 Egyptian governorates for applicant selection |
| **Faq** | Frequently asked questions & answers |
| **Setting** | Site identity, Ministry license (912), contact info, socials, counters, about_us |

---

## ❌ What is NOT in this project

- No `routes/api.php` — no REST API endpoints
- No JWT / Sanctum / Passport / OTP / SMS
- No `auth('api')` guard — the only guard is `admin` (session)
- No User model authentication from the website
- No `ApiResponse` class
- No `BookingRepository`, `CarRepository`, `BranchRepository`, `UserRepository`
- No multi-language / localization — **Arabic only**, single field names (e.g. `name`, `about_us`)
- No `HasLocalizedAttributes` trait — do not use `_ar` / `_en` column suffixes
- No `BusinessException` base class or custom HTTP exceptions

---

## Architecture — Request Flow

```
Route (admin.php / web.php)
  └── FormRequest (validation)
        └── Admin Controller (thin controller)
              └── Service (business logic, DataTable formatting)
                    └── Repository (database queries & Eloquent abstractions)
                          └── Model (fillable, hidden, casts)
```

| Layer | Responsibility |
|---|---|
| **Route** | RESTful resource + multi-delete routes |
| **FormRequest** | Input validation rules only. Place in `app/Http/Requests/Admin/{Domain}/` |
| **Controller** | Receives request → calls service → returns View or JsonResponse |
| **Service** | Business logic, DataTable setup, file uploads via UploadTrait |
| **Repository** | All Eloquent queries. Never call Eloquent from Services or Controllers |
| **Model** | `$fillable` whitelist, `$hidden`, `casts()` |

---

## ✅ DOs

- ✅ Follow the **Repository Pattern** — all DB queries in Repositories
- ✅ **Clean Code & Variable Naming**: Use full, descriptive, intention-revealing variable names (e.g. `$contactMessage`, `$traveler`, `$jobApplication`, `$contractTracker`, `$exception`).
- ✅ **PHP CS & PSR-12 Compliance**: Strictly adhere to PSR-12 coding standard. Run Laravel Pint (`vendor/bin/pint app`) to guarantee clean formatting.
- ✅ **PHP Mess Detector (PHPMD) Standards**: Keep methods focused and short, eliminate dead/unused code or imports, avoid high cyclomatic complexity.
- ✅ **Strict FormRequest Rule**: Always use an external dedicated `FormRequest` class for all input validation (both store and update operations) — NEVER validate inline inside any controller via `$request->validate()` or `Validator::make()`.
- ✅ Group Requests by domain in `app/Http/Requests/Admin/{Domain}/` (e.g. `StoreJobRequest`, `UpdateJobRequest`, `UpdateJobApplicationRequest`, `UpdateContactMessageRequest`).
- ✅ Keep controllers **thin**: validate through FormRequest type-hint → call service → return response
- ✅ Use `declare(strict_types=1)` and explicit return types on every method
- ✅ Use `$fillable` in every Model (never `$guarded = []`)
- ✅ Use `$request->validated()` — never `$request->all()`
- ✅ Use `__('messages.general.created_success')` etc. for all flash/JSON messages
- ✅ Use Arabic only in views and messages — no `_ar` / `_en` suffixes on columns
- ✅ Use `UploadTrait` for all file uploads — always call `uploadImage()`
- ✅ Use `tableAction()`, `tableCheckbox()`, `tableStatus()`, `tableIcon()` helpers in DataTables
- ✅ Use `MultiDeleteRequest` for all batch delete endpoints

---

## ❌ DON'Ts

- ❌ **DON'T** use single-letter variable names anywhere (e.g. `$m`, `$c`, `$t`, `$e`, `$a`, `$p`, `$k`, `$v`) — always write full descriptive names.
- ❌ **DON'T** write inline validation (`$request->validate(...)` or `Validator::make(...)`) inside any Controller — extract every validation into a FormRequest.
- ❌ **DON'T** leave unused imports or dead variables in any file.
- ❌ **DON'T** violate PSR-12 / PHP CS formatting rules (always verify with `vendor/bin/pint --test app`).
- ❌ **DON'T** create any API routes or API controllers
- ❌ **DON'T** use `auth('api')` — use `auth()` or `admin()` helper only
- ❌ **DON'T** use `auth()->id()` for the website contact form — no user auth on website
- ❌ **DON'T** add `_ar` / `_en` column suffixes — single Arabic field names only
- ❌ **DON'T** use `HasLocalizedAttributes` trait — it has been removed
- ❌ **DON'T** create `BusinessException` subclasses — use standard `DomainException` where needed
- ❌ **DON'T** query Eloquent directly from Services or Controllers
- ❌ **DON'T** install new packages without asking the user
- ❌ **DON'T** write tests for the Admin dashboard

---

## Naming Conventions

| Element | Convention | Example |
|---|---|---|
| Controller | `PascalCase` + `Controller` | `JobCategoryController` |
| Service | `PascalCase` + `Service` | `JobCategoryService` |
| Repository | `PascalCase` + `Repository` | `JobCategoryRepository` |
| DTO | `PascalCase` + `DTO` | `ContactMessageDTO` |
| FormRequest | `PascalCase` + `Request` | `StoreJobCategoryRequest` |
| Migration | snake_case with timestamp | `2024_01_01_create_job_categories_table` |
| Model | Singular PascalCase | `JobCategory` |
| DB Table | snake_case plural | `job_categories` |
| Route | kebab-case | `job-categories` |
| View folder | PascalCase matching domain | `Admin/JobCategory/` |

---

## Admin Dashboard CRUD Blueprint

### Standard Methods (Controller)
```php
index(Request $request): View|JsonResponse   // DataTable or index view
create(): View
store(StoreXRequest $request): JsonResponse
edit(Model $model): View
update(UpdateXRequest $request, Model $model): JsonResponse
destroy(Model $model): JsonResponse
multiDelete(MultiDeleteRequest $request): JsonResponse
```

### Standard Methods (Service)
```php
getDataTable(): JsonResponse    // DataTables::of($query)->addColumn(...)
store(array $data): Model
update(Model $model, array $data): bool
delete(Model $model): bool
deleteMultiple(array $ids): int
```

### Helpers Available
- `tableAction($id, $edit, $delete, $view = false)` — action buttons
- `tableCheckbox($id)` — batch select checkbox
- `tableStatus(bool $isActive)` — status badge
- `tableIcon(string $icon, string $fallback)` — icon display

---

## Contact Form Fields

The website contact form collects: `name`, `phone`, `message`, `city_id` (optional).
No `email`, no `subject`, no `booking_number`, no `user_id`.
