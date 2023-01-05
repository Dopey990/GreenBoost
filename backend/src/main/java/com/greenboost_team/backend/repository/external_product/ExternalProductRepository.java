package com.greenboost_team.backend.repository.external_product;

import com.greenboost_team.backend.entity.product.AbstractProductEntity;
import org.springframework.stereotype.Repository;

import java.util.List;

@Repository
public interface ExternalProductRepository {
    List<AbstractProductEntity> getExternalProductByPage(int page);
}
