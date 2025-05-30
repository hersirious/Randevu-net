Simple Website Project - RandevuNet
This project is a basic website developed as a school assignment, demonstrating fundamental web development concepts using PHP, MySQL, HTML, and CSS. It allows for the creation of companies and enables other users to book appointments with those companies.

Features
Company Creation: Users can create and manage company profiles.
Appointment Booking: Other users can book appointments with registered companies.
User Interface: Built with HTML and styled using CSS for a straightforward user experience.
Dynamic Content: Utilizes PHP for server-side scripting to handle dynamic content and interactions.
Database Integration: Connects to a MySQL database to store and retrieve company, user, and appointment information.
Admin Panel: Includes a basic administrator login page for managing content (access details below).
Installation
To set up and run this project locally, please follow these steps:

Database Setup:

Open phpMyAdmin or your preferred MySQL client.
Select the "Import" tab.
Choose the database file named 127_0_0_1.sql located in the database folder within this project directory.
Click "Go" (or "Import") to import the database. This will create all necessary tables and populate them with initial data.
Web Server Configuration:

Place all project files into your web server's document root (e.g., htdocs for Apache, www for Nginx).
Ensure your web server (e.g., Apache, Nginx) is running and configured to process PHP files.
Database Connection:

Open the db.php file in the project's root directory.
Update the database connection parameters (hostname, username, password, database name) in db.php to match your local MySQL setup.
Note: Some code comments or variable names may be in Turkish due to the nature of the project development.
Admin Panel Access
To access the administrator login page:

Navigate to: your_project_url/admin_giris.php
Credentials: The username and password for the admin panel are stored in the admin table of your database. You can find or set them there.
Technologies Used
PHP: For server-side logic, handling company creation, appointment booking, and database interaction.
MySQL: The relational database management system storing all project data.
HTML: For structuring the web content and forms.
CSS: For styling and layout, ensuring a clear and user-friendly interface.
Important Note
This application was developed solely for an educational assignment and is intended for local use only. It is not designed for deployment on the public internet, and the developer assumes no responsibility for any issues arising from its online use.
