# Semester 4 - Web Programming Midterm Project (UTS PEMWEB2)

This folder contains the "Online Course Management System" project, developed for the Midterm Exam (UTS) of the Web Programming 2 course in Semester 4. This project focuses on managing users and course registrations for an online learning platform. It includes three different design versions, demonstrating iterative development and styling improvements.

## Table of Contents

- [Project Overview](#project-overview)
- [Project Versions](#project-versions)
- [Key Features](#key-features)
- [Technologies Used](#technologies-used)
- [Setup Instructions](#setup-instructions)

## Project Overview

The **Online Course Management System** is a web application designed to handle basic operations for an online learning platform. It allows for user registration, course enrollment, viewing available courses, searching for participants, and generating reports on course registrations. The project is structured to showcase different levels of design implementation.

## Project Versions

This project includes three distinct versions, each residing in its own sub-folder, demonstrating progress in design and functionality:

-   **`UTS PEMWEB2 - KELOMPOK 9 (Simple)/`**: The most basic implementation, focusing purely on core functionality with minimal styling.
-   **`UTS PEMWEB2 - KELOMPOK 9 (More Design)/`**: An enhanced version with improved styling and user interface elements compared to the "Simple" version.
-   **`UTS PEMWEB2 - KELOMPOK 9 (Final)/`**: The most complete and polished version, incorporating the final design and all required functionalities.

**It is recommended to use the `UTS PEMWEB2 - KELOMPOK 9 (Final)/` folder for setup and exploration, as it represents the complete project.**

## Key Features

(Based on the `UTS PEMWEB2 - KELOMPOK 9 (Final)/` version)

-   **Database Schema:** The database structure for courses and registrations is defined in `db_kursus_daring.sql`.
-   **User Registration Form:** Allows new users to register for the platform (`form_tambah_user.php`).
-   **Course Registration Form:** Enables registered users to enroll in available courses (`form_reg_kursus.php`).
-   **List of Available Courses:** Displays a list of all courses offered (`list_kursus.php`).
-   **Participant Search:** Functionality to search for participants enrolled in specific courses (`cari_peserta.php`).
-   **Registrant Report:** Generates a report showing the number of registrants per course (`lap_pendaftar.php`).
-   **Database Connection:** A shared file for connecting to the MySQL database (`koneksi.php`).
-   **Custom CSS Styling:** Provides the visual design for the application (`style.css`).

## Technologies Used

* **PHP** (for server-side logic)
* **HTML5**
* **CSS3** (`style.css`)
* **MySQL** (for database management)
* **Bootstrap 4** (for responsive design and UI components)
* **jQuery** (for simplified DOM manipulation and AJAX)
* **Select2** (for enhanced select box functionality, likely in registration forms)

## Setup Instructions

To run the Online Course Management System project locally, you will need a web server environment with PHP and MySQL support (e.g., XAMPP, WAMP, MAMP).

1.  **Clone the repository:**
    ```bash
    git clone <repository_url> # Ganti dengan URL repositori Anda
    cd <nama_folder_repositori_anda>/Lecture-Assignments/Semester\ 4/Project\ Tengah\ Semester\ Pemrograman\ Web\ 2/
    ```
2.  **Place the desired project folder** (e.g., `UTS PEMWEB2 - KELOMPOK 9 (Final)`) into your web server's document root (e.g., `htdocs` for XAMPP). The full path should look something like `C:/xampp/htdocs/UTS PEMWEB2 - KELOMPOK 9 (Final)/`.
3.  **Database Setup:**
    * Open your MySQL administration tool (e.g., phpMyAdmin).
    * Create a new MySQL database named `db_kursus_daring`.
    * Import the `db_kursus_daring.sql` file (located in the chosen project folder, e.g., `Lecture-Assignments/Semester 4/Project Tengah Semester Pemrograman Web 2/UTS PEMWEB2 - KELOMPOK 9 (Final)/`) into your newly created `db_kursus_daring` database.
    * **Configure Database Connection:** Open the `koneksi.php` file within your chosen project folder (e.g., `UTS PEMWEB2 - KELOMPOK 9 (Final)/koneksi.php`) and ensure the database connection details are correctly configured to match your local MySQL setup.
4.  **Access the project in your browser:**
    * Navigate to `http://localhost/UTS%20PEMWEB2%20-%20KELOMPOK%209%20(Final)/form_tambah_user.php` (atau entri point lain seperti `list_kursus.php`) (sesuaikan path sesuai konfigurasi web server Anda).
