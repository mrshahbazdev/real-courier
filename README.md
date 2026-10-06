# Shipshares — Courier Tracking Site (Laravel)

Full courier company website + admin panel:

- **Public site**: hero banner, services, about + stats, how-it-works, contact strip, footer.
- **Tracking**: `/track?tracking_no=XXX` renders a shipment card (sender, consignee, package info, charges table with total, payment method badges, tracking history timeline, Code-128 barcode + QR, signature).
- **Admin** (`/admin`, default `admin@shipshares.com` / `password`):
  - Create/edit/delete shipments — tracking no, status, dates, sender/consignee, description.
  - Per-shipment **charges** (any label + amount, e.g. Registration Fee, Income Tax, Signature Charges) with auto total.
  - Per-shipment **tracking events** (status, location, time).
  - **Site Settings**: company name, tagline, contact info, logo, signature image, hero/about images, hero & about text, service cards, stats, footer text, enabled payment methods.
  - Change admin email/password.

No build step required — plain CSS in `public/css/app.css`, uploads go to `public/uploads` (works on shared hosting, no `storage:link` needed).

## Local setup

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan serve
```

## Shared hosting deploy

1. Upload all files (run `composer install --no-dev` locally and include `vendor/` if the host has no Composer).
2. Point the domain docroot to `public/` — or leave it at project root; the bundled root `.htaccess` rewrites into `public/` automatically.
3. Create a MySQL database, set `DB_*` in `.env` (`DB_CONNECTION=mysql`).
4. `php artisan key:generate && php artisan migrate --seed`
5. Log in at `/admin` and change the admin password.
