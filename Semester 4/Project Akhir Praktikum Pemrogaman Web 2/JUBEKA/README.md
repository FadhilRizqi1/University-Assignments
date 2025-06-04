# Semester 4 - Web Programming Final Project (JUBEKA)

This folder contains the "JUBEKA" project, the final project for the Web Programming 2 course in Semester 4. JUBEKA (Jual Beli Barang Bekas - Used Goods Buy & Sell) is a web-based platform designed to facilitate the buying and selling of second-hand items, primarily focusing on the Palembang, South Sumatra area.

## Table of Contents

- [Project Overview](#project-overview)
- [Key Features](#key-features)
- [Technologies Used](#technologies-used)
- [Setup Instructions](#setup-instructions)

## Project Overview

**JUBEKA** is a comprehensive web application that provides a marketplace for used goods. It supports user registration, item listing with details and images, searching, and administrative functionalities for managing users and items. This project demonstrates a full-stack web development approach using PHP for backend logic and MySQL for database management, along with modern front-end technologies.

## Key Features

-   **User Authentication:**
    -   Login (`auth/login.php`)
    -   Signup (`auth/signup.php`)
    -   Logout (`auth/logout.php`)
-   **User Profile Management:** View and manage personal profiles (`profil.php`).
-   **Item Listing:** Users can post new items for sale with descriptions and images (initial setup might be in `Admin/tambah.php`, regular user posting might also be implemented).
-   **Item Browse & Searching:**
    -   View a list of available items (`daftar-barang.php`).
    -   Search for items by keywords or categories.
-   **Item Details Page:** Detailed view of a specific item (`detail-barang.php`).
-   **User-specific Item Management:** Users can view, edit (`edit-barang.php`), and delete their own listed items.
-   **Contact Form & Message Management:** Users can send messages (`contact.php`).
-   **Admin Dashboard:**
    -   Centralized dashboard for administrators (`Admin/dashboard.php`).
    -   Manage users and items (`Admin/manage_items.php`).
    -   Add new items (`Admin/tambah.php`).
    -   Edit existing items (`Admin/edit.php`).
    -   Admin logout (`Admin/logout.php`).
-   **Transaction Management:** Sections for simulated purchases (`pembelian.php`) and sales (`penjualan.php`).
-   **Database Schema:** The database structure is defined in `jubeka_db.sql`.
-   **Core Functions:** Utility functions for database interaction and other common tasks (`functions.php`, `auth/koneksi.php`).

## Technologies Used

* **PHP** (for server-side logic)
* **HTML5**
* **CSS3**
* **JavaScript**
* **MySQL** (for database management)
* **Bootstrap 5** (for responsive design and UI components)
* **jQuery** (for simplified DOM manipulation and AJAX)
* **Swiper.js** (for implementing carousels, likely on the homepage)
* **Font Awesome** (for icons)

## Setup Instructions

To run the JUBEKA project locally, you will need a web server environment with PHP and MySQL support (e.g., XAMPP, WAMP, MAMP).

1.  **Clone the repository:**
    ```bash
    git clone <repository_url> # Ganti dengan URL repositori Anda
    cd <nama_folder_repositori_anda>/Lecture-Assignments/Semester\ 4/Project\ Akhir\ Praktikum\ Pemrogaman\ Web\ 2/
    ```
2.  **Place the `JUBEKA` folder** into your web server's document root (e.g., `htdocs` for XAMPP). The full path should look something like `C:/xampp/htdocs/JUBEKA/`.
3.  **Database Setup:**
    * Open your MySQL administration tool (e.g., phpMyAdmin).
    * Create a new MySQL database named `jubeka_db`.
    * Import the `jubeka_db.sql` file (located in `Lecture-Assignments/Semester 4/Project Akhir Praktikum Pemrogaman Web 2/JUBEKA/`) into your newly created `jubeka_db` database.
    * **Configure Database Connection:** Open `JUBEKA/auth/koneksi.php` and ensure the database connection details (e.g., `localhost`, `root`, empty password, `jubeka_db`) are correctly configured to match your local MySQL setup.
4.  **Access the project in your browser:**
    * Navigate to `http://localhost/JUBEKA/index.php` (atau `http://localhost/<nama_folder_root_web_server_anda>/JUBEKA/index.php` jika Anda menempatkannya di subfolder dalam `htdocs`).
