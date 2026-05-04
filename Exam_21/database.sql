CREATE DATABASE IF NOT EXISTS school_db;
USE school_db;

CREATE TABLE IF NOT EXISTS students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    course VARCHAR(100) NOT NULL,
    phone VARCHAR(20) NOT NULL
);

INSERT INTO students (name, email, course, phone) VALUES
('Rahul Sharma', 'rahul@example.com', 'BCA', '9876543210'),
('Priya Verma', 'priya@example.com', 'BSc IT', '9876501234'),
('Amit Kumar', 'amit@example.com', 'BCom', '9988776655');
