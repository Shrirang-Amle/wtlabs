package com.security.loginattempttracker.security;

import com.security.loginattempttracker.config.SecurityProperties;
import com.security.loginattempttracker.entity.User;
import com.security.loginattempttracker.service.LoginAttemptService;
import com.security.loginattempttracker.service.UserService;
import jakarta.servlet.http.HttpServletRequest;
import jakarta.servlet.http.HttpServletResponse;
import lombok.RequiredArgsConstructor;
import lombok.extern.slf4j.Slf4j;
import org.springframework.security.authentication.LockedException;
import org.springframework.security.core.AuthenticationException;
import org.springframework.security.web.authentication.SimpleUrlAuthenticationFailureHandler;
import org.springframework.stereotype.Component;

import java.io.IOException;
import java.util.Optional;

/**
 * Handles authentication failures by building a descriptive error message
 * and redirecting back to the login page with the appropriate query parameter.
 *
 * Error codes surfaced to the login page:
 *   - locked          → account is locked, shows remaining minutes
 *   - bad_credentials → wrong password, shows remaining attempts
 *   - not_found       → username does not exist
 *   - error           → generic fallback
 */
@Component
@RequiredArgsConstructor
@Slf4j
public class CustomAuthenticationFailureHandler extends SimpleUrlAuthenticationFailureHandler {

    private final UserService userService;
    private final LoginAttemptService loginAttemptService;
    private final SecurityProperties securityProperties;

    @Override
    public void onAuthenticationFailure(HttpServletRequest request,
                                        HttpServletResponse response,
                                        AuthenticationException exception) throws IOException {

        String username = request.getParameter("username");
        String redirectUrl;

        if (exception instanceof LockedException) {
            // Account is locked — tell the user how long to wait
            long remaining = userService.findByUsername(username)
                    .map(loginAttemptService::getRemainingLockMinutes)
                    .orElse((long) securityProperties.getLockDurationMinutes());

            redirectUrl = "/login?locked&remaining=" + remaining;

        } else {
            // Bad credentials or unknown user
            Optional<User> userOpt = userService.findByUsername(username);

            if (userOpt.isEmpty()) {
                redirectUrl = "/login?not_found";
            } else {
                User user = userOpt.get();
                int attemptsLeft = securityProperties.getMaxFailedAttempts() - user.getFailedAttempts();
                attemptsLeft = Math.max(attemptsLeft, 0);
                redirectUrl = "/login?bad_credentials&attemptsLeft=" + attemptsLeft;
            }
        }

        log.debug("Redirecting failed login for '{}' to: {}", username, redirectUrl);
        response.sendRedirect(request.getContextPath() + redirectUrl);
    }
}
