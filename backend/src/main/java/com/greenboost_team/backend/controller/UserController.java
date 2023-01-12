package com.greenboost_team.backend.controller;

import com.greenboost_team.backend.dto.UserDto;
import com.greenboost_team.backend.entity.HouseEntity;
import com.greenboost_team.backend.entity.UserEntity;
import com.greenboost_team.backend.repository.HouseRepository;
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
    private HouseRepository houseRepository;

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
            UserEntity userEntity = userRepository.save(new UserEntity(user.getEmail(), passwordEncoder.encode(user.getPassword()), user.getFirstName(), user.getLastName()));
            HouseEntity houseEntity = new HouseEntity();
            houseEntity.setId(userEntity.getId());
            houseRepository.save(houseEntity);
            return new ResponseEntity <>(userEntity, HttpStatus.CREATED);
        }
    }

    @PostMapping("/getUserByToken")
    public ResponseEntity<UserEntity> getUserByToken(@RequestParam String token) {
        return ResponseEntity.ok(userRepository.findByToken(token));
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
        user.setToken(token);
        userRepository.save(user);
        // On pourrait enregistrer le token dans une base de données ou dans un cache
        return ResponseEntity.ok(token);
    } else {
        return new ResponseEntity<>(HttpStatus.NO_CONTENT);
    }
}
}
