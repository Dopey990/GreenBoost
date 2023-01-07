package com.greenboost_team.backend.dto;

import lombok.Getter;
import lombok.NoArgsConstructor;
import lombok.Setter;
import org.springframework.data.annotation.Id;
import org.springframework.data.mongodb.core.mapping.Document;

import java.util.List;
import java.util.Map;

@Getter
@Setter
@NoArgsConstructor
@Document
public class HouseDto {
    private Integer nbLivingPerson;
    private Integer area;
    private Map<ProductDto, Integer> products;
}
