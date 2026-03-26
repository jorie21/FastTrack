# Gemini Project Context: FastTrack

## Project Overview
FastTrack is a modern financial tracking application built with **Laravel 12.0** and **Vue 3** (via **Inertia.js**). It provides a robust backend with a clean architecture and a polished, responsive frontend using **Tailwind CSS 4**.

### Key Technologies
- **Backend:** Laravel 12.0 (PHP 8.2+), Fortify (Authentication), Sanctum (API), Wayfinder.
- **Frontend:** Vue 3 (Composition API), TypeScript, Inertia.js, Vite, Tailwind CSS 4, Lucide Icons.
- **Database:** SQLite (Default), supporting MySQL/PostgreSQL.
- **Testing:** Pest PHP (Backend), Prettier & ESLint (Frontend).

## Architectural Patterns
The project follows a structured approach to ensure maintainability:

### 1. Models & Query Builders
- **Location:** Models in `app/Models/`, Custom Builders in `app/Services/QueryBuilders/`.
- **Pattern:** Models override `newEloquentBuilder` to return a specialized Query Builder class. This isolates complex query logic from the model.

### 2. Repositories
- **Location:** `app/Repositories/`.
- **Pattern:** Encapsulates data access and business logic (e.g., `TransactionRepository` handles complex creation/retrieval). Controllers delegate to repositories to remain "thin."

### 3. Validation & Authorization
- **Requests:** Custom form requests in `app/Http/Requests/` handle validation.
- **Policies:** Located in `app/Policies/` for model-level authorization.

### 4. Frontend Bridge (Inertia)
- **Middleware:** `HandleInertiaRequests` bridges backend data to Vue components.
- **Components:** Vue pages in `resources/js/pages/`, reusable components in `resources/js/components/`.

## Development Workflows

### Setup & Installation
```bash
composer setup  # Installs PHP/JS deps, sets up .env, runs migrations, and builds frontend
```

### Running the Application
```bash
composer dev    # Runs server, queue, and Vite concurrently
```

### Testing
```bash
composer test             # Runs Pest tests and lints code
php artisan test          # Standard Laravel test runner
vendor/bin/pest           # Pest-specific runner
```

### Code Quality & Linting
```bash
composer lint             # Backend: Fixes code style using Laravel Pint
npm run lint              # Frontend: Runs ESLint with auto-fix
npm run format            # Frontend: Runs Prettier
npm run types:check       # Frontend: Runs vue-tsc type checking
```

### Deployment (Production)
```bash
npm run build             # Compiles assets for production
php artisan migrate --force # Runs migrations in production
```

## Key Modules
- **Authentication:** Managed by Laravel Fortify (`app/Actions/Fortify/`).
- **Finance Engine:** Core logic for `Transactions`, `Categories`, and `Wallets` located in respective Models, Repositories, and Query Builders.
- **User Preferences:** `HandleAppearance` middleware manages UI themes and preferences.

## Conventions
- **Naming:** Follow PSR-12 for PHP and standard Vue/TypeScript conventions.
- **Type Safety:** Use TypeScript for all frontend components and composables.
- **Testing:** New features MUST include Pest feature tests in `tests/Feature/`.
- **Commits:** Prefer clear, concise commit messages following standard Git practices.
