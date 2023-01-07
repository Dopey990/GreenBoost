package com.greenboost_team.backend.controller;

import com.greenboost_team.backend.dto.UserDto;
import com.greenboost_team.backend.entity.UserEntity;
import com.greenboost_team.backend.mapper.UserMapper;
import com.greenboost_team.backend.repository.UserRepository;
import org.springframework.http.HttpStatus;
import org.springframework.http.ResponseEntity;
import org.springframework.security.crypto.password.PasswordEncoder;
import org.springframework.web.bind.annotation.*;

import java.util.UUID;

import javax.annotation.Resource;

@RestController
@RequestMapping("/user")
public class UserController {

    @Resource
    private UserRepository userRepository;

    @Resource
    private PasswordEncoder passwordEncoder;

    @Resource
    private UserMapper userMapper;


    @GetMapping("/getUserByEmailAndPassword")
    public ResponseEntity<UserDto> getUserByEmailAndPassword(@RequestParam String email, @RequestParam String password) {
        UserEntity result = userRepository.findByEmail(email);
        if(result == null){
            return new ResponseEntity<>(HttpStatus.NO_CONTENT);
        } else if (passwordEncoder.matches(password, result.getPassword())) {
            return ResponseEntity.ok(userMapper.entityToDto(result));
        } else {
            return new ResponseEntity<>(HttpStatus.FORBIDDEN);
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

    @PostMapping("/createUserClearPassword")
    public ResponseEntity<UserEntity> createUserByEmailAndClearPassword(@RequestBody UserDto user) {
        if(userRepository.existsByEmail(user.getEmail())){
            return new ResponseEntity<>(HttpStatus.ALREADY_REPORTED);
        } else {
            return new ResponseEntity <>(userRepository.save(new UserEntity(user.getEmail(), user.getPassword(), user.getFirstName(), user.getLastName())), HttpStatus.CREATED);
        }
    }

    @GetMapping("/getUserToken")
    public ResponseEntity<String> getUserToken(@RequestParam String email, @RequestParam String password) {
        UserEntity user = userRepository.findByEmail(email);
        if (user == null) {
            return new ResponseEntity<>(HttpStatus.BAD_REQUEST);
        }
        if (passwordEncoder.matches(password, user.getPassword())) {
            String token = UUID.randomUUID().toString();
            // On pourrait enregistrer le token dans une base de données ou dans un cache
            return ResponseEntity.ok(token);
        } else {
            return new ResponseEntity<>(HttpStatus.NO_CONTENT);
        }
    }

    @GetMapping("/getUserByToken")
    public ResponseEntity<UserDto> getUserByToken(@RequestParam String token) {
        UserEntity result = userRepository.findByToken(token);
        if(result == null){
            return new ResponseEntity<>(HttpStatus.NO_CONTENT);
        }
        else {
            return ResponseEntity.ok(userMapper.entityToDto(result));
        }
    }
}
