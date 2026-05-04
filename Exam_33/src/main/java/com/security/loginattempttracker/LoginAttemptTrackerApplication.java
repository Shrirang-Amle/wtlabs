package com.security.loginattempttracker;

import org.springframework.boot.SpringApplication;
import org.springframework.boot.autoconfigure.SpringBootApplication;
import org.springframework.scheduling.annotation.EnableScheduling;

@SpringBootApplication
@EnableScheduling
public class LoginAttemptTrackerApplication {

    public static void main(String[] args) {
        SpringApplication.run(LoginAttemptTrackerApplication.class, args);
    }
}
