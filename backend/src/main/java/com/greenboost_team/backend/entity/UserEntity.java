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
public class UserEntity {

    @Id
    private String id;
    private String firstName;
    private String lastName;
    private String email;
    private String password;
    private String town;
    private int ecoScore;
    private int rank;
    private int electricityScore;
    private int gazScore;
    private int pollutionScore;
    private int waterScore;
    private int electricityRank;
    private int gazRank;
    private int pollutionRank;
    private int waterRank;
}
