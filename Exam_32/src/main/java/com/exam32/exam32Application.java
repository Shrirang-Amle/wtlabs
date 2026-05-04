package com.exam32;

import org.springframework.boot.SpringApplication;
import org.springframework.boot.autoconfigure.SpringBootApplication;

/**
 * Exam_32 - Spring Boot Password Encryption Demo
 *
 * Demonstrates:
 *  1. BCrypt password encoder configuration
 *  2. Encrypted password storage in H2 database
 *  3. User authentication with encrypted passwords
 *  4. Password validation during login
 *  5. Authentication result display via REST API
 */
@SpringBootApplication
public class exam32Application {

    public static void main(String[] args) {
        SpringApplication.run(exam32Application.class, args);
    }
}
