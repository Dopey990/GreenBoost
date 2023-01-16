package com.greenboost_team.backend.repository;

import com.greenboost_team.backend.entity.ChallengeEntity;
import org.springframework.data.mongodb.repository.MongoRepository;
import org.springframework.stereotype.Repository;

import java.util.List;
import java.util.Optional;

@Repository
public interface ChallengeRepository extends MongoRepository<ChallengeEntity, String> {

    Optional<ChallengeEntity> findById(String id);
    List<ChallengeEntity> findByCategory(String category);
    List<ChallengeEntity> findAll();
}
