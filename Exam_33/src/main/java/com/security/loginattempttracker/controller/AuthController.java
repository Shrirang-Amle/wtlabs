package com.security.loginattempttracker.controller;

import com.security.loginattempttracker.config.SecurityProperties;
import com.security.loginattempttracker.service.UserService;
import lombok.RequiredArgsConstructor;
import org.springframework.stereotype.Controller;
import org.springframework.ui.Model;
import org.springframework.web.bind.annotation.GetMapping;
import org.springframework.web.bind.annotation.PostMapping;
import org.springframework.web.bind.annotation.RequestParam;
import org.springframework.web.servlet.mvc.support.RedirectAttributes;

/**
 * Handles login page rendering and user registration.
 */
@Controller
@RequiredArgsConstructor
public class AuthController {

    private final UserService userService;
    private final SecurityProperties securityProperties;

    /**
     * Renders the login page.
     *
     * Query parameters drive the error message displayed:
     *   ?locked&remaining=N   → account locked, N minutes remaining
     *   ?bad_credentials&attemptsLeft=N → wrong password, N attempts left
     *   ?not_found            → username not found
     *   ?logout               → successful logout
     *   ?error                → generic Spring Security error fallback
     */
    @GetMapping("/login")
    public String loginPage(
            @RequestParam(required = false) String locked,
            @RequestParam(required = false) String bad_credentials,
            @RequestParam(required = false) String not_found,
            @RequestParam(required = false) String logout,
            @RequestParam(required = false) String error,
            @RequestParam(required = false, defaultValue = "0") long remaining,
            @RequestParam(required = false, defaultValue = "0") int attemptsLeft,
            Model model) {

        if (locked != null) {
            model.addAttribute("errorType", "locked");
            model.addAttribute("remaining", remaining);
            model.addAttribute("lockDuration", securityProperties.getLockDurationMinutes());
        } else if (bad_credentials != null) {
            model.addAttribute("errorType", "bad_credentials");
            model.addAttribute("attemptsLeft", attemptsLeft);
            model.addAttribute("maxAttempts", securityProperties.getMaxFailedAttempts());
        } else if (not_found != null) {
            model.addAttribute("errorType", "not_found");
        } else if (error != null) {
            model.addAttribute("errorType", "error");
        } else if (logout != null) {
            model.addAttribute("logoutSuccess", true);
        }

        return "login";
    }

    /** Renders the registration form. */
    @GetMapping("/register")
    public String registerPage() {
        return "register";
    }

    /** Processes the registration form submission. */
    @PostMapping("/register")
    public String registerUser(
            @RequestParam String username,
            @RequestParam String password,
            @RequestParam String email,
            RedirectAttributes redirectAttributes) {

        try {
            userService.registerUser(username, password, email);
            redirectAttributes.addFlashAttribute("registrationSuccess",
                    "Account created successfully. You can now log in.");
            return "redirect:/login";
        } catch (IllegalArgumentException e) {
            redirectAttributes.addFlashAttribute("registrationError", e.getMessage());
            return "redirect:/register";
        }
    }
}
