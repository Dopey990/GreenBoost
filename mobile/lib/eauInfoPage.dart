import 'package:GreenBoost/components/productsActivitiesToolbox.dart';
import 'package:flutter/material.dart';
import 'package:fl_chart/fl_chart.dart';

import 'components/advicesViewer.dart';
import 'components/menu.dart';
import 'components/pricesForecastChart.dart';

class eauInfoPage extends StatefulWidget {
  const eauInfoPage({super.key});

  @override
  State<StatefulWidget> createState() => EauInfoState();
}

class EauInfoState extends State<eauInfoPage> {
  @override
  Widget build(BuildContext context) {
    return Scaffold(
        appBar: AppBar(
          centerTitle: true,
          title: const Text("Eeau"),
        ),
        drawer: const Menu(),
        body: SingleChildScrollView(
            child: Center(
                child: Column(
          children: [
            const Padding(padding: EdgeInsets.only(top: 20)),
            Image.asset('assets/home/eau.png',
                height: MediaQuery.of(context).size.width / 6,
                width: MediaQuery.of(context).size.width / 6),
            const PricesForecastWidget(
              chartTitle: "Consomation d'eau national",
              apiUrl: "http://localhost:8080/prices/eau",
            ),
            ProductsActivitiesToolboxWidget(),
            const Padding(padding: EdgeInsets.only(top: 20)),
            AdvicesViewerWidget(
              apiUrl:
                  "http://localhost:8080/advices/getByCategory?category=eau&language=FR",
            )
          ],
        ))));
  }
}
