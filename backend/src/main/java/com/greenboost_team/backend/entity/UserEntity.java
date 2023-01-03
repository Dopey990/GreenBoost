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
    private Integer ecoScore;
    private Integer rank;
    private Integer electricityScore;
    private Integer gazScore;
    private Integer pollutionScore;
    private Integer waterScore;
    private Integer electricityRank;
    private Integer gazRank;
    private Integer pollutionRank;
    private Integer waterRank;

    public UserEntity(String email, String password) {
        super();
        this.email = email;
        this.password = password;
    }
    public UserEntity(String email, String password, String firstName, String lastName) {
        super();
        this.email = email;
        this.password = password;
        this.firstName = firstName;
        this.lastName = lastName;
    }
}
