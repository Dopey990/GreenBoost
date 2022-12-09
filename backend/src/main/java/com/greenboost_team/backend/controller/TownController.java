package com.greenboost_team.backend.controller;

import com.greenboost_team.backend.dto.TownDto;
import com.greenboost_team.backend.entity.TownEntity;
import com.greenboost_team.backend.mapper.TownMapper;
import org.springframework.http.HttpStatus;
import org.springframework.http.ResponseEntity;
import org.springframework.web.bind.annotation.GetMapping;
import org.springframework.web.bind.annotation.RequestMapping;
import org.springframework.web.bind.annotation.RequestParam;
import org.springframework.web.bind.annotation.RestController;
import org.springframework.web.client.RestTemplate;

import javax.annotation.Resource;
import java.util.Arrays;
import java.util.List;

@RestController
@RequestMapping("/town")
public class TownController {

    @Resource
    private RestTemplate restTemplate;

    @Resource
    private TownMapper townMapper;

    public TownController() {
    }

    @GetMapping("/getTowns")
    public ResponseEntity<List<TownDto>> getTownsSearch(@RequestParam(required = false) String search) {
        String url = "https://geo.api.gouv.fr/communes" + (search == null || search.isBlank() ? "" : "?nom=" + search);
        try{
            TownEntity[] towns = restTemplate.getForObject(url, TownEntity[].class);
            if (towns == null || towns.length == 0){
                return new ResponseEntity<>(HttpStatus.NO_CONTENT);
            }else {
                return  new ResponseEntity<>(Arrays.stream(towns).map(town -> townMapper.entityToDto(town)).toList(), HttpStatus.OK);
            }
        } catch (Exception e){
            return new ResponseEntity<>(HttpStatus.BAD_REQUEST);
        }
    }
}
