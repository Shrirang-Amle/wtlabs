package com.exam32.service;

import com.exam32.model.AuthResponse;
import com.exam32.model.LoginRequest;
import com.exam32.model.User;
import com.exam32.repository.UserRepository;
import org.slf4j.Logger;
import org.slf4j.LoggerFactory;
import org.springframework.security.crypto.password.PasswordEncoder;
import org.springframework.stereotype.Service;
import org.springframework.transaction.annotation.Transactional;

import java.time.LocalDateTime;
import java.util.Optional;

/**
 * ══════════════════════════════════════════════════════════════
 *  TASK 3 & 4: Authenticate users + validate passwords
 * ══════════════════════════════════════════════════════════════
 *
 * Login flow:
 *  1. Look up the user by username in the database
 *  2. Call PasswordEncoder.matches(rawInput, storedHash)
 *     — BCrypt re-hashes the plain-text with the embedded salt
 *       and compares the result to the stored hash
 *  3. Return an AuthResponse indicating success or failure
 *
 * We NEVER decrypt the stored hash — BCrypt is a one-way function.
 * "Verification" means re-hashing and comparing, not decrypting.
 */
@Service
public class AuthenticationService {

    private static final Logger log = LoggerFactory.getLogger(AuthenticationService.class);

    private final UserRepository  userRepository;
    private final PasswordEncoder passwordEncoder;

    public AuthenticationService(UserRepository userRepository,
                                  PasswordEncoder passwordEncoder) {
        this.userRepository  = userRepository;
        this.passwordEncoder = passwordEncoder;
    }

    /**
     * Authenticates a login attempt.
     *
     * TASK 4 — Password validation:
     *   passwordEncoder.matches(rawPassword, storedHash)
     *   → extracts salt from storedHash
     *   → hashes rawPassword with that salt
     *   → returns true if the two hashes match
     */
    @Transactional
    public AuthResponse authenticate(LoginRequest request) {
        String rawPassword = request.getPassword();
        String username    = request.getUsername();

        log.info("Login attempt for username='{}'", username);

        // ── Step 1: Load user ────────────────────────────────────────
        Optional<User> optUser = userRepository.findByUsername(username);

        if (optUser.isEmpty()) {
            log.warn("Login failed — username '{}' not found", username);
            return AuthResponse.failure("User not found.");
        }

        User user = optUser.get();

        // ── Step 2: Check account is active ─────────────────────────
        if (!user.isEnabled()) {
            log.warn("Login failed — account '{}' is disabled", username);
            return AuthResponse.failure("Account is disabled.");
        }

        // ── Step 3: Verify password (TASK 4) ────────────────────────
        boolean passwordMatches = passwordEncoder.matches(rawPassword, user.getPassword());

        log.info("  Stored BCrypt hash : {}", user.getPassword());
        log.info("  Password matches   : {}", passwordMatches);

        if (!passwordMatches) {
            log.warn("Login failed — invalid password for '{}'", username);
            return AuthResponse.failure("Invalid password.");
        }

        // ── Step 4: Record last login time ───────────────────────────
        user.setLastLogin(LocalDateTime.now());
        userRepository.save(user);

        log.info("Login successful for '{}'", username);

        // ── Step 5: Return success response (TASK 5) ─────────────────
        return AuthResponse.success(
            user.getUsername(),
            user.getRole().name(),
            user.getPassword()
        );
    }

    /**
     * Utility: explicitly compare a raw password to a hash.
     * Useful for testing / demonstration purposes.
     */
    public boolean verifyPassword(String rawPassword, String storedHash) {
        return passwordEncoder.matches(rawPassword, storedHash);
    }

    /**
     * Utility: encode a plain-text password and return the BCrypt hash.
     * Demonstrates that each call produces a DIFFERENT hash (random salt).
     */
    public String encodePassword(String rawPassword) {
        return passwordEncoder.encode(rawPassword);
    }
}
