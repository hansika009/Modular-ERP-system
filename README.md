# Modular-ERP-system
# Modular ERP System 🚀

A full-stack Enterprise Resource Planning (ERP) web application built to handle core business operations including Customer Relationship Management (CRM), Inventory Tracking, and Automated Billing. 

This project was built from scratch using strict Third Normal Form (3NF) database architecture to ensure data integrity and scalable relationships.

## ✨ Features
- **Secure Authentication:** Encrypted employee login and registration portal.
- **Interactive Dashboard:** Data visualization using Chart.js to track top-selling products and recent order activity.
- **Inventory Management:** Full CRUD capabilities with automated "Dead Stock" identification.
- **Automated Order Processing:** Select customers and products to create orders. Stock quantities are automatically deducted from the database upon purchase using SQL Transactions.
- **Smart Billing & Invoicing:** Automatically calculates order totals using multi-table SQL `JOIN` queries and generates a print-ready, professional digital invoice.
- **Live Search:** Instant client-side filtering for searching large inventory and billing tables.

## 🛠️ Technology Stack
- **Frontend:** HTML5, Tailwind CSS, Chart.js
- **Backend:** PHP 
- **Database:** MySQL (using MySQLi prepared statements for SQL injection prevention)

## 🚀 Installation & Setup
1. Clone this repository to your local machine.
2. Move the project folder into your local server environment (e.g., `C:\xampp\htdocs\`).
3. Start Apache and MySQL via the XAMPP Control Panel.
4. Open **phpMyAdmin** (`http://localhost/phpmyadmin`) and import the `database.sql` file.
5. Navigate to `http://localhost/modular_erp_system/` in your browser.
6. The system will auto-initialize. You can log in using the default credentials:
   - **Username:** `admin`
   - **Password:** `password`
