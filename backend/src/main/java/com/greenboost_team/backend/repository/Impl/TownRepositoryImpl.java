package com.greenboost_team.backend.repository.Impl;

import com.greenboost_team.backend.entity.TownEntity;
import com.greenboost_team.backend.repository.TownRepository;
import org.springframework.web.client.RestTemplate;

import javax.annotation.Resource;
import java.util.Arrays;
import java.util.List;

public class TownRepositoryImpl implements TownRepository {

    @Resource
    private RestTemplate restTemplate;

    @Override
    public List<TownEntity> getAllTowns() {
        String url = "https://geo.api.gouv.fr/communes";
        TownEntity[] towns = restTemplate.getForObject(url, TownEntity[].class);
        return towns == null ? null : Arrays.stream(towns).toList();
    }

    @Override
    public List<TownEntity> getAllTownsWithName(String townName) {
        String url = "https://geo.api.gouv.fr/communes?nom=" + townName;
        TownEntity[] towns = restTemplate.getForObject(url, TownEntity[].class);
        return towns == null ? null : Arrays.stream(towns).toList();
    }
}
