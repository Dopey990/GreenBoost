package com.greenboost_team.backend.mapper;


import com.greenboost_team.backend.dto.TownDto;
import com.greenboost_team.backend.entity.TownEntity;
import org.springframework.stereotype.Component;

@Component
public class TownMapper {
    public TownDto entityToDto(TownEntity entity) {
        TownDto result = new TownDto();
        result.setNom(entity.getNom());
        return result;
    }
}
