package com.greenboost_team.backend.entity;

import lombok.Getter;
import lombok.NoArgsConstructor;
import lombok.Setter;
import org.springframework.data.annotation.Id;
import org.springframework.data.mongodb.core.mapping.Document;

import java.util.HashSet;
import java.util.Set;

@Getter
@Setter
@NoArgsConstructor
@Document
public class UserEntity {

    @Id
    private String id;
    private String token;
    private String firstName;
    private String lastName;
    private String email;
    private String password;
    private String town = "none";
    private Integer ecoScore = 0;
    private Integer pointsFromQuestions = 0;
    private Integer rank = -1;
    private Integer electricityScore = 0;
    private Integer gazScore = 0;
    private Integer pollutionScore = 0;
    private Integer waterScore = 0;
    private Integer electricityRank = -1;
    private Integer gazRank = -1;
    private Integer pollutionRank = -1;
    private Integer waterRank = -1;
    private Set<String> doneChallenges = new HashSet<>();
    private String language = "fr";

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
