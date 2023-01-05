package com.greenboost_team.backend.repository.impl.external_product;

import com.greenboost_team.backend.entity.product.AbstractProductEntity;
import com.greenboost_team.backend.entity.product.ElectronicDeviceEntityResponse;
import com.greenboost_team.backend.repository.external_product.ElectronicDeviceRepository;
import com.greenboost_team.backend.utility.ProductEnum;
import org.springframework.http.HttpEntity;
import org.springframework.http.HttpHeaders;
import org.springframework.http.HttpMethod;
import org.springframework.http.ResponseEntity;
import org.springframework.stereotype.Repository;
import org.springframework.web.client.RestTemplate;

import javax.annotation.Resource;
import java.util.ArrayList;
import java.util.List;

@Repository
public class ElectronicDeviceRepositoryImpl implements ElectronicDeviceRepository {

    @Resource
    private RestTemplate restTemplate;

    @Override
    public List<AbstractProductEntity> getExternalProductByPage(int page) {
        String url = "https://eprel.ec.europa.eu/api/products/" + ProductEnum.DISPOSITIF_AFFICHAGE.label + "?_page=" + page + "&_limit=100&sort0=onMarketStartDateTS&order0=DESC&sort1=energyClass&order1=DESC";
        HttpHeaders headers = new HttpHeaders();
        headers.set("x-api-key", "3PR31D3F4ULTU1K3Y2020");
        ResponseEntity<ElectronicDeviceEntityResponse> electronicDevices = restTemplate.exchange(
                url,
                HttpMethod.GET,
                new HttpEntity<>(headers),
                ElectronicDeviceEntityResponse.class
        );
        return electronicDevices.getBody() == null ? null : new ArrayList<>(electronicDevices.getBody().getElectronicDevices());
    }
}
