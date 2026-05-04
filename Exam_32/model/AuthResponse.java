package com.exam32.model;

import java.time.LocalDateTime;

/**
 * Response object returned to the client after a login attempt.
 * Communicates authentication success/failure clearly.
 */
public class AuthResponse {

    private boolean success;
    private String  message;
    private String  username;
    private String  role;
    private String  encryptedPasswordPreview; // first 29 chars of BCrypt hash (safe to show)
    private LocalDateTime timestamp;

    // ─── Static factory helpers ──────────────────────────────────────

    public static AuthResponse success(String username, String role, String encryptedPassword) {
        AuthResponse r = new AuthResponse();
        r.success = true;
        r.message = "Authentication successful! Password validated against BCrypt hash.";
        r.username = username;
        r.role = role;
        // Show only the BCrypt prefix so the student can see the hash format
        r.encryptedPasswordPreview = encryptedPassword.substring(0, Math.min(29, encryptedPassword.length())) + "...";
        r.timestamp = LocalDateTime.now();
        return r;
    }

    public static AuthResponse failure(String reason) {
        AuthResponse r = new AuthResponse();
        r.success = false;
        r.message = "Authentication failed: " + reason;
        r.timestamp = LocalDateTime.now();
        return r;
    }

    // ─── Getters ─────────────────────────────────────────────────────
    public boolean isSuccess()                          { return success; }
    public String  getMessage()                         { return message; }
    public String  getUsername()                        { return username; }
    public String  getRole()                            { return role; }
    public String  getEncryptedPasswordPreview()        { return encryptedPasswordPreview; }
    public LocalDateTime getTimestamp()                 { return timestamp; }
}
