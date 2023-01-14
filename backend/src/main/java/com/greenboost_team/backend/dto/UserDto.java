package com.greenboost_team.backend.dto;

import lombok.AllArgsConstructor;
import lombok.Getter;
import lombok.NoArgsConstructor;
import lombok.Setter;
import org.springframework.data.mongodb.core.mapping.Document;

@Getter
@Setter
@NoArgsConstructor
@AllArgsConstructor
@Document
public class UserDto {

    private String firstName;
    private String lastName;
    private String password;
    private String email;
    private String town;
    private Integer ecoScore;
    private Integer pointsFromQuestions;
    private Integer rank;
    private Integer electricityScore;
    private Integer gazScore;
    private Integer pollutionScore;
    private Integer waterScore;
    private Integer electricityRank;
    private Integer gazRank;
    private Integer pollutionRank;
    private Integer waterRank;
   
}
