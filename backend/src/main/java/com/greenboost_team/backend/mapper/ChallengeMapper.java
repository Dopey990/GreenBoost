package com.greenboost_team.backend.mapper;

import com.greenboost_team.backend.dto.ChallengeDto;
import com.greenboost_team.backend.entity.ChallengeEntity;
import org.springframework.stereotype.Component;

import java.util.Locale;

@Component
public class ChallengeMapper {
    public ChallengeDto entityToDto(ChallengeEntity entity, String language) {
        ChallengeDto result = new ChallengeDto();

        result.setId(entity.getId());
        switch (language.toUpperCase(Locale.ROOT)) {
            case "FR":
                result.setValue(entity.getFr());
                break;

            default:
                result.setValue("");
        }
        result.setCategory(entity.getCategory());
        result.setHasAnswers(entity.getAnswers().size() > 0);

        return result;
    }
}
