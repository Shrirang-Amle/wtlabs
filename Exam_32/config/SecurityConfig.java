package com.exam32.config;

import org.springframework.context.annotation.Bean;
import org.springframework.context.annotation.Configuration;
import org.springframework.security.config.annotation.web.builders.HttpSecurity;
import org.springframework.security.config.annotation.web.configuration.EnableWebSecurity;
import org.springframework.security.config.annotation.web.configurers.AbstractHttpConfigurer;
import org.springframework.security.config.annotation.web.configurers.HeadersConfigurer;
import org.springframework.security.crypto.bcrypt.BCryptPasswordEncoder;
import org.springframework.security.crypto.password.PasswordEncoder;
import org.springframework.security.web.SecurityFilterChain;

/**
 * ══════════════════════════════════════════════════════════════
 *  TASK 1: Configure BCrypt Password Encoder
 * ══════════════════════════════════════════════════════════════
 *
 * BCryptPasswordEncoder is the industry-standard choice because:
 *  • It automatically salts every password (no collision attacks)
 *  • Strength factor (rounds) is configurable — default is 10
 *  • The same plain-text password produces a DIFFERENT hash each
 *    time (salt is embedded in the hash for later verification)
 *
 * Hash format:  $2a$10$<22-char salt><31-char hash>
 *               └─┘ └┘
 *               algo  cost
 */
@Configuration
@EnableWebSecurity
public class SecurityConfig {

    /**
     * BCryptPasswordEncoder bean — injected wherever password
     * hashing or verification is needed.
     *
     * Strength 12 → 2^12 = 4096 rounds (stronger than default 10).
     */
    @Bean
    public PasswordEncoder passwordEncoder() {
        return new BCryptPasswordEncoder(12);
    }

    /**
     * Security filter chain.
     *
     * For this demo we expose all API endpoints publicly so you
     * can test registration/login via curl or Postman without
     * needing to manage session cookies.
     *
     * H2 console access is also enabled for database inspection.
     */
    @Bean
    public SecurityFilterChain filterChain(HttpSecurity http) throws Exception {
        http
            // Disable CSRF for stateless REST demo
            .csrf(AbstractHttpConfigurer::disable)

            // Allow frames for H2 console
            .headers(headers ->
                headers.frameOptions(HeadersConfigurer.FrameOptionsConfig::sameOrigin))

            .authorizeHttpRequests(auth -> auth
                // Public endpoints
                .requestMatchers(
                    "/api/auth/**",
                    "/api/users/**",
                    "/h2-console/**"
                ).permitAll()
                // Everything else requires authentication
                .anyRequest().authenticated()
            );

        return http.build();
    }
}
