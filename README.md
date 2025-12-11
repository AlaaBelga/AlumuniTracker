# AlumniTracker (Suivi des Laureats) 

A web-based interface for managing and tracking the career paths of university alumni. This project allows administrators to view, edit, and update student profiles and professional history in real-time without page reloads.

## Features

* **"No-Refresh" Architecture:** The frontend (`site.html`) communicates with the PHP backend using vanilla JavaScript and the **Fetch API**. This allows data to be injected into the DOM dynamically, creating a smooth, app-like experience without reloading the browser.
* **Career Tracking:** Manage complex work histories through a relational database structure connecting **Laureats** (Graduates), **Entreprises** (Companies), and **Postes** (Roles).
* **Data Integrity:** Enforces data consistency using MySQL Foreign Keys (e.g., ensuring a career entry is always linked to a valid company).
* **Search & Edit:** Quickly retrieve student profiles and update details on the fly.

## Tech Stack

* **Frontend:** HTML5, CSS3, JavaScript (AJAX/Fetch)
* **Backend:** PHP 8.x (Native)
* **Database:** MySQL (Relational Schema)

## File Structure

* `site.html` - The main user interface/dashboard.
* `database_structure.sql` - The SQL schema (tables, keys, and constraints).
* `get_data.php` - API endpoint to retrieve student records as JSON.
* `save_data.php` - API endpoint to save general student profile info.
* `add_parcours.php` - API endpoint to insert new career milestones.
* `update_laureat_details.php` - API endpoint to modify specific graduate details.

## Installation & Setup

1.  **Prerequisites:**
    * Install a local server environment like **XAMPP**, **WAMP**, or **MAMP**.
    * Ensure PHP and MySQL are running.

2.  **Database Setup:**
    * Open **phpMyAdmin**.
    * Create a NEW database named: `LaureatsINPT`
    * Click on the new database, go to the **Import** tab, and upload the `database_structure.sql` file included in this repository.
    * *Note: This will create the empty tables (`LAUREAT`, `ENTREPRISE`, `POSTE`, `PARCOURS_PROFESSIONNEL`). No private data is included.*

3.  **Connection Configuration:**
    * Open the PHP files (`get_data.php`, `save_data.php`, etc.) in a code editor.
    * Ensure the database connection lines match your local setup:
        ```php
        $conn = new mysqli("localhost", "root", "", "LaureatsINPT");
        ```
    * *(Update the password `""` if your local MySQL setup requires one).*

4.  **Running the App:**
    * Place the project folder in your server's root directory (e.g., `htdocs`).
    * Open your browser and navigate to `http://localhost/AlumniTracker/site.html`.

## Security Note
This project is configured for a **local development environment**.
* **Database Credentials:** The PHP files currently use default local credentials (`root`/empty password).
* **Production Warning:** If deploying to a live server, strictly update the database connection strings to use secure environment variables and non-root users.