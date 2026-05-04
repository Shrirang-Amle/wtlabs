package com.security.loginattempttracker.config;

import com.security.loginattempttracker.entity.User;
import com.security.loginattempttracker.repository.UserRepository;
import lombok.RequiredArgsConstructor;
import lombok.extern.slf4j.Slf4j;
import org.springframework.boot.CommandLineRunner;
import org.springframework.security.crypto.password.PasswordEncoder;
import org.springframework.stereotype.Component;

/**
 * Seeds the database with demo users on startup.
 *
 * Demo accounts:
 *   user  / password  → ROLE_USER
 *   admin / admin123  → ROLE_ADMIN
 */
@Component
@RequiredArgsConstructor
@Slf4j
public class DataInitializer implements CommandLineRunner {

    private final UserRepository userRepository;
    private final PasswordEncoder passwordEncoder;

    @Override
    public void run(String... args) {
        if (userRepository.count() == 0) {
            User regularUser = User.builder()
                    .username("user")
                    .password(passwordEncoder.encode("password"))
                    .email("user@example.com")
                    .role("ROLE_USER")
                    .build();

            User adminUser = User.builder()
                    .username("admin")
                    .password(passwordEncoder.encode("admin123"))
                    .email("admin@example.com")
                    .role("ROLE_ADMIN")
                    .build();

            userRepository.save(regularUser);
            userRepository.save(adminUser);

            log.info("Demo users created: 'user' (ROLE_USER) and 'admin' (ROLE_ADMIN)");
        }
    }
}
