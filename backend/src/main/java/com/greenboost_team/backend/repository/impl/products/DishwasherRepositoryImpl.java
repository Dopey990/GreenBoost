package com.greenboost_team.backend.repository.impl.products;

import com.greenboost_team.backend.entity.products.DishwasherEntity;
import com.greenboost_team.backend.repository.products.DishwasherRepository;
import org.springframework.stereotype.Repository;
import org.springframework.web.client.RestTemplate;

import javax.annotation.Resource;
import java.util.Arrays;
import java.util.List;

@Repository
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
