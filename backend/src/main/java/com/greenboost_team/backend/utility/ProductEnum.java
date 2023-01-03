package com.greenboost_team.backend.utility;

public enum ProductEnum {

        LAVE_VAISSELLE("dishwashers2019"),
        LAVE_LINGE("washingmachines2019"),
        DISPOSITIF_AFFICHAGE("electronicdisplays");

        public final String label;

        private ProductEnum(String label) {
            this.label = label;
        }
}
