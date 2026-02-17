# Laravel Core API

A RESTful API backend built with **Laravel 12** featuring authentication, role-based access control (RBAC), and user management. The project follows a clean architecture with the Repository-Service pattern, Sanctum token-based auth with access/refresh token flow, and Hashids for obfuscated public-facing IDs.

---

## Tech Stack

- **PHP** >= 8.2
- **Laravel** 12
- **Laravel Sanctum** 4.0 — Token-based API authentication
- **Hashids** (vinkla/hashids) — Obfuscated route IDs
- **MySQL** — Database

## Features

- **Authentication** — Register, login, logout, and token refresh using Laravel Sanctum with separate access and refresh tokens
- **Role-Based Access Control** — Roles with granular privilege strings checked via Sanctum abilities middleware
- **User Management** — Full CRUD with profile support (name, phone, photo)
- **Hashids Middleware** — Automatically decodes hashed IDs in route parameters for secure, non-sequential public IDs
- **Repository-Service Pattern** — Clean separation of data access (Repositories) and business logic (Services)
- **API Resources** — Consistent, route-aware JSON response transformation
- **Form Request Validation** — Centralized, route-aware validation with custom error messages
- **Standardized API Responses** — Shared `ApiResponse` trait for uniform success/error JSON structure
- **Soft Deletes** — Enabled on Users, Roles, Profiles, and Privileges
- **Database Seeders** — Pre-configured roles, privileges, and a default admin account

## Project Structure

```
app/
├── Http/
│   ├── Controllers/Api/
│   │   ├── AuthController.php      # Authentication endpoints
│   │   └── UserController.php      # User CRUD endpoints
│   └── Middleware/
│       └── DecodeHashids.php       # Decodes hashed route parameters
├── Models/
│   ├── User.php                    # User model (Sanctum, SoftDeletes)
│   ├── Role.php                    # Role model with privileges
│   ├── Profile.php                 # User profile model
│   └── Privilege.php               # Privilege reference model
├── Repositories/
│   ├── UserRepository.php          # User data access
│   ├── ProfileRepository.php       # Profile data access
│   ├── RoleRepository.php          # Role data access
│   └── PrivilegeRepository.php     # Privilege data access
├── Requests/
│   ├── AuthRequest.php             # Auth validation (register/login)
│   └── UserRequest.php             # User validation (update)
├── Resources/
│   ├── AuthResource.php            # Auth response transformation
│   └── UserResource.php            # User response transformation
├── Services/
│   ├── AuthService.php             # Auth business logic
│   └── UserService.php             # User business logic
└── Traits/
    └── ApiResponse.php             # Standardized JSON responses
```

## Database Schema

| Table                    | Description                                      |
|--------------------------|--------------------------------------------------|
| `users`                  | Email, password, status, role foreign key         |
| `profiles`               | Name, phone, photo — belongs to a user            |
| `roles`                  | Role name and comma-separated privilege strings   |
| `privileges`             | Privilege definitions grouped by feature          |
| `personal_access_tokens` | Sanctum token storage with expiration support     |

## Installation

### 1. Clone the repository

```bash
git clone <repository-url>
cd laravel_core
```

### 2. Install dependencies

```bash
composer install
```

### 3. Environment configuration

```bash
cp .env.example .env
php artisan key:generate
```

Edit `.env` and configure your database connection:

```dotenv
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

Optionally configure Sanctum token expiration (defaults shown):

```dotenv
SANCTUM_ACCESS_TOKEN_EXPIRATION=1440       # 24 hours in minutes
SANCTUM_REFRESH_TOKEN_EXPIRATION=43200     # ~30 days in minutes
```

### 4. Run migrations and seeders

```bash
php artisan migrate
php artisan db:seed
```

This creates the database schema and seeds default privileges, roles, and the admin user.

### 5. Start the development server

```bash
php artisan serve
```

The API will be available at `http://localhost:8000/api`.

## Deployment

### 1. Server requirements

- PHP >= 8.2 with extensions: `mbstring`, `xml`, `bcmath`, `ctype`, `fileinfo`, `json`, `openssl`, `pdo`, `tokenizer`
- Composer
- MySQL server
- A web server (Nginx or Apache)

### 2. Deploy steps

```bash
# Clone and enter the project
git clone <repository-url>
cd laravel_core

# Install PHP dependencies (optimized for production)
composer install --no-dev --optimize-autoloader

# Environment setup
cp .env.example .env
php artisan key:generate
# Edit .env with production database credentials and APP_ENV=production, APP_DEBUG=false

# Run migrations and seed
php artisan migrate --force
php artisan db:seed --force

# Optimize for production
php artisan config:cache
php artisan route:cache

# Start the server
php artisan serve
```

### 3. Web server configuration

Point your web server's document root to the `public/` directory. Example Nginx config:

```nginx
server {
    listen 80;
    server_name your-domain.com;
    root /path/to/laravel_core/public;

    index index.php;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

### 4. Directory permissions

```bash
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

### 5. Queue worker (if using queues)

```bash
php artisan queue:work --tries=3
```

Consider using a process manager like Supervisor to keep the queue worker running.

## Testing

```bash
composer test
```

Or directly:

```bash
php artisan test
```
