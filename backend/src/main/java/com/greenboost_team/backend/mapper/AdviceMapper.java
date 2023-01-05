package com.greenboost_team.backend.mapper;

import com.greenboost_team.backend.dto.AdviceDto;
import com.greenboost_team.backend.entity.AdviceEntity;
import org.springframework.stereotype.Component;

@Component
public class AdviceMapper {
    public AdviceDto entityToDto(AdviceEntity entity) {
        AdviceDto result = new AdviceDto();

        result.setFr(entity.getFr());

        return result;
    }

    public AdviceEntity dtoToEntity(AdviceDto dto) {
        AdviceEntity result = new AdviceEntity();

        result.setFr(dto.getFr());

        return result;
    }
}
