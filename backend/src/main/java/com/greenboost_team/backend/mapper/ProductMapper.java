package com.greenboost_team.backend.mapper;

import com.greenboost_team.backend.dto.ProductDto;
import com.greenboost_team.backend.entity.product.AbstractProductEntity;
import org.springframework.stereotype.Component;

@Component
public class ProductMapper {
    public ProductDto entityToDto(AbstractProductEntity entity) {
        ProductDto result = new ProductDto();
        result.setEnergyClass(entity.getEnergyClass());
        result.setOnMarketFirstStartDateTS(entity.getOnMarketFirstStartDateTS());
        result.setId(entity.getId());
        result.setSupplierOrTrademark(entity.getSupplierOrTrademark());
        result.setOnMarketStartDateTS(entity.getOnMarketStartDateTS());
        result.setOrgVerificationStatus(entity.getOrgVerificationStatus());
        return result;
    }
};

