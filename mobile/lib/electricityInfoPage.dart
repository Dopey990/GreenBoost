import 'package:GreenBoost/components/productsActivitiesToolbox.dart';
import 'package:flutter/material.dart';
import 'package:fl_chart/fl_chart.dart';

import 'components/advicesViewer.dart';
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
           backgroundColor: Color.fromARGB(255, 177, 201, 183),
      appBar: AppBar(
        backgroundColor: Color.fromARGB(255, 78, 129, 91),
          centerTitle: true,
          title: const Text("Électricité"),
        ),
        drawer: const Menu(),
        body: SingleChildScrollView(
            child: Center(
                child: Column(
          children: [
            const Padding(padding: EdgeInsets.only(top: 20)),
            Image.asset('assets/home/electricite-vert.png',
                height: MediaQuery.of(context).size.width / 6,
                width: MediaQuery.of(context).size.width / 6),
            const PricesForecastWidget(
              chartTitle: "Prix électricité",
              apiUrl:
                  "http://localhost:8080/prices/electricity/getDayAheadPrices",
            ),
            ProductsActivitiesToolboxWidget(),
            const Padding(padding: EdgeInsets.only(top: 20)),
            AdvicesViewerWidget(
              apiUrl:
                  "http://localhost:8080/advices/getByCategory?category=electricity&language=FR",
            )
          ],
        ))));
  }
}
