package com.greenboost_team.backend.controller;

import com.greenboost_team.backend.dto.PriceDto;
import com.greenboost_team.backend.mapper.PriceMapper;
import org.springframework.http.HttpStatus;
import org.springframework.http.ResponseEntity;
import org.springframework.web.bind.annotation.GetMapping;
import org.springframework.web.bind.annotation.RequestMapping;
import org.springframework.web.bind.annotation.RestController;
import org.springframework.web.client.RestTemplate;

import javax.annotation.Resource;
import java.text.ParseException;
import java.time.LocalDateTime;
import java.time.format.DateTimeFormatter;
import java.util.List;
import java.util.regex.Pattern;

@RestController
@RequestMapping("/prices")
public class PriceController {

    @Resource
    private RestTemplate restTemplate;

    @Resource
    private PriceMapper priceMapper;

    @GetMapping("/electricity/getDayAheadPrices")
    public ResponseEntity<List<PriceDto>> getDayAheadPrices() throws ParseException {
        DateTimeFormatter dateFormatter = DateTimeFormatter.ofPattern("yyyy-MM-dd'T'23:00'Z'");
        LocalDateTime today = java.time.LocalDateTime.now();
        String timeInterval = "&TimeInterval=" + today.minusDays(1).format(dateFormatter) + "/" + today.format(dateFormatter);
        String url = "https://web-api.tp.entsoe.eu/api?documentType=A44&processType=A01&securityToken=73535241-f186-4e66-b6ee-62f3eb59f9a1&In_Domain=10YFR-RTE------C&Out_Domain=10YFR-RTE------C" + timeInterval;
        String prices = restTemplate.getForObject(url, String.class);
        if (prices == null || prices.contains("<code>999</code>")) {
            return new ResponseEntity<>(HttpStatus.NO_CONTENT);
        } else {
            List<PriceDto> result = Pattern.compile("amount>(.*)<")
                    .matcher(prices)
                    .results()
                    .map(m -> priceMapper.entityToDto(m.group(1)))
                    .toList();
            return new ResponseEntity<>(result, HttpStatus.OK);
        }
    }
}
