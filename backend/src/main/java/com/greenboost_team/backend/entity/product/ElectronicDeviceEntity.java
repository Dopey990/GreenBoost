package com.greenboost_team.backend.entity.product;

import lombok.Getter;
import lombok.NoArgsConstructor;
import lombok.Setter;
import org.springframework.data.mongodb.core.mapping.Document;

@Getter
@Setter
@NoArgsConstructor
@Document
public class ElectronicDeviceEntity extends AbstractProductEntity{
    private Double powerStandby;
    private String energyClassSDR;
    private Integer resolutionVerticalPixels;
    private Integer frequencyRate;
    private Integer sizeRatioX;
    private Integer sizeRatioY;
    private Integer diagonalCm;
    private Integer diagonalInch;
    private Integer powerOnModeSDR;
    private String panelTechnology;
    private String energyClassHDR;
    private String powerSupplyType;
    private Double powerStandbyNetworked;
    private Integer energyLabelId;
    private Integer resolutionHorizontalPixels;
    private String powerOnModeHDR;
    private String category;
}
