package com.greenboost_team.backend.entity.product;

import com.fasterxml.jackson.annotation.JsonIgnoreProperties;
import com.fasterxml.jackson.annotation.JsonProperty;
import lombok.*;

@Getter
@Setter
@Data
@NoArgsConstructor
@AllArgsConstructor
@JsonIgnoreProperties(ignoreUnknown = true)
public abstract class AbstractProductEntity {
    private String implementingAct;
    private String modelIdentifier;
    private Integer firstPublicationDateTS;
    private String orgVerificationStatus;
    private String calculatedEnergyClass;
    private Integer powerNetworkStandby;
    private Integer onMarketStartDateTS;
    private Integer versionId;
    private Double energyEfficiencyIndex;
    private String status;
    private String noiseClass;
    private Double powerStandbyMode;
    private Double powerOffMode;
    private Integer noise;
    private Double waterCons;
    private String energyClass;
    private String productGroup;
    private Double powerDelayStart;
    private Integer onMarketFirstStartDateTS;
}
