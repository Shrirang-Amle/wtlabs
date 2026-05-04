# Student Registration System

This project is a simple Student Registration System built with Node.js, Express, and MySQL.

## Features

- Register a student with `name`, `email`, and `course`
- Store student records in a MySQL database
- Fetch all student records through an API
- Display the student list in the browser

## Required package

The database package used in this project is `mysql2`.

## Setup

1. Create a MySQL database named `shri`.
2. Copy `.env.example` to `.env`.
3. Update the database values in `.env`.
4. Install dependencies:

```bash
npm install
```

5. Start the application:

```bash
npm start
```

6. Open `http://localhost:3000` in the browser.

## API Endpoints

- `POST /api/students` to insert a new student
- `GET /api/students` to retrieve all students

## Table Structure

The app uses the `students` table by default and creates it automatically on startup if it does not already exist:

- `id` - integer, primary key, auto increment
- `name` - student name
- `email` - unique email
- `course` - course name
