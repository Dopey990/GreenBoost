package com.greenboost_team.backend.repository.Impl.products;

import com.greenboost_team.backend.entity.TownEntity;
import com.greenboost_team.backend.entity.products.DishwasherEntity;
import com.greenboost_team.backend.repository.products.DishwasherRepository;
import org.springframework.web.client.RestTemplate;

import javax.annotation.Resource;
import java.util.Arrays;
import java.util.List;

public class DishwasherRepositoryImpl implements DishwasherRepository {

    @Resource
    private RestTemplate restTemplate;

    @Override
    public List<DishwasherEntity> getAllDishwashers() {
        String url = "https://geo.api.gouv.fr/communes";
        DishwasherEntity[] dishwashers = restTemplate.getForObject(url, DishwasherEntity[].class);
        return dishwashers == null ? null : Arrays.stream(dishwashers).toList();
    }

}
