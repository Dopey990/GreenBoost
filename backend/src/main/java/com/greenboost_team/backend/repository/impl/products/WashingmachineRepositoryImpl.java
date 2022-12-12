package com.greenboost_team.backend.repository.impl.products;

import com.greenboost_team.backend.entity.products.WashingmachineEntity;
import com.greenboost_team.backend.repository.products.WashingmachineRepository;
import org.springframework.stereotype.Repository;

import java.util.List;

@Repository
public class WashingmachineRepositoryImpl implements WashingmachineRepository {
    @Override
    public List<WashingmachineEntity> getAllWashingmachines() {
        return null;
    }
}
