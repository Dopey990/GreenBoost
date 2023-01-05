package com.greenboost_team.backend.repository;

import com.greenboost_team.backend.entity.product.AbstractProductEntity;
import org.springframework.data.mongodb.repository.MongoRepository;
import org.springframework.stereotype.Repository;

import java.util.List;

@Repository
public interface ProductRepository extends MongoRepository<AbstractProductEntity, String> {
    //Get all datas from MongoDB and return ProductDto
    List<AbstractProductEntity> findAllByProductGroup(String category);
}
