package com.greenboost_team.backend.entity.products;

import lombok.Getter;
import lombok.NoArgsConstructor;
import lombok.Setter;
import org.springframework.data.mongodb.core.mapping.Document;

@Getter
@Setter
@NoArgsConstructor
@Document
public class DishwasherEntity extends AbstractProductEntity{
    private Double energyCons;
    private Integer energyCons100;
    private Double cleaningPerformanceIndexV2;
    private Double dryingPerformanceIndexV2;
    private Integer programmeDuration;
}
