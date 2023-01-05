package com.greenboost_team.backend.controller;

import com.greenboost_team.backend.entity.AdviceEntity;
import com.greenboost_team.backend.mapper.AdviceMapper;
import com.greenboost_team.backend.repository.AdviceRepository;
import org.springframework.http.HttpStatus;
import org.springframework.http.ResponseEntity;
import org.springframework.web.bind.annotation.GetMapping;
import org.springframework.web.bind.annotation.RequestMapping;
import org.springframework.web.bind.annotation.RequestParam;
import org.springframework.web.bind.annotation.RestController;
import org.springframework.http.MediaType;

import javax.annotation.Resource;
import java.util.List;
import java.util.Locale;
import java.util.stream.Collectors;

@RestController
@RequestMapping("/advices")
public class AdviceController {

    @Resource
    private AdviceMapper adviceMapper;

    @Resource
    private AdviceRepository adviceRepository;

    @GetMapping(value = "/getByCategory", produces = { MediaType.APPLICATION_JSON_UTF8_VALUE })
    public ResponseEntity<List<String>> getByCategory(@RequestParam String category, @RequestParam String language) {
        List<AdviceEntity> entities = adviceRepository.findByCategory(category);

        if (!entities.isEmpty()) {
            return ResponseEntity.ok(entities.stream().map(entity -> adviceMapper.entityToDto(entity)).map(dto -> {
                switch (language.toUpperCase(Locale.ROOT)) {
                    case "FR":
                        return dto.getFr();

                    default:
                        return dto.getFr();
                }
            }).collect(Collectors.toList()));
        }
        else {
            return new ResponseEntity<>(HttpStatus.NO_CONTENT);
        }
    }

}
