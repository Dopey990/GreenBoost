package com.greenboost_team.backend.entity.products;

import lombok.Getter;
import lombok.NoArgsConstructor;
import lombok.Setter;
import org.springframework.data.mongodb.core.mapping.Document;

@Getter
@Setter
@NoArgsConstructor
@Document
public class WashingmachineEntity extends AbstractProductEntity{
    private String spinClass;
    private Double washingEfficiencyIndexV2;
    private Integer programmeDurationHalf;
    private Integer programmeDurationQuarter;
    private Double energyConsPerCycle;
    private Integer energyConsPer100Cycle;
    private Integer rinsingEffectiveness;
}
