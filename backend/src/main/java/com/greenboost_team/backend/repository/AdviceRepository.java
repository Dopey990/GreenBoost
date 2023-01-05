package com.greenboost_team.backend.repository;

import com.greenboost_team.backend.entity.AdviceEntity;
import org.springframework.data.mongodb.repository.MongoRepository;
import org.springframework.stereotype.Repository;

import java.util.List;

@Repository
public interface AdviceRepository extends MongoRepository<AdviceEntity, String> {
    List<AdviceEntity> findByCategory(String category);
}
