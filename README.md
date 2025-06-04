# Lecture Assignments - Web Programming (Semester 4)

This repository serves as a comprehensive collection of my assignments and projects undertaken during the 4th semester of Web Programming courses. It showcases my development in various web technologies, primarily focusing on PHP and foundational web development concepts.

## Table of Contents

- [About the Repository](#about-the-repository)
- [Projects Overview](#projects-overview)
  - [JUBEKA (Used Goods Buy & Sell Platform)](#jubeka-used-goods-buy--sell-platform)
  - [Online Course Management System (UTS PEMWEB2)](#online-course-management-system-uts-pemweb2)
- [Weekly Assignments](#weekly-assignments)
- [Technologies Used](#technologies-used)
- [Setup Instructions (for Local Development)](#setup-instructions-for-local-development)
- [Contact](#contact)

## About the Repository

This repository is a structured compilation of practical exercises and projects completed as part of my 4th-semester curriculum in Web Programming. It reflects my journey in learning and applying server-side scripting with PHP, database management with MySQL, and front-end development with HTML, CSS, and Bootstrap. The aim is to demonstrate my understanding of core web development principles and problem-solving skills through hands-on implementation.

## Projects Overview

### JUBEKA (Used Goods Buy & Sell Platform)

**Description:** JUBEKA (Jual Beli Barang Bekas - Used Goods Buy & Sell) is a web-based platform designed to facilitate the buying and selling of second-hand items, primarily focusing on the Palembang, South Sumatra area. This project includes features for user registration, item posting, item searching, viewing item details, and administrative management.

**Key Features:**
- User Authentication (Login, Signup, Logout)
- User Profile Management
- Item Listing (Posting new items for sale)
- Item Browse & Searching by keywords and categories
- Item Details Page
- User-specific Item Management (View, Edit, Delete own items)
- Contact Form & Message Management
- Admin Dashboard for user and item management
- Database schema (`jubeka_db.sql`)
- Core functions for database interaction and utility (`functions.php`)

### Online Course Management System (UTS PEMWEB2)

**Description:** This project focuses on managing users and course registrations for an online learning platform. It includes functionalities for adding new users, registering users for courses, listing available courses, searching for participants in specific courses, and generating a report on the number of registrants per course. The project includes three different design versions (Final, More Design, Simple).

**Key Features:**
- Database schema (`db_kursus_daring.sql`)
- User registration form (`form_tambah_user.php`)
- Course registration form (`form_reg_kursus.php`)
- List of available courses (`list_kursus.php`)
- Search functionality for course participants (`cari_peserta.php`)
- Report on the number of registrants per course (`lap_pendaftar.php`)
- Shared database connection (`koneksi.php`)
- Custom CSS styling (`style.css`)

## Weekly Assignments

This section includes various smaller assignments demonstrating fundamental PHP and HTML concepts.

- **PHP Basics (Pertemuan 2):**
    - `luas_lingkaran.php`: Calculates the area of a circle.
    - `penggunaan_marquee.php`: Demonstrates the use of the `<marquee>` tag (for educational purposes).
    - `total_bayar.php`: Calculates the total payment for items.
    - `volume_kubus.php`: Calculates the volume of a cube.

- **PHP Functions (Pertemuan 4 & 5):**
    - `hitungdiskon.php`: A PHP script to calculate discounted prices.
    - `functions.php` (for login system): Contains core functions related to user login and registration.
    - `login.php` (login page example): A basic login page demonstrating session and cookie management.

- **File & Directory Handling (Tugas PPWEB 2):**
    - `counter.txt`: A text file used to store a visitor counter.
    - `data.txt`: A text file containing sample data.
    - `direktori.php`: Demonstrates creating and removing directories.
    - `file1.php`: Demonstrates creating a new file.
    - `file2.php`: Demonstrates writing data to a file.
    - `file3.php`: Demonstrates reading a specific number of bytes from a file.
    - `file4.php`: Demonstrates reading a file line by line until the end.
    - `file5.php`: Implements a simple visitor counter.
    - `isidirektori.php`: Lists the contents of a directory.

## Technologies Used

* **PHP**
* **HTML5**
* **CSS3**
* **JavaScript**
* **MySQL** (for database management)
* **Bootstrap 5** (for responsive design and UI components in JUBEKA)
* **Bootstrap 4** (for responsive design and UI components in UTS PEMWEB2)
* **jQuery** (for simplified DOM manipulation and AJAX)
* **Swiper.js** (for carousels in JUBEKA homepage)
* **Font Awesome** (for icons)
* **Select2** (for enhanced select box in UTS PEMWEB2)

## Setup Instructions (for Local Development)

To run these projects locally, you will need a web server environment with PHP and MySQL support (e.g., XAMPP, WAMP, MAMP).

1.  **Clone the repository:**
    ```bash
    git clone <repository_url>
    cd <repository_name>
    ```
2.  **Set up your web server:**
    * Place the project folders (e.g., `JUBEKA`, `UTS PEMWEB2 - KELOMPOK 9 (Final)`) into your web server's document root (e.g., `htdocs` for XAMPP).
3.  **Database Setup:**
    * **For JUBEKA:**
        * Create a new MySQL database named `jubeka_db`.
        * Import the `jubeka_db.sql` file into your newly created database using phpMyAdmin or a similar tool.
        * Ensure the database connection in `JUBEKA/auth/koneksi.php` is correctly configured with your MySQL credentials (default `root` and empty password for XAMPP).
    * **For Online Course Management System:**
        * Create a new MySQL database named `db_kursus_daring`.
        * Import the `db_kursus_daring.sql` file into your newly created database.
        * Ensure the database connection in `UTS PEMWEB2 - KELOMPOK 9 (Final)/koneksi.php` is correctly configured.
4.  **Access the projects in your browser:**
    * Navigate to `http://localhost/<your_web_root>/JUBEKA/index.php` for the JUBEKA project.
    * Navigate to `http://localhost/<your_web_root>/UTS%20PEMWEB2%20-%20KELOMPOK%209%20(Final)/form_tambah_user.php` (or other entry points) for the Course Management System.
