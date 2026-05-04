package com.exam32;

import com.exam32.model.AuthResponse;
import com.exam32.model.LoginRequest;
import com.exam32.model.RegisterRequest;
import com.exam32.model.User;
import com.exam32.repository.UserRepository;
import com.exam32.service.AuthenticationService;
import com.exam32.service.UserService;
import org.junit.jupiter.api.BeforeEach;
import org.junit.jupiter.api.DisplayName;
import org.junit.jupiter.api.Test;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.boot.test.context.SpringBootTest;
import org.springframework.security.crypto.password.PasswordEncoder;
import org.springframework.test.context.ActiveProfiles;
import org.springframework.transaction.annotation.Transactional;

import static org.junit.jupiter.api.Assertions.*;

/**
 * Integration tests covering all 5 tasks.
 */
@SpringBootTest
@Transactional
@ActiveProfiles("test")
class exam32ApplicationTests {

    @Autowired private PasswordEncoder       passwordEncoder;
    @Autowired private UserService           userService;
    @Autowired private AuthenticationService authService;
    @Autowired private UserRepository        userRepository;

    private static final String RAW_PASSWORD = "testPass@123";

    // ─── TASK 1: BCrypt encoder configuration ────────────────────────

    @Test
    @DisplayName("Task 1 - BCryptPasswordEncoder bean is configured")
    void task1_bcryptEncoderIsConfigured() {
        assertNotNull(passwordEncoder, "PasswordEncoder bean must not be null");
        // Verify it is BCrypt by encoding and matching
        String hash = passwordEncoder.encode(RAW_PASSWORD);
        assertTrue(hash.startsWith("$2a$"), "BCrypt hashes start with $2a$");
        assertTrue(hash.length() == 60, "BCrypt hash is always 60 characters");
    }

    @Test
    @DisplayName("Task 1 - Same password yields different hashes (random salt)")
    void task1_differentHashesPerCall() {
        String hash1 = passwordEncoder.encode(RAW_PASSWORD);
        String hash2 = passwordEncoder.encode(RAW_PASSWORD);
        assertNotEquals(hash1, hash2, "Each BCrypt call must embed a unique salt");
    }

    // ─── TASK 2: Encrypted storage ───────────────────────────────────

    @Test
    @DisplayName("Task 2 - Password is stored as BCrypt hash, not plain text")
    void task2_passwordStoredAsHash() {
        RegisterRequest req = buildRegisterRequest("testuser", "testuser@test.com", RAW_PASSWORD);
        User saved = userService.registerUser(req);

        assertNotNull(saved.getId(), "User must have a DB-assigned ID");
        assertNotEquals(RAW_PASSWORD, saved.getPassword(),
            "Plain-text password must NEVER be stored");
        assertTrue(saved.getPassword().startsWith("$2a$"),
            "Stored password must be a BCrypt hash");
    }

    // ─── TASK 3: Authenticate with encrypted passwords ───────────────

    @Test
    @DisplayName("Task 3 - Successful authentication with correct password")
    void task3_successfulAuthentication() {
        // Register
        userService.registerUser(
            buildRegisterRequest("authuser", "authuser@test.com", RAW_PASSWORD));

        // Login
        AuthResponse response = authService.authenticate(
            buildLoginRequest("authuser", RAW_PASSWORD));

        assertTrue(response.isSuccess(), "Authentication must succeed with correct password");
        assertEquals("authuser", response.getUsername());
    }

    // ─── TASK 4: Password validation ─────────────────────────────────

    @Test
    @DisplayName("Task 4 - Authentication fails with wrong password")
    void task4_wrongPasswordRejected() {
        userService.registerUser(
            buildRegisterRequest("validuser", "validuser@test.com", RAW_PASSWORD));

        AuthResponse response = authService.authenticate(
            buildLoginRequest("validuser", "wrongPassword!"));

        assertFalse(response.isSuccess(), "Wrong password must not authenticate");
        assertTrue(response.getMessage().contains("Invalid password"));
    }

    @Test
    @DisplayName("Task 4 - PasswordEncoder.matches() verifies correctly")
    void task4_matchesVerifiesCorrectly() {
        String hash = passwordEncoder.encode(RAW_PASSWORD);

        assertTrue(passwordEncoder.matches(RAW_PASSWORD, hash),
            "matches() must return true for the correct plain text");
        assertFalse(passwordEncoder.matches("wrongPass", hash),
            "matches() must return false for an incorrect plain text");
    }

    @Test
    @DisplayName("Task 4 - Unknown username returns failure response")
    void task4_unknownUsernameReturnsFailure() {
        AuthResponse response = authService.authenticate(
            buildLoginRequest("nobody", RAW_PASSWORD));

        assertFalse(response.isSuccess());
        assertTrue(response.getMessage().contains("not found"));
    }

    // ─── TASK 5: Authentication results ─────────────────────────────

    @Test
    @DisplayName("Task 5 - AuthResponse contains username and role on success")
    void task5_authResponseContainsDetails() {
        userService.registerUser(
            buildRegisterRequest("infouser", "infouser@test.com", RAW_PASSWORD));

        AuthResponse response = authService.authenticate(
            buildLoginRequest("infouser", RAW_PASSWORD));

        assertTrue(response.isSuccess());
        assertNotNull(response.getUsername());
        assertNotNull(response.getRole());
        assertNotNull(response.getTimestamp());
        assertNotNull(response.getEncryptedPasswordPreview());
        assertTrue(response.getEncryptedPasswordPreview().contains("..."),
            "Preview must be truncated");
    }

    // ─── Helpers ─────────────────────────────────────────────────────

    private RegisterRequest buildRegisterRequest(String username, String email, String password) {
        RegisterRequest r = new RegisterRequest();
        r.setUsername(username);
        r.setEmail(email);
        r.setPassword(password);
        return r;
    }

    private LoginRequest buildLoginRequest(String username, String password) {
        LoginRequest r = new LoginRequest();
        r.setUsername(username);
        r.setPassword(password);
        return r;
    }
}
