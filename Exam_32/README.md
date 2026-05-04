# Exam_32 — Spring Boot BCrypt Password Encryption

A complete demonstration of **secure password storage and authentication**
using Spring Boot 3, Spring Security, BCrypt, and H2.

---

## Project Structure

```
Exam_32/
├── pom.xml
└── src/
    └── main/
        ├── java/com/exam32/
        │   ├── Exam32Application.java          ← Entry point
        │   ├── config/
        │   │   ├── SecurityConfig.java         ← TASK 1: BCrypt encoder bean
        │   │   └── DataInitializer.java        ← Seeds demo users on startup
        │   ├── controller/
        │   │   ├── AuthController.java         ← TASK 5: REST endpoints
        │   │   └── UserController.java         ← Inspect DB state
        │   ├── model/
        │   │   ├── User.java                   ← Entity (password = BCrypt hash)
        │   │   ├── Role.java
        │   │   ├── RegisterRequest.java        ← DTO
        │   │   ├── LoginRequest.java           ← DTO
        │   │   └── AuthResponse.java           ← TASK 5: result payload
        │   ├── repository/
        │   │   └── UserRepository.java
        │   └── service/
        │       ├── UserService.java            ← TASK 2: encrypt on register
        │       └── AuthenticationService.java  ← TASK 3 & 4: login + verify
        └── resources/
            └── application.properties
```

---

## How the 5 Tasks Map to Code

| Task | Description | Key file |
|------|-------------|----------|
| 1 | Configure BCrypt password encoder | `SecurityConfig.java` — `BCryptPasswordEncoder(12)` bean |
| 2 | Store encrypted passwords in DB | `UserService.registerUser()` — encodes before `save()` |
| 3 | Authenticate with encrypted passwords | `AuthenticationService.authenticate()` |
| 4 | Verify password during login | `passwordEncoder.matches(raw, storedHash)` |
| 5 | Display authentication results | `AuthController` returns `AuthResponse` JSON |

---

## Running the Application

```bash
cd Exam_32
mvn spring-boot:run
```

The app starts on **http://localhost:8080**.

Three demo users are seeded automatically:

| Username | Password    | Role       |
|----------|-------------|------------|
| alice    | alice@123   | ROLE_USER  |
| bob      | bob@456     | ROLE_USER  |
| admin    | admin@999   | ROLE_ADMIN |

---

## API Reference

### 1. Register a new user
```bash
curl -X POST http://localhost:8080/api/auth/register \
  -H "Content-Type: application/json" \
  -d '{"username":"john","email":"john@test.com","password":"john@2024"}'
```

**Response:**
```json
{
  "success": true,
  "message": "User registered successfully! Password encrypted with BCrypt.",
  "username": "john",
  "encryptedPassword": "$2a$12$...",
  "note": "The plain-text password is NOT stored. Only the BCrypt hash is persisted."
}
```

---

### 2. Login — correct password
```bash
curl -X POST http://localhost:8080/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{"username":"alice","password":"alice@123"}'
```

**Response (200 OK):**
```json
{
  "success": true,
  "message": "Authentication successful! Password validated against BCrypt hash.",
  "username": "alice",
  "role": "ROLE_USER",
  "encryptedPasswordPreview": "$2a$12$XyZ...",
  "timestamp": "2024-01-15T10:30:00"
}
```

---

### 3. Login — wrong password
```bash
curl -X POST http://localhost:8080/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{"username":"alice","password":"wrongPassword"}'
```

**Response (401 Unauthorized):**
```json
{
  "success": false,
  "message": "Authentication failed: Invalid password.",
  "timestamp": "2024-01-15T10:30:05"
}
```

---

### 4. Verify a password against a hash directly
```bash
curl -X POST http://localhost:8080/api/auth/verify \
  -H "Content-Type: application/json" \
  -d '{"rawPassword":"alice@123","storedHash":"<paste hash from GET /api/users/alice>"}'
```

---

### 5. Demonstrate BCrypt encoding
```bash
curl -X POST http://localhost:8080/api/auth/encode \
  -H "Content-Type: application/json" \
  -d '{"password":"mySecret"}'
```

**Response** — notice hash1 ≠ hash2 even though the input is the same:
```json
{
  "plainTextPassword": "mySecret",
  "bcryptHash1": "$2a$12$abc...",
  "bcryptHash2": "$2a$12$xyz...",
  "sameHashes": false,
  "note": "Even identical passwords produce different hashes due to random salt..."
}
```

---

### 6. View all users + their encrypted passwords
```bash
curl http://localhost:8080/api/users
```

---

### 7. H2 Database Console
Open **http://localhost:8080/h2-console**

| Field    | Value              |
|----------|--------------------|
| JDBC URL | `jdbc:h2:mem:exam32db` |
| Username | `sa`               |
| Password | *(empty)*          |

Run: `SELECT * FROM USERS;` to inspect the hashed passwords.

---

## Running Tests
```bash
mvn test
```

All 7 tests cover every task:
- Task 1: BCrypt encoder is configured; different hashes per call
- Task 2: Only the hash is persisted, not the plain text
- Task 3: Correct credentials authenticate successfully
- Task 4: Wrong passwords are rejected; `matches()` is verified
- Task 5: `AuthResponse` contains all required fields
