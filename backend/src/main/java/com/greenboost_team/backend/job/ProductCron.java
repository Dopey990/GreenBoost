package com.greenboost_team.backend.job;

import com.greenboost_team.backend.entity.product.AbstractProductEntity;
import com.greenboost_team.backend.repository.external_product.DishwasherRepository;
import com.greenboost_team.backend.repository.external_product.ExternalProductRepository;
import com.greenboost_team.backend.repository.external_product.WashingmachineRepository;
import org.springframework.scheduling.annotation.Scheduled;
import org.springframework.stereotype.Component;

import javax.annotation.PostConstruct;
import javax.annotation.Resource;
import java.util.ArrayList;
import java.util.List;

@Component
public class ProductCron {

    @Resource
    private DishwasherRepository dishwasherRepository;

    @Resource
    private WashingmachineRepository washingmachineRepository;


//    the first Monday in the month at midnight
    @Scheduled(cron = "0 0 0 ? * MON#1")
//    9 o’clock of every day
//    @Scheduled(cron = "0 0 9 * * *")
    public void scheduleJob() throws InterruptedException {
        getExternalProduct(dishwasherRepository);
    }

    @PostConstruct
    public void onStartup() throws InterruptedException {
        System.out.println("OHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHH");
//        getExternalProduct(dishwasherRepository);
        getExternalProduct(washingmachineRepository);
    }

    public void getExternalProduct(ExternalProductRepository externalProductRepository) throws InterruptedException {
        List<AbstractProductEntity> abstractProduct = new ArrayList<>();
        int page = 1;
        while(abstractProduct.size() % 100 == 0){
            abstractProduct.addAll(externalProductRepository.getExternalProductByPage(page++));
        }
//        externalProductRepository.saveExternalProduct();
    }
}
