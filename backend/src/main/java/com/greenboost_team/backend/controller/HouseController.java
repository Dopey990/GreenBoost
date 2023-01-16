package com.greenboost_team.backend.controller;

import com.greenboost_team.backend.dto.HouseDto;
import com.greenboost_team.backend.dto.PriceDto;
import com.greenboost_team.backend.dto.ProductDto;
import com.greenboost_team.backend.entity.HouseEntity;
import com.greenboost_team.backend.entity.UserEntity;
import com.greenboost_team.backend.entity.product.AbstractProductEntity;
import com.greenboost_team.backend.mapper.HouseMapper;
import com.greenboost_team.backend.mapper.ProductMapper;
import com.greenboost_team.backend.repository.HouseRepository;
import com.greenboost_team.backend.repository.ProductRepository;
import com.greenboost_team.backend.repository.UserRepository;
import com.greenboost_team.backend.utility.EcoScoreUtility;
import com.greenboost_team.backend.utility.ProductEnum;
import org.springframework.http.HttpStatus;
import org.springframework.http.ResponseEntity;
import org.springframework.web.bind.annotation.*;
import org.springframework.web.client.RestTemplate;

import javax.annotation.Resource;
import java.text.ParseException;
import java.time.LocalDateTime;
import java.time.format.DateTimeFormatter;
import java.util.*;
import java.util.regex.Pattern;

@RestController
@RequestMapping("/houses")
public class HouseController {

    @Resource
    private HouseMapper houseMapper;

    @Resource
    private ProductMapper productMapper;

    @Resource
    private HouseRepository houseRepository;

    @Resource
    private ProductRepository productRepository;

    @Resource
    private UserRepository userRepository;

    @GetMapping("/getById")
    public ResponseEntity<HouseDto> getById(@RequestParam String id) {
        Optional<HouseEntity> entity = houseRepository.findById(id);

        if (entity.isPresent()) {
            return ResponseEntity.ok(houseMapper.entityToDto(entity.get()));
        } else {
            return new ResponseEntity<>(HttpStatus.NO_CONTENT);
        }
    }

    @PostMapping("/update")
    public ResponseEntity<HouseDto> update(@RequestParam String id, @RequestBody HouseDto dto) {
        HouseEntity entity = houseMapper.dtoToEntity(dto);
        entity.setId(id);

        houseRepository.save(entity);
        return ResponseEntity.ok(dto);
    }

    @PostMapping("/addProduct")
    public ResponseEntity<HouseEntity> addProduct(@RequestParam String userId,
                                                  @RequestParam String productId,
                                                  @RequestParam Integer quantity) {
        Optional<AbstractProductEntity> product = productRepository.findById(productId);
        Optional<HouseEntity> house = houseRepository.findById(userId);

        if (product.isPresent() && house.isPresent()) {
            house.get().getProducts().put(product.get().getId(), house.get().getProducts().get(product.get().getId()) == null ? quantity : house.get().getProducts().get(product.get().getId()) + quantity);
            HouseEntity result = houseRepository.save(house.get());


            Map<AbstractProductEntity, Integer> products = new HashMap<>();
            for (Map.Entry<String, Integer> keyValue : house.get().getProducts().entrySet()) {
                products.put(productRepository.findById(keyValue.getKey()).get(), keyValue.getValue());
            }

            UserEntity userEntity = userRepository.findById(userId).get();
            userEntity.setEcoScore(EcoScoreUtility.calculateEcoScore(products, house.get().getArea(), house.get().getNbLivingPerson()) + userEntity.getPointsFromQuestions());
            userRepository.save(userEntity);

            return ResponseEntity.ok(result);
        } else {
            return new ResponseEntity<>(HttpStatus.NO_CONTENT);
        }
    }

    @GetMapping("/listProducts")
    public ResponseEntity<List<ProductDto>> listProducts(@RequestParam String userId, @RequestParam(required = false) String category) {
        Optional<HouseEntity> house = houseRepository.findById(userId);
        if (house.isPresent()) {
            List<ProductDto> products = new ArrayList<>();
            for (Map.Entry<String, Integer> keyValue : house.get().getProducts().entrySet()) {
                AbstractProductEntity productEntity = productRepository.findById(keyValue.getKey()).get();
                if (category == null || productEntity.getProductGroup().equals(ProductEnum.valueOf(category.toUpperCase()).label)) {
                    products.add(productMapper.entityToDto(productEntity));
                }
            }
            return ResponseEntity.ok(products);
        } else {
            return new ResponseEntity<>(HttpStatus.NO_CONTENT);
        }
    }

    @GetMapping("/setPointsForActivity")
    public ResponseEntity<Integer> setPointsForActivity(@RequestParam String userId, @RequestParam String productId, @RequestParam Integer duree) {
        Optional<AbstractProductEntity> product = productRepository.findById(productId);
        Optional<UserEntity> userEntity = userRepository.findById(userId);
        if (product.isPresent() && userEntity.isPresent()) {
            Integer score = EcoScoreUtility.calculatePointsForActivity(product.get(), duree);
            userEntity.get().setPointsFromQuestions(userEntity.get().getPointsFromQuestions() + score);
            return ResponseEntity.ok(score);
        } else {
            return new ResponseEntity<>(HttpStatus.NO_CONTENT);
        }
    }
}
