# Datas CMS

A modern Laravel-based backend API powering the Datas ecosystem.

---

## Installation

Clone the repository:

```bash
git clone https://github.com/datasdk/foundation-backend-api.git
```

Navigate into the project directory:

```bash
cd foundation-backend-api
```

Install PHP dependencies:

```bash
composer install
```

---

## Environment Configuration

Create your `.env` file and configure your database connection:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=root
DB_PASSWORD=
```

Generate the application key:

```bash
php artisan key:generate
```

---

## Datas Installation

Run the Datas installer:

```bash
php artisan datas:install
```

When the installation is complete, the project is ready for development and configuration.

---

## About

This repository contains the backend API foundation used within the Datas ecosystem.

It is built on Laravel and provides the core architecture for Datas-powered applications and modules.

---

## Usage

This project is intended to be used as part of the Datas Platform and is not designed as a standalone CMS installation.

---

## Documentation

Developer documentation, guides, and integration resources are available at:

- https://datas.dk/developer

---

## Requirements

- PHP 8+
- Laravel
- MySQL / MariaDB
- Composer

---

## Support

For support, updates, and additional resources:

- https://datas.dk

---

## License

This project is proprietary software intended for use within the Datas ecosystem.

Unauthorized distribution or resale is prohibited.