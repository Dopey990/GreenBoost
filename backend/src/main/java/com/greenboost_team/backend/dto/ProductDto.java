package com.greenboost_team.backend.dto;

import lombok.AllArgsConstructor;
import lombok.Getter;
import lombok.NoArgsConstructor;
import lombok.Setter;
import org.springframework.data.mongodb.core.mapping.Document;

@Getter
@Setter
@NoArgsConstructor
@AllArgsConstructor
@Document
public class ProductDto {
    private String id;
    private String orgVerificationStatus;
    private Integer onMarketStartDateTS;
    private String energyClass;
    private Integer onMarketFirstStartDateTS;
    private String supplierOrTrademark;
}
