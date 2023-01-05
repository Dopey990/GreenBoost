package com.greenboost_team.backend.controller;

import com.greenboost_team.backend.utility.ProductEnum;
import org.springframework.http.ResponseEntity;
import org.springframework.web.bind.annotation.GetMapping;
import org.springframework.web.bind.annotation.RequestMapping;
import org.springframework.web.bind.annotation.RestController;

import java.util.Arrays;
import java.util.List;
import java.util.Locale;

@RestController
@RequestMapping("/products")
public class ProductController {

    @GetMapping("/getCategories")
    public ResponseEntity<List<String>> getCategories() {
        return ResponseEntity.ok(Arrays.stream(ProductEnum.values()).map(productEnum -> productEnum.name().toLowerCase(Locale.ROOT)).toList());
    }
}
