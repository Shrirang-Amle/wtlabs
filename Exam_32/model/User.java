package com.exam32.model;

import jakarta.persistence.*;
import jakarta.validation.constraints.Email;
import jakarta.validation.constraints.NotBlank;
import jakarta.validation.constraints.Size;
import java.time.LocalDateTime;

/**
 * User entity — passwords are NEVER stored in plain text.
 * The 'password' field always holds a BCrypt-hashed value.
 */
@Entity
@Table(name = "users",
       uniqueConstraints = {
           @UniqueConstraint(columnNames = "username"),
           @UniqueConstraint(columnNames = "email")
       })
public class User {

    @Id
    @GeneratedValue(strategy = GenerationType.IDENTITY)
    private Long id;

    @NotBlank(message = "Username is required")
    @Size(min = 3, max = 50, message = "Username must be 3–50 characters")
    @Column(nullable = false, unique = true, length = 50)
    private String username;

    @NotBlank(message = "Email is required")
    @Email(message = "Email must be valid")
    @Column(nullable = false, unique = true, length = 100)
    private String email;

    /**
     * Stores the BCrypt-hashed password.
     * Plain-text passwords are NEVER persisted.
     */
    @NotBlank
    @Column(nullable = false)
    private String password;

    @Enumerated(EnumType.STRING)
    @Column(nullable = false, length = 20)
    private Role role = Role.ROLE_USER;

    @Column(nullable = false)
    private boolean enabled = true;

    @Column(name = "created_at", nullable = false, updatable = false)
    private LocalDateTime createdAt;

    @Column(name = "last_login")
    private LocalDateTime lastLogin;

    @PrePersist
    protected void onCreate() {
        createdAt = LocalDateTime.now();
    }

    // ─── Constructors ────────────────────────────────────────────────
    public User() {}

    public User(String username, String email, String password, Role role) {
        this.username = username;
        this.email    = email;
        this.password = password;
        this.role     = role;
    }

    // ─── Getters & Setters ───────────────────────────────────────────
    public Long getId()                      { return id; }
    public void setId(Long id)               { this.id = id; }

    public String getUsername()              { return username; }
    public void setUsername(String username) { this.username = username; }

    public String getEmail()                 { return email; }
    public void setEmail(String email)       { this.email = email; }

    public String getPassword()              { return password; }
    public void setPassword(String password) { this.password = password; }

    public Role getRole()                    { return role; }
    public void setRole(Role role)           { this.role = role; }

    public boolean isEnabled()               { return enabled; }
    public void setEnabled(boolean enabled)  { this.enabled = enabled; }

    public LocalDateTime getCreatedAt()                  { return createdAt; }
    public LocalDateTime getLastLogin()                  { return lastLogin; }
    public void setLastLogin(LocalDateTime lastLogin)    { this.lastLogin = lastLogin; }

    @Override
    public String toString() {
        return "User{id=" + id + ", username='" + username + "', email='" + email +
               "', role=" + role + ", enabled=" + enabled + "}";
    }
}
