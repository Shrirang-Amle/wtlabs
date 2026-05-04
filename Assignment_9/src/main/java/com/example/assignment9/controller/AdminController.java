package com.example.assignment9.controller;

import org.springframework.web.bind.annotation.GetMapping;
import org.springframework.web.bind.annotation.RequestMapping;
import org.springframework.web.bind.annotation.RestController;

import java.util.Map;

@RestController
@RequestMapping("/api/admin")
public class AdminController {

    @GetMapping("/status")
    public Map<String, String> getAdminStatus() {
        return Map.of(
                "message", "Restricted admin endpoint accessed successfully.",
                "access", "ADMIN role required"
        );
    }
}
