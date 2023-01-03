package com.greenboost_team.backend.mapper;

import com.greenboost_team.backend.dto.HouseDto;
import com.greenboost_team.backend.entity.HouseEntity;
import org.springframework.stereotype.Component;

@Component
public class HouseMapper {
    public HouseDto entityToDto(HouseEntity entity) {
        HouseDto result = new HouseDto();

        result.setNbLivingPerson(entity.getNbLivingPerson());
        result.setArea(entity.getArea());

        return result;
    }

    public HouseEntity dtoToEntity(HouseDto dto) {
        HouseEntity result = new HouseEntity();

        result.setNbLivingPerson(dto.getNbLivingPerson());
        result.setArea(dto.getArea());

        return result;
    }
}
