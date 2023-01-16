package com.greenboost_team.backend.utility;

import com.greenboost_team.backend.entity.product.AbstractProductEntity;

import java.util.Map;

public class EcoScoreUtility {

    public static Integer calculateEcoScore(Map<AbstractProductEntity, Integer> products, Integer houseArea, Integer houseNbLivingPerson) {
        double productsScore = 0;

        for (Map.Entry<AbstractProductEntity, Integer> keyValue : products.entrySet()) {
            productsScore += (houseArea * houseNbLivingPerson) / ((Integer.parseInt(String.valueOf(keyValue.getKey().getEnergyClass().charAt(0))) - Integer.parseInt(String.valueOf('A'))) / 10.0) * keyValue.getValue();
        }

        return (int) (1 - (productsScore * 100));
    }

    public static Integer calculatePointsFromQuestions(AbstractProductEntity product, Integer duree){
        System.out.println("eeeeeeeeeeeeeeeeeeeeeeeeeeeee");
        System.out.println(String.valueOf(product.getEnergyClass().charAt(0)));
        System.out.println(Character.valueOf(product.getEnergyClass().charAt(0)));
        System.out.println("eeeeeeeeeeeeeeeeeeeeeeeeeeeee");
        return Integer.parseInt(String.valueOf(product.getEnergyClass().charAt(0))) * (duree / 60);
    }
}
