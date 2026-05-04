package com.exam32.service;

import com.exam32.model.RegisterRequest;
import com.exam32.model.Role;
import com.exam32.model.User;
import com.exam32.repository.UserRepository;
import org.slf4j.Logger;
import org.slf4j.LoggerFactory;
import org.springframework.security.crypto.password.PasswordEncoder;
import org.springframework.stereotype.Service;
import org.springframework.transaction.annotation.Transactional;

import java.util.List;
import java.util.Optional;

/**
 * ══════════════════════════════════════════════════════════════
 *  TASK 2: Store encrypted passwords in the database
 * ══════════════════════════════════════════════════════════════
 *
 * Every time a new user registers:
 *  1. Validate uniqueness of username / email
 *  2. Encode the plain-text password with BCrypt
 *  3. Persist ONLY the hashed value — the plain text is discarded
 */
@Service
@Transactional
public class UserService {

    private static final Logger log = LoggerFactory.getLogger(UserService.class);

    private final UserRepository  userRepository;
    private final PasswordEncoder passwordEncoder;

    public UserService(UserRepository userRepository, PasswordEncoder passwordEncoder) {
        this.userRepository  = userRepository;
        this.passwordEncoder = passwordEncoder;
    }

    // ─── Registration ────────────────────────────────────────────────

    /**
     * Registers a new user, hashing the password before persistence.
     *
     * @param request  DTO with raw credentials from the client
     * @return         persisted User with encrypted password
     * @throws IllegalArgumentException if username / email already taken
     */
    public User registerUser(RegisterRequest request) {
        // Guard: unique username
        if (userRepository.existsByUsername(request.getUsername())) {
            throw new IllegalArgumentException(
                "Username '" + request.getUsername() + "' is already taken.");
        }
        // Guard: unique email
        if (userRepository.existsByEmail(request.getEmail())) {
            throw new IllegalArgumentException(
                "Email '" + request.getEmail() + "' is already registered.");
        }

        // ── TASK 2 core: encode the plain-text password ──────────────
        String plainText    = request.getPassword();
        String encryptedPwd = passwordEncoder.encode(plainText);

        log.info("Registering user '{}'", request.getUsername());
        log.debug("  Plain text  : {}", plainText);
        log.debug("  BCrypt hash : {}", encryptedPwd);

        // Determine role
        Role role = "ROLE_ADMIN".equalsIgnoreCase(request.getRole())
                    ? Role.ROLE_ADMIN
                    : Role.ROLE_USER;

        User user = new User(
            request.getUsername(),
            request.getEmail(),
            encryptedPwd,   // ← hashed password stored
            role
        );

        return userRepository.save(user);
    }

    // ─── Queries ─────────────────────────────────────────────────────

    public Optional<User> findByUsername(String username) {
        return userRepository.findByUsername(username);
    }

    public List<User> findAllUsers() {
        return userRepository.findAll();
    }

    public long countUsers() {
        return userRepository.count();
    }
}
