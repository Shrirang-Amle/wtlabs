package com.security.loginattempttracker.controller;

import com.security.loginattempttracker.service.LoginAttemptService;
import com.security.loginattempttracker.service.UserService;
import lombok.RequiredArgsConstructor;
import org.springframework.stereotype.Controller;
import org.springframework.ui.Model;
import org.springframework.web.bind.annotation.GetMapping;
import org.springframework.web.bind.annotation.PathVariable;
import org.springframework.web.bind.annotation.PostMapping;
import org.springframework.web.bind.annotation.RequestMapping;
import org.springframework.web.servlet.mvc.support.RedirectAttributes;

/**
 * Admin-only controller for managing user accounts and viewing locked accounts.
 * Accessible only to users with ROLE_ADMIN.
 */
@Controller
@RequestMapping("/admin")
@RequiredArgsConstructor
public class AdminController {

    private final UserService userService;
    private final LoginAttemptService loginAttemptService;

    /** Lists all users and highlights locked accounts. */
    @GetMapping("/users")
    public String listUsers(Model model) {
        model.addAttribute("users", userService.findAllUsers());
        model.addAttribute("lockedAccounts", userService.findAllLockedAccounts());
        return "admin/users";
    }

    /** Manually unlocks a specific user account. */
    @PostMapping("/users/{username}/unlock")
    public String unlockUser(@PathVariable String username, RedirectAttributes redirectAttributes) {
        userService.findByUsername(username).ifPresentOrElse(
                user -> {
                    loginAttemptService.unlockAccount(username);
                    redirectAttributes.addFlashAttribute("successMessage",
                            "Account '" + username + "' has been unlocked.");
                },
                () -> redirectAttributes.addFlashAttribute("errorMessage",
                        "User '" + username + "' not found.")
        );
        return "redirect:/admin/users";
    }

    /** Manually locks a specific user account. */
    @PostMapping("/users/{username}/lock")
    public String lockUser(@PathVariable String username, RedirectAttributes redirectAttributes) {
        userService.findByUsername(username).ifPresentOrElse(
                user -> {
                    loginAttemptService.lockAccount(username);
                    redirectAttributes.addFlashAttribute("successMessage",
                            "Account '" + username + "' has been locked.");
                },
                () -> redirectAttributes.addFlashAttribute("errorMessage",
                        "User '" + username + "' not found.")
        );
        return "redirect:/admin/users";
    }
}
