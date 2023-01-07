package com.greenboost_team.backend.repository;

import com.greenboost_team.backend.entity.UserEntity;
import org.springframework.data.mongodb.repository.MongoRepository;
import org.springframework.stereotype.Repository;

@Repository
public interface UserRepository extends MongoRepository<UserEntity, String> {
    UserEntity findOneByEmailAndPassword(String email, String password);

    UserEntity findByEmail(String email);

    UserEntity findByToken(String token);

    boolean existsByEmail(String email);
}
