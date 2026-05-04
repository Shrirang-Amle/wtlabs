package com.security.loginattempttracker.repository;

import com.security.loginattempttracker.entity.User;
import org.springframework.data.jpa.repository.JpaRepository;
import org.springframework.data.jpa.repository.Modifying;
import org.springframework.data.jpa.repository.Query;
import org.springframework.stereotype.Repository;

import java.time.LocalDateTime;
import java.util.List;
import java.util.Optional;

@Repository
public interface UserRepository extends JpaRepository<User, Long> {

    Optional<User> findByUsername(String username);

    /** Increment failed attempt counter for a given username. */
    @Modifying
    @Query("UPDATE User u SET u.failedAttempts = u.failedAttempts + 1 WHERE u.username = :username")
    void incrementFailedAttempts(String username);

    /** Reset failed attempts and clear lock on successful login. */
    @Modifying
    @Query("UPDATE User u SET u.failedAttempts = 0, u.accountLocked = false, u.lockTime = null WHERE u.username = :username")
    void resetFailedAttempts(String username);

    /** Lock the account and record the lock timestamp. */
    @Modifying
    @Query("UPDATE User u SET u.accountLocked = true, u.lockTime = :lockTime WHERE u.username = :username")
    void lockAccount(String username, LocalDateTime lockTime);

    /** Unlock accounts whose lock time has expired. */
    @Modifying
    @Query("UPDATE User u SET u.accountLocked = false, u.failedAttempts = 0, u.lockTime = null " +
           "WHERE u.accountLocked = true AND u.lockTime <= :expiryTime")
    int unlockExpiredAccounts(LocalDateTime expiryTime);

    /** Find all currently locked accounts (for admin/monitoring). */
    List<User> findByAccountLockedTrue();
}
