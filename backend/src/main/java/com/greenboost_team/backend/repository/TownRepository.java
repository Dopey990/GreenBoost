package com.greenboost_team.backend.repository;

import com.greenboost_team.backend.entity.TownEntity;
import com.greenboost_team.backend.entity.UserEntity;
import org.springframework.data.mongodb.repository.MongoRepository;
import org.springframework.stereotype.Repository;

@Repository
public interface TownRepository extends MongoRepository<TownEntity, String> {

}
