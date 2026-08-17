# Product Viewer

A simple local web application for viewing product information.

This project was built as a lightweight product viewer for local use. It does not require a login system or internet deployment. Product data is imported from an Excel file and stored in a local SQLite database.

## Features

* Import product data from an Excel file
* Store product data in SQLite
* Automatically merge duplicate products
* Search products
* Paginate product results
* Simple and minimal interface
* Designed for local/offline usage

## Tech Stack

* **Laravel**
* **Laravel Excel**
* **SQLite**
* **Blade**
* **Tailwind CSS**

## Excel File Format

| Name         | Category | Quantity | Price |
| ------------ | -------- | -------: | ----: |
| Coca Cola    | Drink    |       20 |  1000 |
| Potato Chips | Snack    |       15 |  1500 |
| Coffee       | Drink    |       10 |  2500 |

**Important:** The columns must be in this exact order:

`Name → Category → Quantity → Price`

The first row should contain the column names.

## Installation

Clone the repository and install the dependencies:

```bash
git clone <repository-url>
cd <project-folder>

composer install
npm install
```

Create the environment file:

```bash
cp .env.example .env
```

Generate the application key:

```bash
php artisan key:generate
```

Set SQLite as the database in `.env` if not done yet:

```env
DB_CONNECTION=sqlite
```

Create the SQLite database:

```bash
touch database/database.sqlite
```

Run the migrations:

```bash
php artisan migrate
```

Build the frontend assets:

```bash
npm run build
```

## Running the Application

Start the Laravel development server:

```bash
php artisan serve
```

Then open the local address shown by Laravel in your browser.

For frontend development, you can also run:

```bash
npm run dev
```

## Importing Products

Prepare an Excel file using the required format:

```text
Name | Category | Quantity | Price
```

Then import the file through the application's import feature.

The imported data is saved into the local SQLite database. Duplicate products are merged automatically instead of creating multiple identical records.

## Local Usage

This application is intended specifically for local use.

## Project Purpose

This project was created as a small practical Laravel project to build a simple product viewing system in a short amount of time.

The main focus was on:

* Excel data importing
* Database storage
* Duplicate data handling
* Product searching
* Pagination
* Building a simple Laravel interface

## License

This project is for personal/local use.
