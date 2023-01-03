package com.greenboost_team.backend.entity.product;

import com.fasterxml.jackson.annotation.JsonProperty;
import lombok.AllArgsConstructor;
import lombok.Getter;
import lombok.NoArgsConstructor;
import lombok.Setter;

import java.util.List;

@Getter
@Setter
@NoArgsConstructor
@AllArgsConstructor
public class WashingmachineEntityResponse extends AbstractProductEntityResponse{
    @JsonProperty(value = "hits")
    private List<WashingmachineEntity> washingMachines;
}
