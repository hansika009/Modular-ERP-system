# Modular-ERP-system

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

 Why I Built This Project
I built this Modular ERP System to challenge myself with end-to-end full-stack development, moving beyond simple static websites to create a data-driven application that solves real-world business problems.

My primary goals for this project were:

Mastering Relational Databases: I wanted to architect a database strictly adhering to Third Normal Form (3NF). By implementing complex Foreign Key constraints and ON DELETE CASCADE rules, I ensured high data integrity between Customers, Products, and Orders.
Handling Complex Business Logic: Rather than just performing basic CRUD operations, I engineered automated SQL Transactions. For example, when an order is created, the backend dynamically calculates the total cost via SQL JOINs and instantly deducts the purchased quantity from the inventory stock in a single transactional flow.
Implementing Custom Security: I built a secure employee authentication system from scratch using PHP's native password_hash() encryption and secure session management to protect sensitive business data.
Creating a Premium User Experience: I utilized Tailwind CSS and Chart.js to build a modern, responsive dashboard that translates raw database metrics into clean, interactive visual data.
Ultimately, this project serves as a comprehensive showcase of my ability to design scalable database architectures, write secure server-side logic, and build polished user interfaces.

