import 'dart:convert';

import 'package:GreenBoost/components/productsActivitiesToolbox.dart';
import 'package:flutter/material.dart';
import 'package:fl_chart/fl_chart.dart';
import 'package:shared_preferences/shared_preferences.dart';

import 'components/advicesViewer.dart';
import 'components/menu.dart';
import 'components/pricesForecastChart.dart';

class ElectricityInfoPage extends StatefulWidget {
  const ElectricityInfoPage({super.key});

  @override
  State<StatefulWidget> createState() => ElectricityInfoState();
}

class ElectricityInfoState extends State<ElectricityInfoPage> {
  late Future<Map<String, dynamic>> user;

  @override
  void initState() {
    user = getUser();
    super.initState();
  }

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
            FutureBuilder<Map<String, dynamic>>(
                future: user,
                builder: (BuildContext context,
                    AsyncSnapshot<Map<String, dynamic>> snapshot) {
                  if (snapshot.hasData) {
                    var data = snapshot.data!;

                    int electricityScore = data["electricityScore"] ?? 0;

                    return Image.asset(
                        electricityScore > 80
                            ? 'assets/home/electricite-vert.png'
                            : electricityScore > 40
                                ? "assets/home/electricite-orange.png"
                                : "assets/home/electricite-rouge.png",
                        height: MediaQuery.of(context).size.width / 4.5,
                        width: MediaQuery.of(context).size.width / 4.5,
                        fit: BoxFit.fitWidth);
                  } else {
                    return const Padding(
                        padding: EdgeInsets.all(20.0),
                        child: CircularProgressIndicator());
                  }
                }),
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

  Future<Map<String, dynamic>> getUser() async {
    SharedPreferences prefs = await SharedPreferences.getInstance();
    Map<String, dynamic> userMap =
        jsonDecode(prefs.getString('user')!) as Map<String, dynamic>;

    return userMap;
  }
}
