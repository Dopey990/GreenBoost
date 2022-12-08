package com.greenboost_team.backend.entity;

import lombok.Getter;
import lombok.NoArgsConstructor;
import lombok.Setter;
import org.springframework.data.annotation.Id;
import org.springframework.data.mongodb.core.mapping.Document;

@Getter
@Setter
@NoArgsConstructor
@Document
public class TownEntity {

    @Id
    private String id;
    private String nom;
    private String code;
    private String codeDepartement;
    private Integer siren;
    private Integer codeEpci;
    private String codeRegion;
    private String codePostaux;
    private Integer population;


}
