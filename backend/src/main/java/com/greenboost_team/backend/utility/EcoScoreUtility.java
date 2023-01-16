package com.greenboost_team.backend.utility;

import com.greenboost_team.backend.entity.product.AbstractProductEntity;

import java.util.Map;

public class EcoScoreUtility {

    public static Integer calculateEcoScore(Map<AbstractProductEntity, Integer> products, Integer houseArea, Integer houseNbLivingPerson) {
        double productsScore = 0;

        for (Map.Entry<AbstractProductEntity, Integer> keyValue : products.entrySet()) {
            productsScore += (houseArea * houseNbLivingPerson) / ((Character.getNumericValue(keyValue.getKey().getEnergyClass().charAt(0)) - Character.getNumericValue(String.valueOf('A').charAt(0))) / 10.0) * keyValue.getValue();
        }

        return (int) (1 - (productsScore * 100));
    }

    public static Integer calculatePointsForActivity(AbstractProductEntity product, Integer duree){
        int signe = ((float)duree / 60) > 1.5 ? -1 : 1;
        return Math.round(Character.getNumericValue(product.getEnergyClass().charAt(0)) * (float)(duree / 60) / 10 * signe);
    }
}
