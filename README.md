# CRUD System — Products

A minimal, dependency-free **CRUD (Create, Read, Update, Delete)** web application for managing a catalogue of products. Built as a workshop exercise using plain **PHP** + **MySQL/MariaDB** on a **XAMPP** stack, styled with a locally vendored copy of **Bootstrap 5**.

The entire application is five procedural PHP files backed by a single database table. There is no framework, no build step, and no package manager — you clone it, start XAMPP, and it runs.

---

## Table of Contents

- [Overview](#overview)
- [Tech Stack](#tech-stack)
- [Features](#features)
- [Project Structure](#project-structure)
- [Database Structure](#database-structure)
- [Setup / Installation](#setup--installation)
- [How It Works](#how-it-works)
- [Configuration](#configuration)
- [API Reference](#api-reference)
- [Known Issues & Limitations](#known-issues--limitations)
- [Security Notes](#security-notes)
- [Future Improvements](#future-improvements)
- [License](#license)

---

## Overview

| | |
|---|---|
| **Project** | CRUD System — Products |
| **Repository** | <https://github.com/omar-syntax/CRUD-System> |
| **Default branch** | `main` |
| **Entry point** | `index.php` |
| **Architecture** | Multi-file procedural PHP, one page per action, rendered server-side |
| **Data store** | Single MySQL/MariaDB table (`products`) |
| **Auth** | None |

The app renders a table of all products and exposes four operations — list, create, update, and delete. All mutations are plain HTML form submissions handled by dedicated PHP scripts, which then redirect back to `index.php` using the Post/Redirect/Get pattern.

---

## Tech Stack

| Layer | Technology | Version (as installed) | Notes |
|---|---|---|---|
| **Language** | PHP | 8.2.12 | Procedural style, no OOP, no namespaces |
| **Web server** | Apache | via XAMPP | Serves the project directly from `htdocs` |
| **Database** | MariaDB | 10.4.32 | MySQL-compatible; bundled with XAMPP |
| **DB driver** | `mysqli` (procedural) | — | `mysqli_connect` / `mysqli_query`; **not** PDO, **not** prepared statements |
| **Frontend CSS** | Bootstrap | 5.3.8 | Vendored locally in `css/`, not loaded from a CDN |
| **Frontend JS** | Bootstrap Bundle | 5.3.8 | Includes Popper; vendored locally in `js/` |
| **JS written** | Vanilla JavaScript | — | Only wires up modal fields; no framework |
| **Templating** | Raw PHP in HTML | — | `include` of a shared header/footer partial |

**Local environment (reference machine)**

```
PHP     8.2.12  (cli) — ZTS Visual C++ 2019 x64
MariaDB 10.4.32-MariaDB
Bootstrap 5.3.8 (MIT)
XAMPP   (Windows)
```

> **No dependencies to install.** Bootstrap's compiled distribution is committed into `css/` and `js/`, so there is no `composer.json`, no `package.json`, and no `node_modules`.

---

## Features

- **Read** — Display every product in a responsive Bootstrap table (`id`, `name`, `description`, `price`, `created_at`).
- **Create** — Insert a new product through a modal form.
- **Update** — Edit an existing product's name, description, and price through a modal form.
- **Delete** — Remove a product after confirmation in a "Danger Action" modal.
- **Empty-state handling** — The table renders with an empty `<tbody>` when there are no rows, rather than erroring.
- **Post/Redirect/Get** — All write actions end in a `Location: index.php` redirect, so a browser refresh cannot resubmit a form.
- **Shared layout** — `includes/header.php` and `includes/footer.php` keep markup consistent across pages.
- **Responsive** — Bootstrap 5 grid and `viewport` meta tag handle mobile layouts.

**Not implemented:** user accounts, authentication, roles/permissions, pagination, search, sorting, filtering, input validation, image upload, CSV import/export, activity logging.

---

## Project Structure

```
workshop-5/
├── index.php            # Main page: lists all products + Add/Edit/Delete modals
├── create.php           # Action endpoint — handles INSERT (POST)
├── edit.php             # Action endpoint — handles UPDATE (POST)
├── delet.php            # Action endpoint — handles DELETE (POST)  [sic: "delet"]
├── show.php             # Placeholder for a detail view — currently EMPTY (0 bytes)
├── README.md            # This file
│
├── includes/
│   ├── config.php       # Database credentials + mysqli connection
│   ├── header.php       # <head>, Bootstrap CSS link, opening navbar
│   └── footer.php       # Footer markup + Bootstrap bundle script tag
│
├── css/                 # Bootstrap 5.3.8 compiled CSS (all build variants)
│   ├── bootstrap.min.css            ← used by the app
│   ├── bootstrap.css
│   ├── bootstrap.rtl.min.css        (right-to-left locale support)
│   ├── bootstrap-grid.min.css       (grid-only build)
│   ├── bootstrap-reboot.min.css     (reboot-only build)
│   ├── bootstrap-utilities.min.css  (utilities-only build)
│   └── *.map                        (source maps, for devtools debugging)
│
└── js/                  # Bootstrap 5.3.8 compiled JS (all build variants)
    ├── bootstrap.bundle.min.js      ← used by the app (includes Popper)
    ├── bootstrap.bundle.js
    ├── bootstrap.min.js             (no Popper — modals still work)
    ├── bootstrap.esm.min.js         (ES module build)
    └── *.map                        (source maps)
```

### File responsibilities

| File | Responsibility |
|---|---|
| `index.php` | The only page that renders data. Runs `SELECT * FROM products`, builds the table, hosts three Bootstrap modals, and contains the inline JS that populates them. |
| `create.php` | Reads `name`, `describtion`, `price` from `$_POST`; runs an `INSERT`; redirects to `index.php`. |
| `edit.php` | Reads `id`, `name`, `description`, `price` from `$_POST`; runs an `UPDATE ... WHERE id = ?`; redirects to `index.php`. |
| `delet.php` | Reads `id` from `$_POST`; runs a `DELETE ... WHERE id = ?`; redirects to `index.php`. |
| `show.php` | Intended as a single-product view. **Empty.** The "Show" button on `index.php` is a dead `href="#"`. |
| `includes/config.php` | Holds hardcoded host/user/password/database and opens the connection, calling `die()` on failure. |
| `includes/header.php` | Document skeleton, page title, Bootstrap CSS `<link>`, and a collapsible navbar. |
| `includes/footer.php` | Footer and the Bootstrap bundle `<script>` that powers the modals. |

> **Note on naming:** the delete handler is `delet.php` (missing the "e"). It is referenced from `index.php` as `action="delet.php"`, so it works — but be aware of it when navigating directly or when adding new links.

---

## Database Structure

### Database

| Property | Value |
|---|---|
| **Name** | `workshop_5` |
| **Charset** | `utf8mb4` |
| **Collation** | `utf8mb4_general_ci` |
| **Engine** | InnoDB |

### Tables

The schema consists of **one table: `products`**.

| Table | Purpose | Rows (dev) |
|---|---|---|
| `products` | Stores the product catalogue | 2 |

### `products` — column definitions

| Column | Type | Null | Key | Default | Extra | Description |
|---|---|---|---|---|---|---|
| `id` | `int(11)` | NO | **PRI** | — | `auto_increment` | Surrogate primary key. |
| `name` | `varchar(100)` | NO | — | — | — | Product name. |
| `description` | `text` | YES | — | `NULL` | — | Free-form product description. |
| `price` | `double` | NO | — | — | — | Product price (unitless). |
| `created_at` | `timestamp` | NO | — | `current_timestamp()` | `on update current_timestamp()` | Creation time — **also** refreshed on every update. |

### Relationships & indexes

- **Foreign keys:** none. The schema has a single table, so there are no relationships.
- **Indexes:** one — the `PRIMARY KEY` on `id`. There are **no secondary indexes**; any future filtering on `name` or `price` would require a full table scan.
- **Constraints:** none beyond `NOT NULL` and the primary key.

### Full DDL

The exact definition, as returned by `SHOW CREATE TABLE products`:

```sql
CREATE TABLE `products` (
  `id`          int(11)      NOT NULL AUTO_INCREMENT,
  `name`        varchar(100) NOT NULL,
  `description` text         DEFAULT NULL,
  `price`       double       NOT NULL,
  `created_at`  timestamp    NOT NULL DEFAULT current_timestamp()
                            ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_general_ci;
```

### ER diagram

```
┌───────────────────────────────────────┐
│             products                  │
├───────────────────────────────────────┤
│ PK  id           int(11)  AI          │
│     name         varchar(100) NOT NULL│
│     description  text         NULL    │
│     price        double       NOT NULL│
│     created_at   timestamp    NOT NULL│
│                 DEFAULT now()         │
│                 ON UPDATE now()       │
└───────────────────────────────────────┘
        (single table — no relations)
```

### Creating the database from scratch

```sql
CREATE DATABASE IF NOT EXISTS `workshop_5`
  DEFAULT CHARACTER SET utf8mb4
  COLLATE utf8mb4_general_ci;

USE `workshop_5`;

CREATE TABLE `products` (
  `id`          int(11)      NOT NULL AUTO_INCREMENT,
  `name`        varchar(100) NOT NULL,
  `description` text         DEFAULT NULL,
  `price`       double       NOT NULL,
  `created_at`  timestamp    NOT NULL DEFAULT current_timestamp()
                            ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_general_ci;
```

### Optional sample data

```sql
USE `workshop_5`;

INSERT INTO `products` (`name`, `description`, `price`) VALUES
  ('macbook air',      'The best laptop',         80000),
  ('Mercedes S-class', 'The most luxurious car',  20000000);
```

---

## Setup / Installation

### Prerequisites

- **XAMPP** (or any Apache + PHP 8.0+ + MySQL/MariaDB stack)
- PHP with the **`mysqli`** extension enabled
- The project folder placed inside the web root (`htdocs`)

### Steps

1. **Start the services.** Open the XAMPP Control Panel and start **Apache** and **MySQL**.

2. **Place the project.** The folder must be the docroot's child directory named `workshop-5` (or adjust the URLs accordingly):

   ```
   C:\xampp\htdocs\workshop-5\
   ```

3. **Create the database.** Open <http://localhost/phpmyadmin> and run the [DDL script](#full-ddl) above. This creates the `workshop_5` database and its `products` table.

4. **Verify credentials.** Confirm `includes/config.php` matches your local MySQL credentials — XAMPP's defaults are `root` with an empty password.

5. **Open the app:**

   ```
   http://localhost/workshop-5/index.php
   ```

### Expected result

The page shows a black navbar, the heading **Products**, an **Add product** button, and a table listing every row in `products`.

### Troubleshooting

| Symptom | Likely cause | Fix |
|---|---|---|
| `Can't vonnect to the database.` | MySQL is not running | Start MySQL in the XAMPP Control Panel |
| Same error, MySQL is running | Wrong credentials | Check `includes/config.php` |
| Same error, DB missing | `workshop_5` not created | Create it in phpMyAdmin using the DDL above |
| Blank page | PHP fatal error | Enable `display_errors` in `php.ini`, or check the Apache error log |
| Modals do not open | JS failed to load | Confirm `js/bootstrap.bundle.min.js` exists in the project |
| Navbar hamburger does nothing | Bootstrap 4 syntax on a Bootstrap 5 navbar | See [Known Issues](#known-issues--limitations) |
| CSS/JS 404s | Project not inside `htdocs` | Move the folder under the docroot |

---

## How It Works

### Request lifecycle

1. A browser requests `index.php`.
2. `index.php` includes `includes/config.php`, which opens a `mysqli` connection to `workshop_5`.
3. `SELECT * FROM products` runs, and rows are streamed into the table with `mysqli_fetch_assoc()`.
4. `includes/header.php` and `includes/footer.php` wrap the page chrome.
5. The browser receives fully rendered HTML — the client-side JavaScript only handles modal behaviour.

### Create

```
index.php (Add modal form, method="post")
        │
        ▼
create.php ── INSERT INTO `products`(`name`,`description`,`price`) VALUES (...)
        │
        ▼
   header("location:index.php")   ← Post/Redirect/Get
```

Note: the form field is named `describtion` (misspelled) and `create.php` reads `$_POST["describtion"]` — the two match, so it functions, but the column is `description`.

### Update

```
index.php (Edit modal form)
   │  JS reads data-id / data-name / data-description / data-price
   │  off the clicked button and fills the hidden id + text inputs
   ▼
edit.php ── UPDATE `products` SET `name`=?, `description`=?, `price`=? WHERE `id`=?
        │
        ▼
   header("location:index.php")
```

The Edit button carries the row's data as HTML `data-*` attributes. Values are escaped with `htmlspecialchars($row['name'], ENT_QUOTES)` when written into those attributes.

### Delete

```
index.php (Delete modal form)
   │  JS copies the clicked button's data-id into a hidden input
   ▼
delet.php ── DELETE FROM `products` WHERE `id`=?
        │
        ▼
   header("location:index.php")
```

### Modal wiring (vanilla JS)

Bootstrap exposes lifecycle events on each modal. `index.php` listens for `show.bs.modal` and populates the form fields from the button that triggered it:

```js
document.getElementById('editmodal').addEventListener('show.bs.modal', function (event) {
    var button = event.relatedTarget;          // the clicked Edit button
    document.getElementById('editId').value          = button.getAttribute('data-id');
    document.getElementById('editName').value        = button.getAttribute('data-name');
    document.getElementById('editDescription').value = button.getAttribute('data-description');
    document.getElementById('editPrice').value       = button.getAttribute('data-price');
});
```

Because the value passes through an HTML attribute, `htmlspecialchars` prevents attribute-breakout — but this is a client-side convenience only and provides no security guarantee.

---

## Configuration

All configuration lives in **`includes/config.php`** as plain variables — there is no `.env` file and no environment-variable support.

| Variable | Default | Purpose |
|---|---|---|
| `$host` | `localhost` | MySQL host. Use `127.0.0.1` if `localhost` forces a slow socket connection. |
| `$username` | `root` | MySQL user. |
| `$password` | *(empty)* | MySQL password. |
| `$dbname` | `workshop_5` | Target database. |
| `$conn` | — | The `mysqli` connection handle, reused by every included script. |

```php
<?php

$host = "localhost";
$username = "root";
$password = "";
$dbname = "workshop_5";
$conn = mysqli_connect($host, $username, $password, $dbname);

if (!$conn) {
    die("Can't vonnect to the database.");
};
```

On failure the app calls `die()` with a plain-text message. **These credentials are committed to version control** — see [Security Notes](#security-notes).

---

## API Reference

There is no JSON/REST API. The "API" is a set of HTML form endpoints, all of which accept `POST` only.

### `index.php`

| Parameter | Type | Description |
|---|---|---|
| *(none)* | — | Renders the full product list. Accepts no input. |

### `create.php` — create a product

| Parameter | Type | Required | Maps to column | Notes |
|---|---|---|---|---|
| `name` | string | Yes | `name` | Max 100 chars. |
| `describtion` | string | No* | `description` | Field name is misspelled. |
| `price` | numeric string | Yes | `price` | Inserted **unquoted**. |

*Behaviour:* `INSERT`, then redirect to `index.php`.

### `edit.php` — update a product

| Parameter | Type | Required | Maps to column | Notes |
|---|---|---|---|---|
| `id` | int | Yes | `id` (WHERE) | Hidden input; identifies the row. |
| `name` | string | Yes | `name` | |
| `description` | string | No* | `description` | Correctly spelled here. |
| `price` | numeric string | Yes | `price` | Written **quoted** as a string. |

*Behaviour:* `UPDATE ... WHERE id = ?`, then redirect to `index.php`.

### `delet.php` — delete a product

| Parameter | Type | Required | Notes |
|---|---|---|---|
| `id` | int | Yes | Hidden input; identifies the row. |

*Behaviour:* `DELETE ... WHERE id = ?`, then redirect to `index.php`.

### `show.php` — single product view

**Not implemented.** The file is empty (0 bytes) and the "Show" button in `index.php` links to `href="#"`.

---

## Known Issues & Limitations

Functional and correctness problems present in the current code:

1. **Navbar toggler is broken.** `includes/header.php` uses Bootstrap 4 attributes (`data-toggle="collapse"`, `data-target="#navbarID"`) on a Bootstrap 5 navbar. Bootstrap 5 requires `data-bs-toggle` / `data-bs-target`. The hamburger button does nothing.

2. **Navbar menu is empty.** The only `.navbar-nav` block is commented out, so the collapse target has no content.

3. **`show.php` is empty.** The "Show" button is a dead link.

4. **Delete depends entirely on JavaScript.** If the modal script fails, the hidden `id` stays empty and the query degrades to `DELETE FROM products WHERE id=` → SQL syntax error, no user feedback.

5. **No error handling on writes.** All three action scripts do `if ($res) { header(...); }` with no `else`. On failure the user stays on a blank page with no message.

6. **Missing `exit;` after redirect.** `header("location:index.php")` is not followed by `exit`, so execution technically continues after the header call. There is no code after it today, but it is fragile — any future output below it would produce a "headers already sent" warning.

7. **Inconsistent quoting of `price`.** `create.php` interpolates it **unquoted** (`VALUES (...,$price)`), while `edit.php` wraps it in quotes (`SET price='$price'`). The unquoted form in `create.php` is both fragile and injectable.

8. **Inconsistent field name.** `create.php` uses `describtion`; `edit.php` uses `description`. The two do not match the column name, and the inconsistency is a trap for future edits.

9. **`created_at` also updates on edit.** The `ON UPDATE current_timestamp()` clause means the "created at" time is silently overwritten every time a product is edited. For true creation tracking, drop that clause and add a separate `updated_at` column.

10. **`price` uses `double`.** Floating-point storage cannot represent currency exactly (`0.1 + 0.2 !== 0.3`), so totals can drift. Use `DECIMAL(10,2)`.

11. **No server-side validation.** Empty names, negative prices, non-numeric prices, and over-length names are all sent straight to the database.

12. **No `.gitignore`.** There is no ignore file, so local artifacts (logs, `.env`, editor files, database dumps) are at risk of being committed.

13. **Bootstrap ships every build variant.** `css/` and `js/` contain ~20 files and source maps (~3 MB) when only two are ever loaded. This bloats the repo and the directory listing.

---

## Security Notes

> The application is a learning exercise and **is not production-ready**. The issues below are architectural and should be addressed before any real deployment.

### 1. SQL Injection — critical, present in all three action scripts

Every query is assembled by concatenating raw `$_POST` values directly into SQL:

```php
// create.php
$sql = "INSERT INTO `products`(`name`, `description`, `price`)
        VALUES ('$name','$describtion',$price)";

// edit.php
$sql = "UPDATE `products` SET `name`='$name',`description`='$describtion',`price`='$price'
        WHERE `id`=$id";

// delet.php
$sql = "DELETE FROM `products` WHERE `id`=$id";
```

A single quote in any field breaks the query; a crafted payload can read or destroy data. Note that `price` in `create.php` is interpolated **unquoted**, which is an injectable numeric context.

**Fix — use prepared statements:**

```php
$name        = $_POST["name"];
$description = $_POST["describtion"];
$price       = floatval($_POST["price"]);

$stmt = mysqli_prepare($conn, "INSERT INTO `products` (`name`, `description`, `price`) VALUES (?, ?, ?)");
mysqli_stmt_bind_param($stmt, "ssd", $name, $description, $price);
mysqli_stmt_execute($stmt);
```

### 2. Cross-Site Scripting (XSS) — present in the table output

`index.php` renders `name` and `description` **unescaped** into table cells:

```php
<td><?= $row["name"] ?></td>
<td><?= $row["description"] ?></td>
```

Any script-like content stored in those columns executes in every visitor's browser. Escaping is applied inconsistently — the Edit *button* uses `htmlspecialchars()`, but the visible *cells* do not.

**Fix:**

```php
<td><?= htmlspecialchars($row["name"], ENT_QUOTES, 'UTF-8') ?></td>
<td><?= htmlspecialchars($row["description"], ENT_QUOTES, 'UTF-8') ?></td>
```

### 3. No CSRF protection

None of the three forms carries a token. A malicious page can auto-submit a form to `delet.php` and delete arbitrary rows, or to `edit.php` and alter them. Add a per-session token and verify it on every POST.

### 4. Hardcoded, committed credentials

`root` with a blank password is stored in a tracked file. Anyone with repository access knows the production database credentials, and the app would connect as a full-privilege superuser. Use a least-privilege MySQL user, load credentials from environment variables, and **never commit secrets**.

### 5. No authentication or authorization

Every visitor can create, edit, and delete products, and can address any row by guessing its `id` (IDOR). Authentication plus per-action authorization is required before real use.

### 6. Irreversible deletes

Deletion is permanent and unconfirmed beyond a modal. There is no soft delete, trash, or audit trail.

### 7. Error messages leak nothing useful

`die("Can't vonnect to the database.")` is the only failure path, and query failures are silent. In production, errors should be logged server-side with `error_log()`, never printed to the browser.

---

## Future Improvements

Prioritised, roughly by value:

**Correctness & security**
- [ ] Replace string concatenation with `mysqli` prepared statements everywhere
- [ ] Escape all output with `htmlspecialchars()` (or introduce a tiny render helper)
- [ ] Add CSRF tokens to the create/edit/delete forms
- [ ] Add server-side validation: required fields, `is_numeric()` / `FILTER_VALIDATE_FLOAT` on price, `strlen()` limit on name
- [ ] Change `price` from `double` to `DECIMAL(10,2)`
- [ ] Remove `ON UPDATE current_timestamp()` from `created_at`; add a separate `updated_at`
- [ ] Move credentials to environment variables / `.env`; add `.gitignore`; use a non-root DB user
- [ ] Add `exit;` after every `header()` redirect
- [ ] Report errors via `error_log()` and show the user a friendly message

**Functionality**
- [ ] Implement `show.php` and wire up the "Show" button
- [ ] Add authentication and per-action authorization
- [ ] Add pagination, search, and sortable columns
- [ ] Rename `delet.php` → `delete.php` (keep a redirect stub for old links)
- [ ] Rename the `describtion` form field to `description` for consistency
- [ ] Add a `products` unique index on `name` and validate for duplicates
- [ ] Add product image upload with validation and a safe filename
- [ ] Add a "deleted successfully" flash message

**Architecture & quality**
- [ ] Introduce a front controller and a small router instead of one file per action
- [ ] Extract data access into functions/classes with prepared statements
- [ ] Replace `mysqli` with **PDO** for consistent exception-based error handling
- [ ] Split `includes/` into partials (nav, head, scripts) plus a service layer
- [ ] Move the inline `<script>` out of `index.php` into `js/app.js`
- [ ] Prune unused Bootstrap builds and source maps from the repo
- [ ] Add PHPUnit tests for the data layer
- [ ] Add a linter (PHP_CodeSniffer, PHPStan) and a pre-commit hook
- [ ] Add a composer/npm toolchain and a `.env.example`

---

## License

This project is a workshop/learning exercise. No license file is present in the repository.

Bundled third-party assets:

- **Bootstrap 5.3.8** — © The Bootstrap Authors, MIT License — <https://github.com/twbs/bootstrap>
