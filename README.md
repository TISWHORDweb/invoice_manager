# Invoice Manager

A web application for managing sales invoices. Create, view, edit, and delete invoices with line items, filtering, and file attachments.

## Features

- **CRUD** — Full create, read, update, and delete for invoices
- **Line items** — Multiple items per invoice with quantity, unit price, and automatic totals
- **Filtering** — Filter by date range, payment status, and search by invoice number or client name
- **File attachments** — Upload, download, and remove files (PDF, DOC, images) per invoice
- **Payment status** — Pending, Paid, Overdue, Cancelled with clear badges
- **Responsive UI** — Works on desktop, tablet, and mobile

## Tech Stack

| Layer    | Technology        |
| -------- | ------------------ |
| Backend  | PHP 8.2+, Laravel 11 |
| Database | SQLite (default), MySQL supported |
| Frontend | HTML, CSS, JavaScript (Blade templates) |

## Requirements

- PHP 8.2 or higher (extensions: bcmath, ctype, fileinfo, json, mbstring, openssl, pdo, tokenizer, xml)
- [Composer](https://getcomposer.org)
- SQLite (bundled with PHP) or MySQL / MariaDB

## Installation

```bash
# Clone or enter the project directory
cd virtual_switch_assesment

# Install dependencies
composer install

# Environment setup
cp .env.example .env
php artisan key:generate

# Database (SQLite by default)
touch database/database.sqlite
php artisan migrate
php artisan db:seed   # optional: demo data
```

## Running the Application

```bash
php artisan serve
```

Open **http://localhost:8000** in your browser.

## Configuration

- **Database** — Edit `.env` for MySQL: set `DB_CONNECTION=mysql`, `DB_HOST`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`, then run `php artisan migrate`.
- **File uploads** — Stored under `storage/app/invoice-attachments/`. Max size: `UPLOAD_MAX_SIZE_KB` in `.env` (default 5120 KB). Allowed types: PDF, DOC, DOCX, JPG, JPEG, PNG, GIF.

## Project Structure

```
app/
  Http/Controllers/   # InvoiceController, InvoiceAttachmentController
  Http/Requests/      # StoreInvoiceRequest, UpdateInvoiceRequest
  Models/             # Invoice, InvoiceItem, InvoiceAttachment
  Policies/           # InvoicePolicy, InvoiceAttachmentPolicy
  Services/           # InvoiceService
database/
  migrations/         # invoices, invoice_items, invoice_attachments
  seeders/            # InvoiceSeeder
resources/views/      # Blade templates (layouts, invoices)
routes/web.php        # Web routes
public/css/app.css    # Application styles
```

## Troubleshooting

| Issue | Solution |
| ----- | -------- |
| `bootstrap/cache` not writable | `mkdir -p bootstrap/cache && chmod 775 bootstrap/cache` then `composer install` |
| Autoload / class not found | `composer dump-autoload` |
| Storage or cache permission errors | `chmod -R 775 storage bootstrap/cache` |
| Session or cache errors | Run `php artisan migrate` (sessions use database) |
| PHP 8.5+ PDO deprecation or 500 | See vendor patch note in `.env` or suppress in `public/index.php`; re-apply after `composer update` if needed |

## License

MIT
