package com.greenboost_team.backend.mapper;

import com.greenboost_team.backend.dto.HouseDto;
import com.greenboost_team.backend.dto.ProductDto;
import com.greenboost_team.backend.entity.HouseEntity;
import com.greenboost_team.backend.repository.ProductRepository;
import org.springframework.stereotype.Component;

import javax.annotation.Resource;
import javax.xml.crypto.dsig.keyinfo.KeyValue;
import java.util.HashMap;
import java.util.Map;

@Component
public class HouseMapper {

    @Resource
    private ProductRepository productRepository;

    @Resource
    private ProductMapper productMapper;

    public HouseDto entityToDto(HouseEntity entity) {
        HouseDto result = new HouseDto();

        result.setNbLivingPerson(entity.getNbLivingPerson());
        result.setArea(entity.getArea());

        Map<ProductDto, Integer> products = new HashMap<>();
        for (Map.Entry<String, Integer> keyValue : entity.getProducts().entrySet()) {
            products.put(productMapper.entityToDto(productRepository.findById(keyValue.getKey()).get()), entity.getProducts().get(keyValue.getKey()));
        }
        result.setProducts(products);

        return result;
    }

    public HouseEntity dtoToEntity(HouseDto dto) {
        HouseEntity result = new HouseEntity();

        result.setNbLivingPerson(dto.getNbLivingPerson());
        result.setArea(dto.getArea());

        Map<String, Integer> products = new HashMap<>();
        for (Map.Entry<ProductDto, Integer> keyValue : dto.getProducts().entrySet()) {
            products.put(keyValue.getKey().getId(), keyValue.getValue());
        }
        result.setProducts(products);

        return result;
    }
}
