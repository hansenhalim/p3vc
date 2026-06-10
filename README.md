# P3VC — Paguyuban Pengelola Perumahan Villa Citra

Billing and dues-management system for the **Villa Citra** residential estate,
run by its management association (*Paguyuban Pengelola Perumahan Villa Citra*).
It tracks every property in the estate, the recurring dues (*iuran*) each one
owes, the payments collected against them, and the outstanding balance — and it
produces the invoices and reports the association needs to operate.

This README is written for a developer setting up, running, or maintaining the
app. If you read only one section, read [The Sync quirk](#the-sync-quirk-read-this)
— it is the single most surprising part of the system.

---

## Tech stack

| Layer        | Choice                                                            |
|--------------|-------------------------------------------------------------------|
| Framework    | Laravel 8                                                         |
| Language     | PHP `^7.2.5 \|\| ^8.0`                                            |
| Database     | MySQL                                                              |
| Auth / roles | [spatie/laravel-permission](https://github.com/spatie/laravel-permission) |
| PDF          | [barryvdh/laravel-dompdf](https://github.com/barryvdh/laravel-dompdf) |
| QR codes     | [simplesoftwareio/simple-qrcode](https://github.com/SimpleSoftwareIO/simple-qrcode) |
| Excel export | [maatwebsite/excel](https://github.com/SpartnerNL/Laravel-Excel) |
| Media        | [spatie/laravel-medialibrary](https://github.com/spatie/laravel-medialibrary) |
| Front-end    | CoreUI 3 (Bootstrap 4) admin template, compiled via Laravel Mix   |

The UI is server-rendered Blade. There is no SPA.

---

## Domain model

```
Cluster ──< Unit >── Customer
              │
              └──< Transaction >──< Payment
```

- **Cluster** — a group/category of properties that defines the dues rate. Each
  cluster has a `cost` and a `per` mode of either `mth` (a flat amount per month)
  or `sqm` (an amount per square metre of the unit's area).
- **Unit** — a single property in the estate. Has an `area_sqm` and belongs to a
  cluster (which sets its rate) and to a customer.
- **Customer** — the owner/occupant responsible for a unit's dues.
- **Transaction** — a billed item for a unit, scoped to a `period` (a month).
- **Payment** — money received; linked to transactions to settle them.

### Glossary

| Term       | Meaning                                                                       |
|------------|-------------------------------------------------------------------------------|
| **iuran**  | The recurring dues a unit owes (monthly or per-m² depending on its cluster).  |
| **period** | The month a transaction bills for.                                            |
| **credit** | The dues charged to a unit for a period: `cost × 1` (mth) or `cost × area_sqm` (sqm). |
| **balance**| Net of what's been charged vs. paid for a unit.                               |
| **debt**   | Outstanding amount a unit still owes.                                         |

> `credit`, `balance`, and `debt` are **not** stored on the unit — they are
> recomputed into a denormalized table. See [The Sync quirk](#the-sync-quirk-read-this).

---

## Roles & approval flow

Access is governed by three roles in a hierarchy (`master` > `supervisor` >
`operator`), enforced by route middleware (`role:...`).

| Role         | Can do                                                                                   |
|--------------|------------------------------------------------------------------------------------------|
| **operator** | Day-to-day data entry: manage clusters, customers, units, and record payments.           |
| **supervisor** | Everything an operator can, plus **approve** transactions and review the approvals queue. |
| **master**   | Full control: manage users, roles, and the menu/permission system, and **unapprove** transactions. |

Transactions follow an **approve → unapprove** workflow: an operator creates
them, a supervisor approves (`transactions/{id}/approve`), and only a master can
reverse an approval (`master.unapprove`). The approvals queue lives under
`/approvals`.

---

## Features

- **Unit & dues management** — register units, assign them to clusters and
  customers, and track per-unit debt (`/units/{unit}/debt`).
- **Payments & transactions** — record payments and reconcile them against billed
  periods, with a supervisor approval step.
- **PDF invoices with QR codes** — printable invoices rendered with dompdf and an
  embedded QR code (`resources/views/pdf/invoice.blade.php`,
  `transactions/{id}/print`).
- **Reports** — transaction reports, viewable and printable
  (`transactions/report`, `transactions/report/print`).
- **Excel exports** — export unit data to spreadsheets (`units/export/{type}`).
- **DB-driven menus & permissions** — the sidebar and per-role menu access are
  data-driven (`menus` / `menulist` / `menurole` / `role_hierarchy` tables),
  seeded by `MenusTableSeeder`.

---

## The Sync quirk (read this)

The units list, debts, and balances are **not computed on the fly**. They are
read from a denormalized `unit_shadows` table that caches each unit's
`credit`, `balance`, and `debt`.

That table is only refreshed when someone runs **Sync** (`POST units/sync`,
exposed as a button in the Units screen). Sync truncates `unit_shadows`, walks
every current unit with its transactions and payments, recomputes the numbers,
and stamps `configs.units_last_sync`.

**Consequence:** after you change clusters, units, transactions, or payments —
whether through the UI, a seeder, or a manual DB edit — the displayed debts and
balances are **stale until you run Sync**. If numbers look wrong, run Sync first
before assuming there's a bug.

---

## Local setup

**Prerequisites:** PHP 7.2–8.x, Composer, MySQL, Node.js, and npm.

```bash
# 1. Install PHP dependencies
composer install

# 2. Environment
cp .env.example .env
php artisan key:generate
#   then edit .env — set DB_DATABASE / DB_USERNAME / DB_PASSWORD (MySQL)

# 3. Database: run migrations and seed reference + demo data
php artisan migrate --seed

# 4. Front-end assets — REQUIRED.
#    Compiled CSS/JS are NOT committed; Laravel Mix copies CoreUI vendor
#    files into public/ and compiles the stylesheet.
npm install
npm run dev          # or `npm run watch` while developing

# 5. Serve
php artisan serve
```

Then open <http://localhost:8000>.

### Default login (from the seeder)

The seeder creates the association's users. The default master account is:

| Email                          | Password   |
|--------------------------------|------------|
| `fpsecond.hh@p3villacitra.com` | `password` |

> Change or remove these credentials before any non-local deployment.

### After seeding

The unit shadows are empty until you Sync. Log in, go to **Units**, and run
**Sync** so debts and balances populate. See
[The Sync quirk](#the-sync-quirk-read-this).

---

## Project structure

```
app/
  Http/Controllers/   Cluster, Customer, Unit, Transaction, Payment,
                      Approval, Master, Users, Roles, Menu* controllers
  Models/             Cluster, Unit, Customer, Transaction, Payment, …
database/
  migrations/         schema, incl. unit_shadows and the menu/role tables
  seeders/            DatabaseSeeder calls Users, Menus, Payment, Customer,
                      Cluster, Price, Unit seeders (Transaction seeders are
                      commented out)
resources/views/      Blade: cluster/ unit/ customer/ transaction/ approval/
                      master/ pdf/ (invoice) and layouts/
routes/web.php        all app routes, grouped by role middleware
webpack.mix.js        Laravel Mix asset pipeline (CoreUI vendor copy + SCSS)
```

---

## Project status

- This is a **production application** actively used by P3VC — treat data and
  credentials with care.
- The stack is **dated** (Laravel 8, PHP 7.2–8). Plan upgrades deliberately.
- **Sync is manual.** Cached unit debts/balances go stale until someone runs it.
- **Test coverage is minimal** — verify changes against real flows, not just the
  suite.

---

## License

MIT — see [LICENSE](LICENSE).
