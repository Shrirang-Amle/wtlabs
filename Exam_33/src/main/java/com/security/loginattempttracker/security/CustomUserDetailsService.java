package com.security.loginattempttracker.security;

import com.security.loginattempttracker.entity.User;
import com.security.loginattempttracker.repository.UserRepository;
import com.security.loginattempttracker.service.LoginAttemptService;
import lombok.RequiredArgsConstructor;
import org.springframework.security.core.authority.SimpleGrantedAuthority;
import org.springframework.security.core.userdetails.UserDetails;
import org.springframework.security.core.userdetails.UserDetailsService;
import org.springframework.security.core.userdetails.UsernameNotFoundException;
import org.springframework.stereotype.Service;

import java.util.List;

/**
 * Custom UserDetailsService that loads user data from the database
 * and enforces account locking based on failed login attempts.
 */
@Service
@RequiredArgsConstructor
public class CustomUserDetailsService implements UserDetailsService {

    private final UserRepository userRepository;
    private final LoginAttemptService loginAttemptService;

    @Override
    public UserDetails loadUserByUsername(String username) throws UsernameNotFoundException {
        User user = userRepository.findByUsername(username)
                .orElseThrow(() -> new UsernameNotFoundException(
                        "No account found with username: " + username));

        // Check if the lock duration has expired and auto-unlock if so
        boolean locked = loginAttemptService.isAccountStillLocked(user);

        return org.springframework.security.core.userdetails.User.builder()
                .username(user.getUsername())
                .password(user.getPassword())
                .authorities(List.of(new SimpleGrantedAuthority(user.getRole())))
                .accountLocked(locked)
                .disabled(!user.isEnabled())
                .accountExpired(false)
                .credentialsExpired(false)
                .build();
    }
}
