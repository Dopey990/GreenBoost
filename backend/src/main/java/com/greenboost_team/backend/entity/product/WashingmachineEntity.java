package com.greenboost_team.backend.entity.product;

import com.fasterxml.jackson.annotation.JsonIgnoreProperties;
import com.fasterxml.jackson.annotation.JsonProperty;
import lombok.*;
import org.springframework.data.mongodb.core.mapping.Document;

@Getter
@Setter
@Builder
@NoArgsConstructor
@AllArgsConstructor
@JsonIgnoreProperties(ignoreUnknown = true)
public class WashingmachineEntity extends AbstractProductEntity{
    private String spinClass;
    private Double washingEfficiencyIndexV2;
    private Integer programmeDurationHalf;
    private Integer programmeDurationQuarter;
    private Double energyConsPerCycle;
    private Integer energyConsPer100Cycle;
    private Integer rinsingEffectiveness;
    private Integer powerNetworkStandby;
    private Double energyEfficiencyIndex;
    private String noiseClass;
    private Double powerStandbyMode;
    private Integer noise;
    private Double waterCons;
    private Double powerDelayStart;
}
