package com.exam32.controller;

import com.exam32.model.*;
import com.exam32.service.AuthenticationService;
import com.exam32.service.UserService;
import jakarta.validation.Valid;
import org.slf4j.Logger;
import org.slf4j.LoggerFactory;
import org.springframework.http.HttpStatus;
import org.springframework.http.ResponseEntity;
import org.springframework.web.bind.annotation.*;

import java.util.Map;

/**
 * ══════════════════════════════════════════════════════════════
 *  TASK 5: Display authentication results
 * ══════════════════════════════════════════════════════════════
 *
 *  POST /api/auth/register  → register a new user (encrypts password)
 *  POST /api/auth/login     → authenticate and return result
 *  POST /api/auth/verify    → manually verify a password against a hash
 *  POST /api/auth/encode    → show what BCrypt does to a plain-text password
 */
@RestController
@RequestMapping("/api/auth")
public class AuthController {

    private static final Logger log = LoggerFactory.getLogger(AuthController.class);

    private final UserService           userService;
    private final AuthenticationService authService;

    public AuthController(UserService userService, AuthenticationService authService) {
        this.userService  = userService;
        this.authService  = authService;
    }

    // ─── 1. Register ─────────────────────────────────────────────────

    /**
     * Register a new user.
     * The plain-text password is hashed with BCrypt before DB insertion.
     *
     * Example:
     *   POST /api/auth/register
     *   { "username":"john", "email":"john@test.com", "password":"secret123" }
     */
    @PostMapping("/register")
    public ResponseEntity<?> register(@Valid @RequestBody RegisterRequest request) {
        try {
            User saved = userService.registerUser(request);

            return ResponseEntity.status(HttpStatus.CREATED).body(Map.of(
                "success",           true,
                "message",           "User registered successfully! Password encrypted with BCrypt.",
                "username",          saved.getUsername(),
                "email",             saved.getEmail(),
                "role",              saved.getRole().name(),
                "encryptedPassword", saved.getPassword(),   // safe — this is the HASH, not plain text
                "note",              "The plain-text password is NOT stored. Only the BCrypt hash is persisted."
            ));

        } catch (IllegalArgumentException ex) {
            return ResponseEntity.status(HttpStatus.CONFLICT).body(Map.of(
                "success", false,
                "message", ex.getMessage()
            ));
        }
    }

    // ─── 2. Login ────────────────────────────────────────────────────

    /**
     * Authenticate a user.
     * Spring Security's PasswordEncoder.matches() validates the password
     * against the stored BCrypt hash without decrypting it.
     *
     * Example (success):
     *   POST /api/auth/login
     *   { "username":"alice", "password":"alice@123" }
     *
     * Example (failure):
     *   POST /api/auth/login
     *   { "username":"alice", "password":"wrongPassword" }
     */
    @PostMapping("/login")
    public ResponseEntity<AuthResponse> login(@Valid @RequestBody LoginRequest request) {
        AuthResponse response = authService.authenticate(request);
        HttpStatus   status   = response.isSuccess() ? HttpStatus.OK : HttpStatus.UNAUTHORIZED;
        return ResponseEntity.status(status).body(response);
    }

    // ─── 3. Explicit verify ──────────────────────────────────────────

    /**
     * Verify whether a plain-text password matches a BCrypt hash.
     * Useful for understanding how BCrypt.matches() works.
     *
     * Example:
     *   POST /api/auth/verify
     *   { "rawPassword":"alice@123",
     *     "storedHash":"$2a$12$..." }
     */
    @PostMapping("/verify")
    public ResponseEntity<?> verifyPassword(@RequestBody Map<String, String> body) {
        String rawPassword = body.get("rawPassword");
        String storedHash  = body.get("storedHash");

        if (rawPassword == null || storedHash == null) {
            return ResponseEntity.badRequest().body(Map.of(
                "error", "Both 'rawPassword' and 'storedHash' fields are required."
            ));
        }

        boolean matches = authService.verifyPassword(rawPassword, storedHash);

        return ResponseEntity.ok(Map.of(
            "rawPassword",  rawPassword,
            "storedHash",   storedHash,
            "matches",      matches,
            "explanation",  matches
                ? "BCrypt re-hashed the plain text with the embedded salt and the hashes matched."
                : "The hashes did NOT match — password is incorrect."
        ));
    }

    // ─── 4. Encode demo ──────────────────────────────────────────────

    /**
     * Encode a plain-text password and return the BCrypt hash.
     * Call this multiple times with the same password to see that
     * each call produces a DIFFERENT hash (random salt per call).
     *
     * Example:
     *   POST /api/auth/encode
     *   { "password": "mySecret" }
     */
    @PostMapping("/encode")
    public ResponseEntity<?> encodePassword(@RequestBody Map<String, String> body) {
        String rawPassword = body.get("password");

        if (rawPassword == null || rawPassword.isBlank()) {
            return ResponseEntity.badRequest().body(Map.of(
                "error", "'password' field is required."
            ));
        }

        String hash1 = authService.encodePassword(rawPassword);
        String hash2 = authService.encodePassword(rawPassword);   // will differ from hash1

        return ResponseEntity.ok(Map.of(
            "plainTextPassword", rawPassword,
            "bcryptHash1",       hash1,
            "bcryptHash2",       hash2,
            "sameHashes",        hash1.equals(hash2),
            "note",              "Even identical passwords produce different hashes due to random salt. " +
                                 "Both hashes will still PASS matches() against the same plain text."
        ));
    }
}
