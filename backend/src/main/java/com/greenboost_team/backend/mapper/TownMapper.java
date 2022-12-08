package com.greenboost_team.backend.mapper;

public class TownMapper {

    public TownDto entityToDto(TownEntity entity) {
        TownDto result = new TownDto();

        result.setName(entity.getName());

        return result;
    }
}
