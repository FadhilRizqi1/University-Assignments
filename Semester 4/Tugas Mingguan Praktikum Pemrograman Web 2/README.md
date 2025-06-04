# Semester 4 - Weekly Web Programming Assignments

This folder contains various weekly assignments and practical exercises from the Web Programming 2 course in Semester 4. These assignments cover fundamental concepts in PHP and HTML, demonstrating basic server-side scripting, form handling, and file/directory operations.

## Table of Contents

- [Assignment Categories](#assignment-categories)
  - [PHP Basics (Pertemuan 2)](#php-basics-pertemuan-2)
  - [PHP Functions (Pertemuan 4 & 5)](#php-functions-pertemuan-4--5)
  - [File & Directory Handling (Tugas PPWEB 2)](#file--directory-handling-tugas-ppweb-2)
- [Technologies Used](#technologies-used)
- [Setup Instructions](#setup-instructions)

## Assignment Categories

This section details the individual assignments grouped by their respective topics or meeting numbers.

### PHP Basics (Pertemuan 2)

These files demonstrate fundamental PHP syntax and basic calculations.

-   `luas_lingkaran.php`: Calculates the area of a circle based on a given radius.
-   `penggunaan_marquee.php`: Illustrates the deprecated HTML `<marquee>` tag for text scrolling (for educational purposes only, not recommended for modern web development).
-   `total_bayar.php`: Calculates the total payment for a set of items, potentially including quantity and price.
-   `volume_kubus.php`: Calculates the volume of a cube based on its side length.

### PHP Functions (Pertemuan 4 & 5)

These assignments focus on using and creating PHP functions, including basic login system components.

-   `hitungdiskon.php`: A PHP script that calculates discounted prices based on an original price and a discount percentage.
-   `Pertemuan 5/functions/functions.php`: Contains core PHP functions, likely related to user authentication, session management, or database interactions for a basic login system.
-   `Pertemuan 5/pageLogin/login.php`: A basic login page demonstrating form submission, validation, and potentially session/cookie management.

### File & Directory Handling (Tugas PPWEB 2)

These files demonstrate various operations related to file and directory manipulation in PHP.

-   `counter.txt`: A simple text file often used to store a numerical value, such as a website visitor counter.
-   `data.txt`: A generic text file containing sample data for reading or writing operations.
-   `direktori.php`: Demonstrates PHP functions for creating and removing directories on the server.
-   `file1.php`: Illustrates how to create a new file and potentially write initial content to it.
-   `file2.php`: Shows how to write data to an existing file, either overwriting or appending.
-   `file3.php`: Demonstrates reading a specific number of bytes or characters from a file.
-   `file4.php`: Shows how to read the content of a file line by line until the end of the file is reached.
-   `file5.php`: Implements a simple visitor counter by reading from and writing to `counter.txt`.
-   `isidirektori.php`: Lists the contents (files and subdirectories) of a specified directory.

## Technologies Used

* **PHP**
* **HTML5**

## Setup Instructions

To run these weekly assignments locally, you will need a basic web server environment with PHP support (e.g., XAMPP, WAMP, MAMP).

1.  **Clone the repository:**
    ```bash
    git clone https://github.com/FadhilRizqi1/Lecture-Assignments.git
    cd <your repository folder name>/Lecture-Assignments/Semester\ 4/Tugas\ Mingguan\ Praktikum\ Pemrograman\ Web\ 2/
    ```
2.  **Place the `Tugas Mingguan Praktikum Pemrograman Web 2` folder** into your web server's document root (e.g., `htdocs` for XAMPP). The full path should look something like `C:/xampp/htdocs/Tugas Mingguan Praktikum Pemrograman Web 2/`.
3.  **Access the assignments in your browser:**
    * Navigate directly to the PHP files, for example:
        * `http://localhost/Tugas%20Mingguan%20Praktikum%20Pemrograman%20Web%202/Pertemuan%202/luas_lingkaran.php`
        * `http://localhost/Tugas%20Mingguan%20Praktikum%20Pemrograman%20Web%202/Tugas%20PPWEB%202/file5.php`
    * (Sesuaikan path sesuai konfigurasi web server Anda dan lokasi file yang tepat).
