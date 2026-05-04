package com.security.loginattempttracker.entity;

import jakarta.persistence.*;
import lombok.AllArgsConstructor;
import lombok.Builder;
import lombok.Data;
import lombok.NoArgsConstructor;

import java.time.LocalDateTime;

/**
 * Represents an application user with account locking support.
 * Tracks failed login attempts and lock timestamps.
 */
@Entity
@Table(name = "users")
@Data
@Builder
@NoArgsConstructor
@AllArgsConstructor
public class User {

    @Id
    @GeneratedValue(strategy = GenerationType.IDENTITY)
    private Long id;

    @Column(nullable = false, unique = true, length = 50)
    private String username;

    @Column(nullable = false)
    private String password;

    @Column(nullable = false, unique = true, length = 100)
    private String email;

    /** Number of consecutive failed login attempts since last successful login. */
    @Column(nullable = false)
    @Builder.Default
    private int failedAttempts = 0;

    /** Whether the account is currently locked due to too many failed attempts. */
    @Column(nullable = false)
    @Builder.Default
    private boolean accountLocked = false;

    /** Timestamp when the account was locked; null if not locked. */
    @Column
    private LocalDateTime lockTime;

    /** Whether the account is enabled (admin-controlled, separate from locking). */
    @Column(nullable = false)
    @Builder.Default
    private boolean enabled = true;

    /** Role assigned to the user, e.g. ROLE_USER or ROLE_ADMIN. */
    @Column(nullable = false, length = 20)
    @Builder.Default
    private String role = "ROLE_USER";
}
