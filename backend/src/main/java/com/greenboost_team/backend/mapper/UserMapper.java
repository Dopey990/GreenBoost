package com.greenboost_team.backend.mapper;

import com.greenboost_team.backend.dto.UserDto;
import com.greenboost_team.backend.entity.UserEntity;
import org.springframework.stereotype.Component;

@Component
public class UserMapper {
    public UserDto entityToDto(UserEntity entity) {
        UserDto result = new UserDto();

        result.setFirstName(entity.getFirstName());
        result.setLastName(entity.getLastName());
        result.setEmail(entity.getEmail());
        result.setTown(entity.getTown());
        result.setEcoScore(entity.getEcoScore());
        result.setPointsFromQuestions(entity.getPointsFromQuestions());
        result.setRank(entity.getRank());
        result.setElectricityScore(entity.getElectricityScore());
        result.setGazScore(entity.getGazScore());
        result.setPollutionScore(entity.getPollutionScore());
        result.setWaterRank(entity.getWaterScore());
        result.setElectricityRank(entity.getElectricityRank());
        result.setGazRank(entity.getGazRank());
        result.setPollutionRank(entity.getPollutionRank());
        result.setWaterRank(entity.getWaterRank());

        return result;
    }

    public UserEntity dtoToEntity(UserDto dto) {
        UserEntity result = new UserEntity();

        result.setFirstName(dto.getFirstName());
        result.setLastName(dto.getLastName());
        result.setEmail(dto.getEmail());
        result.setTown(dto.getTown());
        result.setEcoScore(dto.getEcoScore());
        result.setPointsFromQuestions(dto.getPointsFromQuestions());
        result.setRank(dto.getRank());
        result.setElectricityScore(dto.getElectricityScore());
        result.setGazScore(dto.getGazScore());
        result.setPollutionScore(dto.getPollutionScore());
        result.setWaterRank(dto.getWaterScore());
        result.setElectricityRank(dto.getElectricityRank());
        result.setGazRank(dto.getGazRank());
        result.setPollutionRank(dto.getPollutionRank());
        result.setWaterRank(dto.getWaterRank());

        return result;
    }
}
