package com.greenboost_team.backend.controller;

import com.greenboost_team.backend.dto.HouseDto;
import com.greenboost_team.backend.dto.UserDto;
import com.greenboost_team.backend.entity.HouseEntity;
import com.greenboost_team.backend.entity.UserEntity;
import com.greenboost_team.backend.entity.product.AbstractProductEntity;
import com.greenboost_team.backend.mapper.UserMapper;
import com.greenboost_team.backend.repository.HouseRepository;
import com.greenboost_team.backend.repository.ProductRepository;
import com.greenboost_team.backend.repository.UserRepository;
import com.greenboost_team.backend.utility.EcoScoreUtility;
import org.springframework.http.HttpStatus;
import org.springframework.http.MediaType;
import org.springframework.http.ResponseEntity;
import org.springframework.security.crypto.password.PasswordEncoder;
import org.springframework.web.bind.annotation.*;

import java.util.*;
import java.util.stream.Collectors;

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

    @Resource
    private ProductRepository productRepository;

    @Resource
    private UserMapper userMapper;


    @GetMapping("/getUser")
    public ResponseEntity<UserEntity> getUserByEmailAndPassword(@RequestParam String email, @RequestParam String password) {
        UserEntity result = userRepository.findByEmail(email);
        if (result == null) {
            return new ResponseEntity<>(HttpStatus.BAD_REQUEST);
        } else if (passwordEncoder.matches(password, result.getPassword())) {
            return ResponseEntity.ok(result);
        } else {
            return new ResponseEntity<>(HttpStatus.NO_CONTENT);
        }
    }

    @PostMapping("/createUser")
    public ResponseEntity<UserEntity> createUserByEmailAndPassword(@RequestBody UserDto user) {
        if (userRepository.existsByEmail(user.getEmail())) {
            return new ResponseEntity<>(HttpStatus.ALREADY_REPORTED);
        } else {
            UserEntity userEntity = userRepository.save(new UserEntity(user.getEmail(), passwordEncoder.encode(user.getPassword()), user.getFirstName(), user.getLastName()));
            HouseEntity houseEntity = new HouseEntity();
            houseEntity.setId(userEntity.getId());
            houseRepository.save(houseEntity);
            return new ResponseEntity<>(userEntity, HttpStatus.CREATED);
        }
    }

    @PostMapping("/getUserByToken")
    public ResponseEntity<UserEntity> getUserByToken(@RequestParam String token) {
        return ResponseEntity.ok(userRepository.findByToken(token));
    }

    @PostMapping("/createUserClearPassword")
    public ResponseEntity<UserEntity> createUserByEmailAndClearPassword(@RequestBody UserDto user) {
        if (userRepository.existsByEmail(user.getEmail())) {
            return new ResponseEntity<>(HttpStatus.ALREADY_REPORTED);
        } else {
            return new ResponseEntity<>(userRepository.save(new UserEntity(user.getEmail(), user.getPassword(), user.getFirstName(), user.getLastName())), HttpStatus.CREATED);
        }
    }

    @GetMapping("/refreshEcoScore")
    public ResponseEntity<Integer> refreshEcoScore(@RequestParam String userId) {
        Optional<UserEntity> userEntity = userRepository.findById(userId);

        if (userEntity.isPresent()) {
            Optional<HouseEntity> houseEntity = houseRepository.findById(userEntity.get().getId());

            if (houseEntity.isPresent()) {
                Map<AbstractProductEntity, Integer> products = new HashMap<>();
                for (Map.Entry<String, Integer> keyValue : houseEntity.get().getProducts().entrySet()) {
                    products.put(productRepository.findById(keyValue.getKey()).get(), keyValue.getValue());
                }

                userEntity.get().setEcoScore(EcoScoreUtility.calculateEcoScore(products, houseEntity.get().getArea(), houseEntity.get().getNbLivingPerson()) + userEntity.get().getPointsFromQuestions());
            }

            return ResponseEntity.ok(userRepository.save(userEntity.get()).getEcoScore());
        }

        return new ResponseEntity<>(HttpStatus.NO_CONTENT);
    }

    @GetMapping(value="/getTop10", produces = { MediaType.APPLICATION_JSON_UTF8_VALUE })
    public ResponseEntity<List<UserDto>> getTop10() {
        return ResponseEntity.ok(userRepository.findTop10ByRankNot(-1).stream().map(userEntity -> userMapper.entityToDto(userEntity)).collect(Collectors.toList()));
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

    @PostMapping("/update")
    public ResponseEntity<UserEntity> update(@RequestParam String id, @RequestParam(required = false) String firstName, @RequestParam(required = false) String lastName, @RequestParam(required = false) String email) {
        Optional<UserEntity> entity = userRepository.findById(id);
        if (entity.isPresent()){
            entity.get().setFirstName(firstName.isEmpty() ? entity.get().getFirstName() : firstName);
            entity.get().setLastName(lastName.isEmpty() ? entity.get().getLastName() : lastName);
            entity.get().setEmail(email.isEmpty() ? entity.get().getEmail() : email);
            userRepository.save(entity.get());
            return ResponseEntity.ok(entity.get());
        } else {
            return new ResponseEntity<>(HttpStatus.NOT_FOUND);
        }
    }
}
