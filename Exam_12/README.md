# Attendance System in PHP and MySQL

This is a simple lab exam attendance system built using PHP and MySQL.

## Features

- Student can register himself
- Teacher can take attendance using checkbox, roll number and name
- Attendance report can be viewed date-wise
- Uses relative links, so changing the folder name does not affect the project

## Setup Steps

1. Copy project folder into `htdocs` or `www`.
2. Create a MySQL database by importing `database.sql`.
3. Open `config.php` and update database username/password if required.
4. Run project in browser:
   - `http://localhost/your-folder-name/`

## Main Files

- `config.php` - database connection and helper function
- `index.php` - home page
- `student_register.php` - student registration form
- `teacher_attendance.php` - teacher attendance page
- `view_attendance.php` - attendance report
- `database.sql` - database tables
