# ShopZone — Laravel + PostgreSQL

Ecommerce / POS application rebuilt on **Laravel** with a **secure PostgreSQL** database.

Originally a custom PHP (MySQL/XAMPP) app; now a modern Laravel project with:

- Role-based access (Owner, Cashier, Customer)
- Product catalog (shoes & clothing) with stock and sizes
- Owner dashboard and product management (add / edit / deactivate)
- Public shop index and product listing
- PostgreSQL (dedicated non-superuser role)

## Requirements

- PHP 8.2+ with extensions: `pdo_pgsql`, `mbstring`, `xml`, `bcmath`, `curl`, `zip`, `fileinfo`
- Composer 2
- PostgreSQL 14+

## Quick start

```bash
# 1. Install dependencies
composer install

# 2. Environment
cp .env.example .env   # or use the included .env
php artisan key:generate

# 3. Create PostgreSQL database & user (as postgres superuser)
sudo -u postgres psql -c "CREATE USER ecom_user WITH PASSWORD 'SecureEcom2026Pg' CREATEDB;"
sudo -u postgres psql -c "CREATE DATABASE ecom_laravel OWNER ecom_user;"
sudo -u postgres psql -d ecom_laravel -c "GRANT ALL ON SCHEMA public TO ecom_user;"

# 4. Migrate & seed
php artisan migrate --seed
php artisan storage:link

# 5. Run
php artisan serve
```

Open http://127.0.0.1:8000

## Default logins

| Role    | Username | Password |
|---------|----------|----------|
| Owner   | admin    | admin    |
| Cashier | cashier  | admin    |

**Change these passwords in production.**

## Main routes

| URL | Description |
|-----|-------------|
| `/` | Shop home (featured & deals) |
| `/shop/products` | Product catalog |
| `/login` | Login |
| `/owner/dashboard` | Owner dashboard |
| `/owner/products` | Add / edit / list products |

## Environment (PostgreSQL)

```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=ecom_laravel
DB_USERNAME=ecom_user
DB_PASSWORD=SecureEcom2026Pg
```

## Project structure

```
app/
  Http/Controllers/AuthController.php
  Http/Controllers/Owner/   # Dashboard, Product management
  Http/Controllers/Shop/    # Public shop
  Http/Middleware/EnsureRole.php
  Models/                   # User, Product, Category, Supplier, ...
database/
  migrations/               # Full ecom schema
  seeders/DatabaseSeeder.php
resources/views/
  auth/login.blade.php
  owner/                    # dashboard, products
  shop/                     # index, products, product
  layouts/app.blade.php
routes/web.php
```

## Security notes

- Application uses a **dedicated PostgreSQL role** (`ecom_user`), not the superuser.
- Passwords are hashed with bcrypt (`Hash` / Eloquent casts).
- CSRF protection on all forms.
- Role middleware (`role:Owner,Cashier`) guards admin routes.
- Session cookies: HTTP-only; configure `SESSION_SECURE_COOKIE=true` behind HTTPS.

## Product add (admin)

1. Login as **admin / admin**
2. Go to **Manage products** → **+ Add Product**
3. Fill name, SKU, department, selling price (> 0), quantity
4. Save — product appears in the admin list and on the shop when `status=active` and `quantity > 0`

## Migrating from the legacy PHP app

1. Export data from the old MySQL/PostgreSQL `ecom` database if needed.
2. Point this Laravel app at a fresh database and run `migrate --seed`, or write custom import commands.
3. Map legacy columns (`password_hash` → `password`, etc.) in an import seeder.

## License

Proprietary / internal use for ShopZone Kenya demo.
