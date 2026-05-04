package com.security.loginattempttracker.config;

import lombok.Data;
import org.springframework.boot.context.properties.ConfigurationProperties;
import org.springframework.stereotype.Component;

/**
 * Externalized configuration for login attempt security settings.
 * Values are read from application.properties under the "app.security" prefix.
 */
@Component
@ConfigurationProperties(prefix = "app.security")
@Data
public class SecurityProperties {

    /** Maximum number of failed login attempts before locking the account. */
    private int maxFailedAttempts = 5;

    /** Duration in minutes for which the account remains locked. */
    private long lockDurationMinutes = 15;
}
