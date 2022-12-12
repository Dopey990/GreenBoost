package com.greenboost_team.backend.controller;

import com.greenboost_team.backend.dto.TownDto;
import com.greenboost_team.backend.entity.TownEntity;
import com.greenboost_team.backend.mapper.TownMapper;
import com.greenboost_team.backend.repository.TownRepository;
import org.springframework.http.HttpStatus;
import org.springframework.http.ResponseEntity;
import org.springframework.web.bind.annotation.GetMapping;
import org.springframework.web.bind.annotation.RequestMapping;
import org.springframework.web.bind.annotation.RequestParam;
import org.springframework.web.bind.annotation.RestController;

import javax.annotation.Resource;
import java.util.List;

@RestController
@RequestMapping("/town")
public class TownController {

    @Resource
    private TownMapper townMapper;

    @Resource
    private TownRepository townRepository;

    @GetMapping("/getTowns")
    public ResponseEntity<List<TownDto>> getTownsSearch(@RequestParam(required = false) String search) {
        List<TownEntity> towns = search == null ? townRepository.getAllTowns() : townRepository.getAllTownsWithName(search);
        if (towns == null || towns.size() == 0) {
            return new ResponseEntity<>(HttpStatus.NO_CONTENT);
        } else {
            return new ResponseEntity<>(towns.stream().map(town -> townMapper.entityToDto(town)).toList(), HttpStatus.OK);
        }
    }
}
