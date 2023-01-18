import 'package:GreenBoost/components/productsActivitiesToolbox.dart';
import 'package:flutter/material.dart';
import 'package:fl_chart/fl_chart.dart';

import 'components/advicesViewer.dart';
import 'components/menu.dart';
import 'components/pricesForecastChart.dart';
import 'package:carousel_slider/carousel_slider.dart';

class EauInfoPage extends StatefulWidget {
  const EauInfoPage({super.key});

  @override
  State<StatefulWidget> createState() => EauInfoState();
}

class EauInfoState extends State<EauInfoPage> {
  //M'insulte pas Thomas =
  final List<String> images = [
    "https://www.un.org/africarenewal/sites/www.un.org.africarenewal/files/12-15-2015WashUNICEF.jpg",
    "https://www.un.org/africarenewal/sites/www.un.org.africarenewal/files/12-15-2015WashUNICEF.jpg",
    "https://www.un.org/africarenewal/sites/www.un.org.africarenewal/files/12-15-2015WashUNICEF.jpg",
    "https://www.un.org/africarenewal/sites/www.un.org.africarenewal/files/12-15-2015WashUNICEF.jpg",
    "https://www.un.org/africarenewal/sites/www.un.org.africarenewal/files/12-15-2015WashUNICEF.jpg",
    "https://www.example.com/image6.jpg",
  ];
  @override
  int _currentPage = 0;

  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: Color.fromARGB(255, 178, 205, 185),
        appBar: AppBar(
          centerTitle: true,
          title: const Text("Eau"),
        ),
        drawer: const Menu(),
        body: SingleChildScrollView(
            child: Center(
                child: Column(
          children: [
            const Padding(padding: EdgeInsets.only(top: 20)),
            Image.asset('assets/home/eau-vert.png',
                height: MediaQuery.of(context).size.width / 6,
                width: MediaQuery.of(context).size.width / 6),
            SizedBox(height: 20.0),
            CarouselSlider(
              options: CarouselOptions(height: 400.0),
              items: [
                'https://www.un.org/africarenewal/sites/www.un.org.africarenewal/files/12-15-2015WashUNICEF.jpg',
                'https://www.un.org/africarenewal/sites/www.un.org.africarenewal/files/12-15-2015WashUNICEF.jpg',
                'https://www.un.org/africarenewal/sites/www.un.org.africarenewal/files/12-15-2015WashUNICEF.jpg',
              ].map((i) {
                return Builder(
                  builder: (BuildContext context) {
                    return Container(
                      width: MediaQuery.of(context).size.width,
                      margin: EdgeInsets.symmetric(horizontal: 5.0),
                      decoration: BoxDecoration(
                          color: Color.fromARGB(255, 185, 177, 188)),
                      child: Image.network(i),
                    );
                  },
                );
              }).toList(),
            ),
            const Padding(padding: EdgeInsets.only(top: 20)),
            AdvicesViewerWidget(
              apiUrl:
                  "http://localhost:8080/advices/getByCategory?category=water&language=FR",
            ),
            const Padding(padding: EdgeInsets.only(top: 20)),
          ],
        ))));
  }
}
