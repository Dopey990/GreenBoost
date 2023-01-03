package com.greenboost_team.backend.repository;

import com.greenboost_team.backend.entity.HouseEntity;
import org.springframework.data.mongodb.repository.MongoRepository;
import org.springframework.stereotype.Repository;

import java.util.Optional;

@Repository
public interface HouseRepository extends MongoRepository<HouseEntity, String> {
    Optional<HouseEntity> findById(String id);

    HouseEntity save(HouseEntity entity);
}
