package com.security.loginattempttracker.security;

import com.security.loginattempttracker.service.LoginAttemptService;
import lombok.RequiredArgsConstructor;
import lombok.extern.slf4j.Slf4j;
import org.springframework.context.event.EventListener;
import org.springframework.security.authentication.event.AbstractAuthenticationFailureEvent;
import org.springframework.security.authentication.event.AuthenticationSuccessEvent;
import org.springframework.stereotype.Component;

/**
 * Listens to Spring Security authentication events and delegates
 * to LoginAttemptService to update the failed attempt counters.
 *
 * Using application events decouples the tracking logic from the
 * authentication filter chain.
 */
@Component
@RequiredArgsConstructor
@Slf4j
public class AuthenticationEventListener {

    private final LoginAttemptService loginAttemptService;

    /**
     * Triggered after a successful authentication.
     * Resets the failed attempt counter for the authenticated user.
     */
    @EventListener
    public void onAuthenticationSuccess(AuthenticationSuccessEvent event) {
        String username = event.getAuthentication().getName();
        loginAttemptService.loginSucceeded(username);
        log.info("Authentication SUCCESS for user '{}'", username);
    }

    /**
     * Triggered after any authentication failure (bad credentials, locked, etc.).
     * Increments the failed attempt counter only for bad-credentials failures
     * to avoid double-counting when the account is already locked.
     */
    @EventListener
    public void onAuthenticationFailure(AbstractAuthenticationFailureEvent event) {
        String username = event.getAuthentication().getName();
        String exceptionClass = event.getException().getClass().getSimpleName();

        log.warn("Authentication FAILURE for user '{}': {}", username, exceptionClass);

        // Only count bad credentials — not already-locked or disabled accounts
        if ("BadCredentialsException".equals(exceptionClass)) {
            loginAttemptService.loginFailed(username);
        }
    }
}
