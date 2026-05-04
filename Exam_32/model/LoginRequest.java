package com.exam32.model;

import jakarta.validation.constraints.NotBlank;

/**
 * DTO carrying login credentials from the client.
 */
public class LoginRequest {

    @NotBlank(message = "Username is required")
    private String username;

    @NotBlank(message = "Password is required")
    private String password;

    // ─── Getters & Setters ───────────────────────────────────────────
    public String getUsername()              { return username; }
    public void setUsername(String username) { this.username = username; }

    public String getPassword()              { return password; }
    public void setPassword(String password) { this.password = password; }
}
