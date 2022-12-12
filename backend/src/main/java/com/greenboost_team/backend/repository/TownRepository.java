package com.greenboost_team.backend.repository;

import com.greenboost_team.backend.entity.TownEntity;
import org.springframework.stereotype.Repository;

import java.util.List;

@Repository
public interface TownRepository {
    List<TownEntity> getAllTowns();
    List<TownEntity> getAllTownsWithName(String townName);
}
