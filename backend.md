# Backend Documentation

This project is built using the **Laravel 12.0** framework, providing a robust and scalable backend for the application. It follows a clean architecture pattern by separating concerns into Models, Query Builders, Repositories, and Controllers.

## Tech Stack

- **Framework:** [Laravel 12.0](https://laravel.com/docs/12.x)
- **Authentication:** [Laravel Fortify](https://laravel.com/docs/12.x/fortify) (Headless Auth) & [Laravel Sanctum](https://laravel.com/docs/12.x/sanctum) (API Tokens)
- **Frontend Bridge:** [Inertia.js](https://inertiajs.com/)
- **Database:** SQLite (Default), supports MySQL/MariaDB/PostgreSQL.
- **Testing:** [Pest PHP](https://pestphp.com/)

---

## Architectural Patterns

The backend follows a specific structural pattern to ensure maintainability:

### 1. Models & Query Builders
Models represent the database tables. To keep models clean, complex query logic is moved into specialized **Query Builder** classes.
- **Models:** Located in `app/Models/`.
- **Query Builders:** Located in `app/Services/QueryBuilders/`.
- **Integration:** Models override the `newEloquentBuilder` method to return the custom builder.

### 2. Repositories
Data access and business logic are encapsulated in **Repositories** (`app/Repositories/`). This prevents controllers from being bloated with database logic.
- **Example:** `TransactionRepository` handles the creation and retrieval of transactions, including transaction management (`DB::beginTransaction()`).

### 3. Controllers
Controllers (`app/Http/Controllers/`) are kept "thin." They primarily handle request routing and delegate tasks to Repositories.
- **Web Controllers:** Use Inertia to render Vue components.
- **API Controllers:** Return JSON responses.

### 4. Validation & Authorization
- **Requests:** Custom form requests in `app/Http/Requests/` handle validation.
- **Policies:** Located in `app/Policies/`, they define authorization rules for models.

### 5. Middleware & Concerns
- **Middleware:** Located in `app/Http/Middleware/`.
    - `HandleInertiaRequests`: Bridges the backend data to the frontend Vue components.
    - `HandleAppearance`: Manages user-specific UI preferences (e.g., themes).
- **Concerns (Traits):** Located in `app/Concerns/`. These are shared traits for reusability, such as common validation rules for passwords and profiles.

---

## Key Modules

### Authentication
Handled by **Laravel Fortify**.
- Configuration: `config/fortify.php`
- Custom Logic: `app/Actions/Fortify/` (e.g., `CreateNewUser`, `ResetUserPassword`)
- Features: Registration, Password Reset, Email Verification, Two-Factor Authentication.

### Finance (Transactions & Categories)
- **Transactions:** Tracks financial entries with amount, date, and description.
- **Categories:** Organizes transactions into user-defined categories.
- **Querying:** Supports filtering by user, category, date, and keyword search via the custom Query Builders.

---

## API Endpoints

Authenticated routes are protected by the `auth:sanctum` middleware.

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/api/transactions` | List user transactions (with filtering) |
| POST | `/api/transactions` | Create a new transaction |
| DELETE | `/api/transactions/{uuid}` | Delete a transaction |
| GET | `/api/categories` | List user categories |
| POST | `/api/categories` | Create a new category |
| DELETE | `/api/categories/{uuid}` | Delete a category |

---

## Database Schema

- `users`: Standard Laravel user table with Fortify extensions.
- `categories`: `id`, `name`, `description`, `user_id`.
- `transactions`: `id`, `uuid`, `user_id`, `category_id`, `amount`, `transaction_date`, `description`, `created_by_id`.

---

## Development & Testing

### Commands
- **Run Migrations:** `php artisan migrate`
- **Seed Database:** `php artisan db:seed`
- **Run Tests:** `php artisan test` or `vendor/bin/pest`
- **Lint Code:** `composer lint` (uses Laravel Pint)

### Testing Suite
The project uses **Pest PHP** for testing.
- **Feature Tests:** `tests/Feature/` (e.g., `DashboardTest.php`)
- **Unit Tests:** `tests/Unit/`
