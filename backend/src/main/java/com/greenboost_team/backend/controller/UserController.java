package com.greenboost_team.backend.controller;

import com.greenboost_team.backend.dto.UserDto;
import com.greenboost_team.backend.entity.UserEntity;
import com.greenboost_team.backend.repository.UserRepository;
import org.springframework.http.HttpStatus;
import org.springframework.http.ResponseEntity;
import org.springframework.security.crypto.password.PasswordEncoder;
import org.springframework.web.bind.annotation.*;

import javax.annotation.Resource;

@RestController
@RequestMapping("/user")
public class UserController {

    @Resource
    private UserRepository userRepository;

    @Resource
    private PasswordEncoder passwordEncoder;


    @GetMapping("/getUser")
    public ResponseEntity<UserEntity> getUserByEmailAndPassword(@RequestParam String email, @RequestParam String password) {
        UserEntity result = userRepository.findByEmail(email);
        if(result == null){
            return new ResponseEntity<>(HttpStatus.BAD_REQUEST);
        } else if (passwordEncoder.matches(password, result.getPassword())) {
            return ResponseEntity.ok(result);
        } else {
            return new ResponseEntity<>(HttpStatus.NO_CONTENT);
        }
    }

    @PostMapping("/createUser")
    public ResponseEntity<UserEntity> createUserByEmailAndPassword(@RequestBody UserDto user) {
        if(userRepository.existsByEmail(user.getEmail())){
            return new ResponseEntity<>(HttpStatus.ALREADY_REPORTED);
        } else {
            return new ResponseEntity <>(userRepository.save(new UserEntity(user.getEmail(), passwordEncoder.encode(user.getPassword()), user.getFirstName(), user.getLastName())), HttpStatus.CREATED);
        }
    }
}
