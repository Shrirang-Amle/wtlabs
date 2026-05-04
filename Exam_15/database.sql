CREATE DATABASE IF NOT EXISTS college_complaints;
USE college_complaints;

CREATE TABLE IF NOT EXISTS students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(100) NOT NULL
);

CREATE TABLE IF NOT EXISTS admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(100) NOT NULL
);

CREATE TABLE IF NOT EXISTS complaints (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    subject VARCHAR(150) NOT NULL,
    department VARCHAR(100) NOT NULL,
    complaint_text TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_student_complaint
        FOREIGN KEY (student_id) REFERENCES students(id)
        ON DELETE CASCADE
);

INSERT INTO students (name, email, password)
SELECT 'Student One', 'student1@college.com', 'student123'
WHERE NOT EXISTS (
    SELECT 1 FROM students WHERE email = 'student1@college.com'
);

INSERT INTO admins (username, password)
SELECT 'admin', 'admin123'
WHERE NOT EXISTS (
    SELECT 1 FROM admins WHERE username = 'admin'
);
