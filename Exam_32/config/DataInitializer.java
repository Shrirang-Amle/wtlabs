package com.exam32.config;

import com.exam32.model.Role;
import com.exam32.model.User;
import com.exam32.repository.UserRepository;
import org.slf4j.Logger;
import org.slf4j.LoggerFactory;
import org.springframework.boot.CommandLineRunner;
import org.springframework.context.annotation.Bean;
import org.springframework.context.annotation.Configuration;
import org.springframework.security.crypto.password.PasswordEncoder;

/**
 * Seeds the H2 database with sample users on every startup.
 * Demonstrates TASK 2: passwords are encrypted before being stored.
 */
@Configuration
public class DataInitializer {

    private static final Logger log = LoggerFactory.getLogger(DataInitializer.class);

    @Bean
    public CommandLineRunner seedUsers(UserRepository repo, PasswordEncoder encoder) {
        return args -> {
            log.info("════════════════════════════════════════════════════");
            log.info("  Seeding demo users — passwords encrypted with BCrypt");
            log.info("════════════════════════════════════════════════════");

            createUser(repo, encoder, "alice",   "alice@example.com",  "alice@123",   Role.ROLE_USER);
            createUser(repo, encoder, "bob",     "bob@example.com",    "bob@456",     Role.ROLE_USER);
            createUser(repo, encoder, "admin",   "admin@example.com",  "admin@999",   Role.ROLE_ADMIN);

            log.info("Seeded {} users.", repo.count());
            log.info("H2 Console → http://localhost:8080/h2-console");
            log.info("  JDBC URL : jdbc:h2:mem:exam32db");
            log.info("  Username : sa  |  Password: (empty)");
        };
    }

    private void createUser(UserRepository repo, PasswordEncoder encoder,
                            String username, String email,
                            String rawPassword, Role role) {
        if (repo.existsByUsername(username)) return;

        String hashed = encoder.encode(rawPassword);

        User user = new User(username, email, hashed, role);
        repo.save(user);

        log.info("  Created user '{}' | plain='{}' | bcrypt='{}'",
                 username, rawPassword, hashed);
    }
}
