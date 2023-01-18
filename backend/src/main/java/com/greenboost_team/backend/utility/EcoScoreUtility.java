package com.greenboost_team.backend.utility;

import com.greenboost_team.backend.entity.product.AbstractProductEntity;

import java.util.Map;

public class EcoScoreUtility {

    public static Integer calculateEcoScore(Map<AbstractProductEntity, Integer> products, Integer houseArea, Integer houseNbLivingPerson) {
        double productsScore = 0;
        double coefficient = 10.0;

        int consoA = Character.getNumericValue(String.valueOf('A').charAt(0)) - 1;

        for (Map.Entry<AbstractProductEntity, Integer> keyValue : products.entrySet()) {
            System.out.println("=====================");

            double surface = houseArea * houseNbLivingPerson;
            double conso = Character.getNumericValue(keyValue.getKey().getEnergyClass().charAt(0)) - consoA;
            double calc = (coefficient / conso / surface);
            System.out.println("conso : " + conso);
            System.out.println("coefficient : " + coefficient);
            System.out.println("surface : " + surface);
            System.out.println("calc : " + calc);
            System.out.println(calc * keyValue.getValue());
//            productsScore += surface / (conso / 10.0) * keyValue.getValue();
            productsScore += calc;
            System.out.println(productsScore);
            System.out.println("=====================");
        }
        System.out.println((1 - (productsScore * coefficient)) * 100);
        System.out.println("eeeeeeeeeeeeeeeeeeeeeeeee");
//        return (int) (1 - (productsScore * 100));
        return (int) ((1 - (productsScore * coefficient)) * 100);
    }

    public static Integer calculatePointsForActivity(AbstractProductEntity product, Integer duree){
        int signe = ((float)duree / 60) > 1.5 ? -1 : 1;
        return Math.round(Character.getNumericValue(product.getEnergyClass().charAt(0)) * (float)(duree / 60) / 10 * signe);
    }
}
