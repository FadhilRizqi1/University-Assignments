# Ferigo: Ship Ticket Booking Website

Ferigo is a web-based application designed to facilitate ship ticket bookings for passengers. It aims to provide an easy, fast, and secure platform for users to find schedules, book tickets, and manage their travel history.

## Features

- **User Authentication**: Secure login and registration system with password hashing and "remember me" functionality.
- **Schedule Search**: Users can search for available ship schedules based on:
  - Departure Port (Pelabuhan Asal)
  - Destination Port (Pelabuhan Tujuan)
  - Service Type (Layanan: Reguler or Eksekutif)
  - Check-in Date (Tanggal)
  - Check-in Time (Jam)
  - Passenger Type (Umur: Di atas 6 tahun or Di bawah 2 tahun)
- **Ticket Booking**: Once a schedule is found, users can proceed to book tickets.
- **Payment Gateway**: Supports Mandiri E-Money as a payment method.
- **E-Ticket Generation**: Generates an electronic ticket (e-ticket) that can be downloaded as a PNG image.
- **Booking History**: Users can view their past booking history, including details like origin, destination, service type, date, time, number of passengers, and price.
- **Responsive Design**: The website is designed to be accessible and functional across various devices.

## Technologies Used

- **Frontend**:
  - HTML5
  - CSS3 (with `@import` for Google Fonts like 'DM Sans' and 'Poppins')
  - JavaScript (for dynamic interactions and SweetAlert2 integration)
- **Backend**:
  - PHP (for server-side logic, database interactions, and session management)
- **Database**:
  - MySQL (managed with phpMyAdmin)

## Database Schema

The `ferigodb` database contains the following tables:

- **`userlog`**: Stores user registration details.
  - `id` (INT, Primary Key, AUTO_INCREMENT)
  - `username` (VARCHAR)
  - `email` (VARCHAR)
  - `password` (VARCHAR, hashed)
  - `userID` (CHAR, Unique ID)
- **`jadwal`**: Stores ship schedules and pricing information.
  - `pelabuhan_asal` (VARCHAR)
  - `pelabuhan_tujuan` (VARCHAR)
  - `layanan` (VARCHAR)
  - `harga` (INT)
  - `jadwal_masuk` (DATE)
  - `jam_masuk` (VARCHAR)
  - `umur` (VARCHAR)
- **`riwayat_pemesanan`**: Records user booking history.
  - `id` (INT, Primary Key, AUTO_INCREMENT)
  - `nickname` (VARCHAR)
  - `asal` (VARCHAR)
  - `tujuan` (VARCHAR)
  - `layanan` (VARCHAR)
  - `tanggal` (DATE)
  - `jam` (VARCHAR)
  - `penumpang` (INT)
  - `harga` (INT)

## Installation and Setup

1.  **Clone the repository**:
    ```bash
    git clone <repository_url>
    ```
2.  **Set up the database**:
    - Import the `ferigodb.sql` file into your MySQL database (e.g., using phpMyAdmin).
    - Ensure the database connection details in `Ferigo/functions/functions.php` are correct:
      ```php
      $conn = mysqli_connect('localhost', 'root', '', 'ferigodb');
      ```
      (Update `localhost`, `root`, `''`, and `ferigodb` as per your database configuration).
3.  **Configure BASEURL**:
    - Update `BASEURL` in `Ferigo/config.php` to your project's root URL:
      ```php
      define('BASEURL', 'http://localhost/Ferigo');
      ```
      (Replace `http://localhost/Ferigo` with your actual URL).
4.  **Place the project on your web server**:
    - Move the `Ferigo` folder to your web server's document root (e.g., `htdocs` for Apache).
5.  **Access the application**:
    - Open your web browser and navigate to the `BASEURL` you configured (e.g., `http://localhost/Ferigo`).

## Available Schedules

Currently, the available schedules for booking are from **November 28, 2024, to November 30, 2024**.

## Usage

1.  **Register an Account**: If you don't have an account, navigate to the "Register" page to create one. You will need to provide a username (8-15 characters), a valid Gmail address, and a password (8-15 characters).
2.  **Login**: Use your registered credentials to log in to the system. You can also select "Remember me" for automatic login on subsequent visits.
3.  **Search for Schedules**: On the homepage, use the "Pesan Tiket" (Book Ticket) section to input your desired departure port, destination port, service type, date, time, and passenger type, then click "Cari Jadwal" (Search Schedule).
    - Note: The departure and destination ports will automatically update based on each other's selection (e.g., if you select "Bakauheni, Lampung" as departure, "Merak, Banten" will be set as destination, and vice-versa).
4.  **View Schedule Details**: If a schedule is found, you will be redirected to the schedule details page where you can see information like ship name, type, departure date, time, ports, service type, and ticket price.
5.  **Proceed to Payment**: Select your payment method (currently only Mandiri E-Money is available) and enter your account number. Click "Bayar" (Pay) to complete the booking.
6.  **View Booking History**: After successful payment, you can view your booking history by navigating to the "Riwayat" (History) page. From there, you can download your e-ticket.
7.  **Logout**: Click the "Log out" button in the navigation bar to end your session.
