package com.greenboost_team.backend.repository.products;

import com.greenboost_team.backend.entity.products.DishwasherEntity;

import java.util.List;

public interface DishwasherRepository {
    List<DishwasherEntity> getAllDishwashers();
}
