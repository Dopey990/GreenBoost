package com.greenboost_team.backend.controller;

import com.greenboost_team.backend.dto.ProductDto;
import com.greenboost_team.backend.entity.product.AbstractProductEntity;
import com.greenboost_team.backend.mapper.ProductMapper;
import com.greenboost_team.backend.repository.ProductRepository;
import com.greenboost_team.backend.utility.ProductEnum;
import org.springframework.http.HttpStatus;
import org.springframework.http.ResponseEntity;
import org.springframework.web.bind.annotation.GetMapping;
import org.springframework.web.bind.annotation.RequestMapping;
import org.springframework.web.bind.annotation.RequestParam;
import org.springframework.web.bind.annotation.RestController;

import javax.annotation.Resource;
import java.util.Arrays;
import java.util.List;
import java.util.Locale;

@RestController
@RequestMapping("/products")
public class ProductController {

    @Resource
    private ProductRepository productRepository;

    @Resource
    private ProductMapper productMapper;

    @GetMapping("/getCategories")
    public ResponseEntity<List<String>> getCategories() {
        return ResponseEntity.ok(Arrays.stream(ProductEnum.values()).map(productEnum -> productEnum.name().toLowerCase(Locale.ROOT)).toList());
    }

    @GetMapping("/getProducts")
    public ResponseEntity<List<ProductDto>> getProducts(@RequestParam(required = false) String category) {
        List<AbstractProductEntity> products = category == null ? productRepository.findAll() : productRepository.findAllByProductGroup(category);
        if (products.size() == 0) {
            return new ResponseEntity<>(HttpStatus.NO_CONTENT);
        } else {
            return new ResponseEntity<>(products.stream().map(product -> productMapper.entityToDto(product)).toList(), HttpStatus.OK);
        }
    }
}
