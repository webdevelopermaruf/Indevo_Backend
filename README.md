## Project Overview

Indevo is a personal life management app. This Laravel project exposes a secure, versioned REST API that powers:

| Module | Description |
|---|---|
| **Auth** | Register, login, Google OAuth, JWT tokens |
| **Dashboard** | Aggregated home screen data (balance, streak, alerts) |
| **Budgeting** | Income/expense tracking, category budgets, savings goals, currency conversion |
| **Reminders** | Micro-guidance reminders with priority, recurrence, and location triggers |
| **Goals** | Pending and completed financial/personal goals with progress tracking |
| **Progress** | XP system, levels, badges, milestones |
| **Skills** | Skill library with sub-items, completion tracking, and points |

---

## Tech Stack

| Layer | Technology |
|---|---|
| Framework | Laravel 11.x |
| Language | PHP 8.3+ |
| Database | MySQL 8.0|
| Auth | Laravel Sanctum (API tokens) + Socialite (Google OAuth) |
| Cache | Redis |
| Queue | Laravel Queues (Redis driver) |
| Push Notifications | Firebase Cloud Messaging (FCM) via `laravel-notification-channels/fcm` |
| File Storage | Cloudflare R2 |
| API Docs | Scramble |
| Testing | PHPUnit |

---

## Requirements

- PHP >= 8.3
- Composer >= 2.x
- MySQL 8.0
- Redis >= 6.x
- Laravel CLI (`composer global require laravel/installer`)

---

## Installation

### 1. Clone the Repository

```bash
git clone https://github.com/webdevelopermaruf/Indevo_Backend
cd Indevo_Backend
```

### 2. Install PHP Dependencies

```bash
composer install
```

### 3. Copy Environment File

```bash
cp .env.example .env
```

### 4. Generate Application Key

```bash
php artisan key:generate
```

### 5. Run Migrations & Seeders

```bash
php artisan migrate --seed
```

### 6. Start the Development Server

```bash
php artisan serve
```

API will be available at: `http://localhost:8000/api/v1`
