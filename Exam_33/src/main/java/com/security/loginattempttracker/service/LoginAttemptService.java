package com.security.loginattempttracker.service;

import com.security.loginattempttracker.config.SecurityProperties;
import com.security.loginattempttracker.entity.User;
import com.security.loginattempttracker.repository.UserRepository;
import lombok.RequiredArgsConstructor;
import lombok.extern.slf4j.Slf4j;
import org.springframework.scheduling.annotation.Scheduled;
import org.springframework.stereotype.Service;
import org.springframework.transaction.annotation.Transactional;

import java.time.LocalDateTime;

/**
 * Handles all logic related to tracking failed login attempts,
 * locking accounts, and automatically unlocking them after the lock duration.
 */
@Service
@RequiredArgsConstructor
@Slf4j
public class LoginAttemptService {

    private final UserRepository userRepository;
    private final SecurityProperties securityProperties;

    /**
     * Called on a successful login. Resets the failed attempt counter
     * and clears any existing lock for the user.
     */
    @Transactional
    public void loginSucceeded(String username) {
        userRepository.resetFailedAttempts(username);
        log.info("Login succeeded for user '{}'. Failed attempts reset.", username);
    }

    /**
     * Called on a failed login attempt. Increments the counter and
     * locks the account if the maximum threshold is reached.
     */
    @Transactional
    public void loginFailed(String username) {
        userRepository.findByUsername(username).ifPresent(user -> {
            int newFailedAttempts = user.getFailedAttempts() + 1;
            userRepository.incrementFailedAttempts(username);

            log.warn("Failed login attempt #{} for user '{}'.", newFailedAttempts, username);

            if (newFailedAttempts >= securityProperties.getMaxFailedAttempts()) {
                lockAccount(username);
            }
        });
    }

    /**
     * Locks the account and records the lock timestamp.
     */
    @Transactional
    public void lockAccount(String username) {
        LocalDateTime lockTime = LocalDateTime.now();
        userRepository.lockAccount(username, lockTime);
        log.warn("Account '{}' has been LOCKED at {} due to too many failed attempts.", username, lockTime);
    }

    /**
     * Manually unlocks an account (e.g., by an admin).
     */
    @Transactional
    public void unlockAccount(String username) {
        userRepository.resetFailedAttempts(username);
        log.info("Account '{}' has been manually UNLOCKED.", username);
    }

    /**
     * Checks whether the account is still within its lock window.
     * If the lock duration has passed, the account is automatically unlocked.
     *
     * @return true if the account is currently locked and the lock has not expired
     */
    @Transactional
    public boolean isAccountStillLocked(User user) {
        if (!user.isAccountLocked()) {
            return false;
        }

        LocalDateTime unlockTime = user.getLockTime()
                .plusMinutes(securityProperties.getLockDurationMinutes());

        if (LocalDateTime.now().isAfter(unlockTime)) {
            userRepository.resetFailedAttempts(user.getUsername());
            log.info("Account '{}' lock has expired and has been automatically unlocked.", user.getUsername());
            return false;
        }

        return true;
    }

    /**
     * Returns how many minutes remain until the account is unlocked.
     */
    public long getRemainingLockMinutes(User user) {
        if (user.getLockTime() == null) return 0;
        LocalDateTime unlockTime = user.getLockTime()
                .plusMinutes(securityProperties.getLockDurationMinutes());
        long remaining = java.time.Duration.between(LocalDateTime.now(), unlockTime).toMinutes();
        return Math.max(remaining, 0);
    }

    /**
     * Scheduled task that runs every minute to automatically unlock
     * accounts whose lock duration has expired.
     */
    @Scheduled(fixedDelay = 60_000)
    @Transactional
    public void unlockExpiredAccounts() {
        LocalDateTime expiryTime = LocalDateTime.now()
                .minusMinutes(securityProperties.getLockDurationMinutes());
        int unlocked = userRepository.unlockExpiredAccounts(expiryTime);
        if (unlocked > 0) {
            log.info("Scheduled unlock: {} account(s) automatically unlocked.", unlocked);
        }
    }

    public int getMaxFailedAttempts() {
        return securityProperties.getMaxFailedAttempts();
    }
}
