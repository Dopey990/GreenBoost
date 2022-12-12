package com.greenboost_team.backend.mapper;

import com.greenboost_team.backend.dto.PriceDto;
import com.greenboost_team.backend.entity.PriceEntity;
import org.springframework.stereotype.Component;

@Component
public class PriceMapper {
    public PriceDto entityToDto(String entity) {
        return new PriceDto(entity);
    }
}
