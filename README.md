# CRM — Modern Customer Relationship Management System

A powerful, intuitive, and modern Customer Relationship Management (CRM) platform built on **Laravel** and **Tailwind CSS**. Designed to help businesses track leads, manage customer interactions, automate sales pipelines, and streamline email communication.

---

## 🌟 Key Features

- **📊 Lead & Deal Pipelines:** Visual Kanban boards and list views to track deal stages, deal values, and conversion rates.
- **👥 Contact & Organization Management:** Centralized database for customers, organizations, and communication histories.
- **✉️ Integrated Email System:** Full two-way email integration supporting **SMTP** for outbound emails and **IMAP** for automatic inbox syncing.
- **📋 Activity Tracking:** Schedule calls, organize meetings, track notes, and log interactions with customers.
- **📑 Quotes & Products:** Generate customizable product quotes, manage SKUs, inventory, and pricing tiers.
- **🌐 Web Forms:** Embeddable lead-capture forms with custom styling and direct CRM integration.
- **⚡ Workflows & Automation:** Trigger automated email notifications and events based on lead status changes.
- **🔒 Role-Based Access Control:** Granular permission management across users, teams, and administrator roles.
- **🎨 Modern UI & Dark Mode:** Responsive layout with dark/light themes and customizable accent colors.

---

## 🛠️ Tech Stack

- **Backend:** [Laravel](https://laravel.com/) (PHP 8.3+)
- **Database:** MySQL 8.0+
- **Frontend:** [Tailwind CSS](https://tailwindcss.com/), [Vue.js](https://vuejs.org/), [Vite](https://vitejs.dev/)
- **Email Protocol:** SMTP / IMAP (`webklex/laravel-imap`)

---

## 🚀 Quick Start Guide

### 1. Prerequisites
Ensure you have the following installed on your machine:
- PHP >= 8.3 (with `pdo_mysql`, `curl`, `mbstring`, `fileinfo`, `imap`, `intl`, `gd` extensions enabled)
- Composer >= 2.x
- Node.js >= 18.x & npm
- MySQL Server >= 8.0

---

### 2. Installation Steps

1. **Clone the repository:**
   ```bash
   git clone https://github.com/SIDDHARTHKAUSHIK1/CRM.git
   cd CRM
   ```

2. **Install PHP and Node dependencies:**
   ```bash
   composer install
   npm install
   npm run build
   ```

3. **Configure Environment:**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
   Open `.env` and set your MySQL database credentials:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=laravel-crm
   DB_USERNAME=root
   DB_PASSWORD=your_mysql_password
   ```

4. **Run Migrations & Seeders:**
   ```bash
   php artisan migrate --seed
   ```

5. **Start the Development Server:**
   ```bash
   php artisan serve --port=8000
   ```

6. **Access the CRM:**
   - **URL:** [http://127.0.0.1:8000/admin/login](http://127.0.0.1:8000/admin/login)
   - **Default Email:** `admin@example.com`
   - **Default Password:** `admin123`

---

## 📧 Email Configuration (SMTP & IMAP)

To send and receive emails inside the CRM, add your email service credentials (e.g. Gmail App Password) to your `.env`:

```env
# Outbound SMTP Settings
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your_email@gmail.com
MAIL_PASSWORD=your_app_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="your_email@gmail.com"
MAIL_FROM_NAME="${APP_NAME}"

# Inbound IMAP Settings
IMAP_HOST=imap.gmail.com
IMAP_PORT=993
IMAP_ENCRYPTION=ssl
IMAP_VALIDATE_CERT=true
IMAP_USERNAME=your_email@gmail.com
IMAP_PASSWORD=your_app_password
```

### Syncing Incoming Emails
Run the background email parser command or schedule it as a cron job:
```bash
php artisan inbound-emails:process
```

## 📄 License & Proprietary Rights

**Copyright © Siddharth Kaushik. All rights reserved.**

This project is **strictly proprietary and confidential**. It is **not** open-source software. 

Unauthorized copying, modification, distribution, sublicensing, reverse engineering, or reproduction of this codebase or any part thereof, via any medium, is strictly prohibited without express written permission from the copyright holder.