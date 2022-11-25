package com.greenboost_team.backend.service;

import com.greenboost_team.backend.entity.UserEntity;
import com.greenboost_team.backend.repository.UserRepository;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.http.HttpStatus;
import org.springframework.http.ResponseEntity;
import org.springframework.security.crypto.password.PasswordEncoder;
import org.springframework.stereotype.Service;
import org.springframework.web.bind.annotation.GetMapping;
import org.springframework.web.bind.annotation.RequestParam;

@Service
public class UserService {

    @Autowired
    private UserRepository userRepository;

    @Autowired
    private PasswordEncoder passwordEncoder;


    @GetMapping("/user/{email}/{password}")
    public ResponseEntity<UserEntity> getUserByEmailAndPassword(@RequestParam String email, @RequestParam String password) {
        UserEntity result = userRepository.findOneByEmailAndPassword(email, passwordEncoder.encode(password));

        return result == null ? new ResponseEntity<>(HttpStatus.NOT_FOUND) : ResponseEntity.ok(result);
    }
}
