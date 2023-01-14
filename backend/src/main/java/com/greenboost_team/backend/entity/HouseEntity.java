package com.greenboost_team.backend.entity;

import lombok.Getter;
import lombok.NoArgsConstructor;
import lombok.Setter;
import org.springframework.data.annotation.Id;
import org.springframework.data.mongodb.core.mapping.Document;

import java.util.HashMap;
import java.util.Map;

@Getter
@Setter
@NoArgsConstructor
@Document
public class HouseEntity {
    @Id
    private String id;
    private Integer nbLivingPerson;
    private Integer area;
    private Map<String, Integer> products = new HashMap<>();
}
