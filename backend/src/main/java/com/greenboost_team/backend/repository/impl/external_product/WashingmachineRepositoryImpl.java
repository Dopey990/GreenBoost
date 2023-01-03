package com.greenboost_team.backend.repository.impl.external_product;

import com.greenboost_team.backend.entity.product.AbstractProductEntity;
import com.greenboost_team.backend.entity.product.WashingmachineEntity;
import com.greenboost_team.backend.entity.product.WashingmachineEntityResponse;
import com.greenboost_team.backend.repository.external_product.WashingmachineRepository;
import com.greenboost_team.backend.utility.ProductEnum;
import org.springframework.http.*;
import org.springframework.stereotype.Repository;
import org.springframework.web.client.RestTemplate;

import javax.annotation.Resource;
import java.util.*;

@Repository
public class WashingmachineRepositoryImpl implements WashingmachineRepository {

    @Resource
    private RestTemplate restTemplate;

    @Override
    public List<AbstractProductEntity> getExternalProductByPage(int page) {
        String url = "https://eprel.ec.europa.eu/api/products/" + ProductEnum.LAVE_LINGE.label + "?_page=" + page + "&_limit=100&sort0=onMarketStartDateTS&order0=DESC&sort1=energyClass&order1=DESC";
        HttpHeaders headers = new HttpHeaders();
        headers.set("x-api-key", "3PR31D3F4ULTU1K3Y2020");
        ResponseEntity<WashingmachineEntityResponse> washingMachines = restTemplate.exchange(
                url,
                HttpMethod.GET,
                new HttpEntity<>(headers),
                WashingmachineEntityResponse.class
        );
        return washingMachines.getBody() == null ? null : new ArrayList<>(washingMachines.getBody().getWashingMachines());
    }

    @Override
    public void saveExternalProduct(List<AbstractProductEntity> abstractProducts) {

    }
}
