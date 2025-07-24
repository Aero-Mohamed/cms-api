# 🧩 Dynamic CMS Backend API (Laravel)

A Laravel-based backend API for a generic Content Management System (CMS), designed to allow full **dynamic entity creation**, **custom attributes**, and **content management**.

---

## 🚀 Setup Instructions

---

## 🧠 Project Overview

This CMS backend supports two roles:

- **Admin**:
    - Creates dynamic entities (e.g., `Product`, `Article`, `Project`)
    - Defines custom attributes (text, number, date, boolean, etc.)
    - Manages relationships between entities

- **Operator**:
    - Views and fills content based on Admin-defined schemas
    - Uses auto-generated forms powered by the entity definitions

---

## 📦 Features

- Dynamic **entity & field creation**
- Support for multiple field types: `text`, `number`, `date`, `boolean`
- Admin vs Operator roles with role-based access
- Simple and extendable API structure
- RESTful resource routing
- JWT-based authentication (Laravel Passport)

## 🛠️ Technologies

- **Laravel 12+**
- **MySQL DB**
- **Eloquent ORM**
- **Laravel Passport** for API auth

---

## 🛡️Code Standard & Quality
- Use command `composer lint` to run the following checks:
    - Static Code Analysis (PHP Stan + LaraStan) - Testing for potential errors.
        - `./vendor/bin/phpstan analyse`
    - Php Code Sniffer (PSR-12).
        - Detect Problems `./vendor/bin/phpcs --standard=PSR12 app`
        - Fix Problems `./vendor/bin/phpcbf --standard=PSR12 app`

---
