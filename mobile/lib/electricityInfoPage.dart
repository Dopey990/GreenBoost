import 'package:flutter/material.dart';
import 'package:fl_chart/fl_chart.dart';

import 'components/menu.dart';
import 'components/pricesForecastChart.dart';

class ElectricityInfoPage extends StatefulWidget {
  const ElectricityInfoPage({super.key});

  @override
  State<StatefulWidget> createState() => ElectricityInfoState();
}

class ElectricityInfoState extends State<ElectricityInfoPage> {
  @override
  Widget build(BuildContext context) {
    return Scaffold(
        appBar: AppBar(
          centerTitle: true,
          title: Text("Électricité"),
        ),
        drawer: Menu(),
        body: Center(
            child: Column(
          children: [
            const SizedBox(height: 10),
            Image.asset('assets/home/electricite-vert.png',
                height: MediaQuery.of(context).size.width / 6,
                width: MediaQuery.of(context).size.width / 6),
            const PricesForecastWidget(
              chartTitle: "Prix électricité",
              apiUrl:
                  "http://10.8.253.233:8080/prices/electricity/getDayAheadPrices",
            )
          ],
        )));
  }
}
