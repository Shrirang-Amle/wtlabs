package com.exam32.repository;

import com.exam32.model.User;
import org.springframework.data.jpa.repository.JpaRepository;
import org.springframework.stereotype.Repository;

import java.util.Optional;

/**
 * Repository providing CRUD operations for the User entity.
 */
@Repository
public interface UserRepository extends JpaRepository<User, Long> {

    /** Find a user by username (used during login). */
    Optional<User> findByUsername(String username);

    /** Find a user by email address. */
    Optional<User> findByEmail(String email);

    /** Check if a username already exists (registration guard). */
    boolean existsByUsername(String username);

    /** Check if an email already exists (registration guard). */
    boolean existsByEmail(String email);
}
