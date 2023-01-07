package com.greenboost_team.backend.controller;

import com.greenboost_team.backend.dto.HouseDto;
import com.greenboost_team.backend.dto.PriceDto;
import com.greenboost_team.backend.entity.HouseEntity;
import com.greenboost_team.backend.entity.product.AbstractProductEntity;
import com.greenboost_team.backend.mapper.HouseMapper;
import com.greenboost_team.backend.repository.HouseRepository;
import com.greenboost_team.backend.repository.ProductRepository;
import org.springframework.http.HttpStatus;
import org.springframework.http.ResponseEntity;
import org.springframework.web.bind.annotation.*;
import org.springframework.web.client.RestTemplate;

import javax.annotation.Resource;
import java.text.ParseException;
import java.time.LocalDateTime;
import java.time.format.DateTimeFormatter;
import java.util.List;
import java.util.Optional;
import java.util.regex.Pattern;

@RestController
@RequestMapping("/houses")
public class HouseController {

    @Resource
    private HouseMapper houseMapper;

    @Resource
    private HouseRepository houseRepository;

    @Resource
    private ProductRepository productRepository;

    @GetMapping("/getById")
    public ResponseEntity<HouseDto> getById(@RequestParam String id) {
        Optional<HouseEntity> entity = houseRepository.findById(id);

        if (entity.isPresent()) {
            return ResponseEntity.ok(houseMapper.entityToDto(entity.get()));
        }
        else {
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
            house.get().getProducts().put(product.get(), house.get().getProducts().get(product.get()) == null ? quantity : house.get().getProducts().get(product.get()) + quantity);

            return ResponseEntity.ok(houseRepository.save(house.get()));
        }
        else {
            return new ResponseEntity<>(HttpStatus.NO_CONTENT);
        }
    }
}
