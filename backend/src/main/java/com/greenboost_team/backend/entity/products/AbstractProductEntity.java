package com.greenboost_team.backend.entity.products;

import lombok.Getter;
import lombok.NoArgsConstructor;
import lombok.Setter;
import org.springframework.data.mongodb.core.mapping.Document;

@Getter
@Setter
@NoArgsConstructor
@Document
public abstract class AbstractProductEntity {
    private String implementingAct;
    private String modelIdentifier;
    private String organisationName;
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
