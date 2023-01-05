package com.greenboost_team.backend.controller;

import com.greenboost_team.backend.dto.HouseDto;
import com.greenboost_team.backend.dto.PriceDto;
import com.greenboost_team.backend.entity.HouseEntity;
import com.greenboost_team.backend.mapper.HouseMapper;
import com.greenboost_team.backend.repository.HouseRepository;
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
}
