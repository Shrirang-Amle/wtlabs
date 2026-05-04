package com.security.loginattempttracker.config;

import com.security.loginattempttracker.security.CustomAuthenticationFailureHandler;
import com.security.loginattempttracker.security.CustomUserDetailsService;
import lombok.RequiredArgsConstructor;
import org.springframework.context.annotation.Bean;
import org.springframework.context.annotation.Configuration;
import org.springframework.security.authentication.AuthenticationEventPublisher;
import org.springframework.security.authentication.DefaultAuthenticationEventPublisher;
import org.springframework.security.config.annotation.web.builders.HttpSecurity;
import org.springframework.security.config.annotation.web.configuration.EnableWebSecurity;
import org.springframework.security.web.SecurityFilterChain;

/**
 * Central Spring Security configuration.
 *
 * Key decisions:
 * - BCrypt for password hashing (cost factor 12)
 * - Form login with custom failure handler for rich error messages
 * - AuthenticationEventPublisher wired so our event listener receives events
 * - H2 console and public pages are permitted without authentication
 */
@Configuration
@EnableWebSecurity
@RequiredArgsConstructor
public class SecurityConfig {

    private final CustomUserDetailsService userDetailsService;
    private final CustomAuthenticationFailureHandler failureHandler;

    /**
     * Publishes authentication success/failure events to the application context,
     * enabling our AuthenticationEventListener to react to them.
     */
    @Bean
    public AuthenticationEventPublisher authenticationEventPublisher(
            org.springframework.context.ApplicationEventPublisher publisher) {
        return new DefaultAuthenticationEventPublisher(publisher);
    }

    @Bean
    public SecurityFilterChain securityFilterChain(HttpSecurity http) throws Exception {
        http
            .authorizeHttpRequests(auth -> auth
                // Public endpoints
                .requestMatchers("/login", "/register", "/css/**", "/js/**").permitAll()
                // H2 console (dev only — remove in production)
                .requestMatchers("/h2-console/**").permitAll()
                // Admin area requires ADMIN role
                .requestMatchers("/admin/**").hasRole("ADMIN")
                // Everything else requires authentication
                .anyRequest().authenticated()
            )
            .formLogin(form -> form
                .loginPage("/login")
                .loginProcessingUrl("/login")
                .usernameParameter("username")
                .passwordParameter("password")
                .defaultSuccessUrl("/dashboard", true)
                .failureHandler(failureHandler)
                .permitAll()
            )
            .logout(logout -> logout
                .logoutUrl("/logout")
                .logoutSuccessUrl("/login?logout")
                .invalidateHttpSession(true)
                .deleteCookies("JSESSIONID")
                .permitAll()
            )
            .userDetailsService(userDetailsService)
            // Allow H2 console frames (dev only)
            .headers(headers -> headers.frameOptions(frame -> frame.sameOrigin()))
            .csrf(csrf -> csrf
                // Disable CSRF for H2 console path only
                .ignoringRequestMatchers("/h2-console/**")
            );

        return http.build();
    }
}
