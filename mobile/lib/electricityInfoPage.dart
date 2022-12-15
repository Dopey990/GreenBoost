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
            PricesForecastWidget(
              chartTitle: "Prix électricité",
              values: const [
                100.25,
                125.22,
                200.22,
                222.11,
                150.00,
                250.11,
                100.25,
                125.22,
                200.22,
                222.11,
                150.00,
                250.11,
                100.25,
                125.22,
                200.22,
                222.11,
                150.00,
                250.11,
                100.25,
                125.22,
                200.22,
                222.11,
                150.00,
                250.11
              ],
              refreshCallback: () => {print("Refresh tkt")},
            )
          ],
        )));
  }
}
