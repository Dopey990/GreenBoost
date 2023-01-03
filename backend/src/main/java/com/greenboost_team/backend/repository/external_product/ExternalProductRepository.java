package com.greenboost_team.backend.repository.external_product;

import com.greenboost_team.backend.entity.product.AbstractProductEntity;

import java.util.List;

public interface ExternalProductRepository {
    List<AbstractProductEntity> getExternalProductByPage(int page);
    void saveExternalProduct(List<AbstractProductEntity> abstractProducts);


}
