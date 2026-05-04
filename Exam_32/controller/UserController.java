package com.exam32.controller;

import com.exam32.model.User;
import com.exam32.service.UserService;
import org.springframework.http.ResponseEntity;
import org.springframework.web.bind.annotation.*;

import java.util.List;
import java.util.Map;
import java.util.stream.Collectors;

/**
 * User management endpoints — lets you inspect the DB state.
 *
 *  GET /api/users         → list all users with hashed passwords
 *  GET /api/users/{name}  → fetch a single user by username
 */
@RestController
@RequestMapping("/api/users")
public class UserController {

    private final UserService userService;

    public UserController(UserService userService) {
        this.userService = userService;
    }

    /**
     * List every user stored in the database.
     * Encrypted passwords are shown so you can see that plain text
     * is NEVER persisted — only the BCrypt hash.
     */
    @GetMapping
    public ResponseEntity<?> listUsers() {
        List<User> users = userService.findAllUsers();

        List<Map<String, Object>> result = users.stream()
            .map(u -> Map.<String, Object>of(
                "id",               u.getId(),
                "username",         u.getUsername(),
                "email",            u.getEmail(),
                "role",             u.getRole().name(),
                "enabled",          u.isEnabled(),
                "encryptedPassword", u.getPassword(),
                "createdAt",        u.getCreatedAt().toString(),
                "lastLogin",        u.getLastLogin() != null ? u.getLastLogin().toString() : "Never"
            ))
            .collect(Collectors.toList());

        return ResponseEntity.ok(Map.of(
            "totalUsers", userService.countUsers(),
            "note",       "Passwords shown here are BCrypt hashes — original plain text is irrecoverable.",
            "users",      result
        ));
    }

    /**
     * Look up a single user by username.
     */
    @GetMapping("/{username}")
    public ResponseEntity<?> getUserByUsername(@PathVariable String username) {
        return userService.findByUsername(username)
            .map(u -> ResponseEntity.ok(Map.<String, Object>of(
                "id",                u.getId(),
                "username",          u.getUsername(),
                "email",             u.getEmail(),
                "role",              u.getRole().name(),
                "enabled",           u.isEnabled(),
                "encryptedPassword", u.getPassword(),
                "createdAt",         u.getCreatedAt().toString(),
                "lastLogin",         u.getLastLogin() != null ? u.getLastLogin().toString() : "Never"
            )))
            .orElseGet(() -> ResponseEntity.notFound().<Map<String, Object>>build());
    }
}
