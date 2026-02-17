# How to run the Invoice Manager application

This document describes how to set up and run the Laravel-based **Invoice Manager** web application on your machine.

## Prerequisites

- **PHP 8.2 or higher** (with extensions: bcmath, ctype, fileinfo, json, mbstring, openssl, pdo, tokenizer, xml)
- **Composer** ([getcomposer.org](https://getcomposer.org))
- **SQLite** (usually bundled with PHP) or **MySQL** / **MariaDB** if you prefer

## Quick start (SQLite)

1. **Clone or open the project** and go to the project directory:
   ```bash
   cd /path/to/virtual_switch_assesment
   ```

2. **Install PHP dependencies**
   ```bash
   composer install
   ```

3. **Environment and key**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Database**
   - SQLite is used by default. Create the database file:
     ```bash
     touch database/database.sqlite
     ```
   - Run migrations:
     ```bash
     php artisan migrate
     ```
   - (Optional) Seed demo data:
     ```bash
     php artisan db:seed
     ```

5. **Start the development server**
   ```bash
   php artisan serve
   ```
   Then open **http://localhost:8000** in your browser. You will be redirected to the invoice list.

## Using MySQL instead of SQLite

1. Create a database (e.g. `invoice_manager`).
2. In `.env`, set:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=invoice_manager
   DB_USERNAME=your_user
   DB_PASSWORD=your_password
   ```
3. Run:
   ```bash
   php artisan migrate
   php artisan db:seed   # optional
   php artisan serve
   ```

## File uploads

- Uploaded files are stored under `storage/app/invoice-attachments/` (private to the app).
- Max file size is controlled by `UPLOAD_MAX_SIZE_KB` in `.env` (default 5120 KB).
- Allowed types: PDF, DOC, DOCX, JPG, JPEG, PNG, GIF.

## What you can do in the app

- **List invoices** – with filters by date range, payment status, and search (number/client).
- **Create invoice** – invoice header + multiple line items; totals and tax are calculated.
- **View invoice** – details, line items, and attachments; upload or remove files.
- **Edit invoice** – change header and line items.
- **Delete invoice** – soft delete (can be restored from DB if needed).

## Troubleshooting

- **"Class not found" or autoload errors**  
  Run: `composer dump-autoload`

- **Permission errors on storage or cache**  
  On Linux/macOS:
  ```bash
  chmod -R 775 storage bootstrap/cache
  ```

- **Session or cache errors**  
  Ensure migrations have been run (`php artisan migrate`) so the `sessions` and `cache` tables exist.

- **Blank page or 500 error**  
  Set `APP_DEBUG=true` in `.env` and check `storage/logs/laravel.log` for the error message.
