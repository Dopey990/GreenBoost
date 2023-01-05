package com.greenboost_team.backend.dto;

import lombok.Getter;
import lombok.NoArgsConstructor;
import lombok.Setter;
import org.springframework.data.mongodb.core.mapping.Document;

import java.util.List;

@Getter
@Setter
@NoArgsConstructor
@Document
public class ChallengeDto {
    private String id;
    private String value;
    private String category;
    private Boolean hasAnswers;
}
