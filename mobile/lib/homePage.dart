import 'dart:convert';

import 'package:flutter/material.dart';
import 'package:flutter_titled_container/flutter_titled_container.dart';
import 'package:shared_preferences/shared_preferences.dart';

import 'components/advicesViewer.dart';
import 'components/menu.dart';

class HomePage extends StatefulWidget {
  const HomePage({super.key});

  @override
  State<StatefulWidget> createState() => HomeState();
}

class HomeState extends State<HomePage> {
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
        title: const Text('GreenBoost App'),
        
      ),
      drawer: const Menu(),
      body: Column(
        children: [
          const SizedBox(height: 10),
          Align(
              alignment: Alignment.centerRight,
              child: Padding(
                padding: const EdgeInsets.only(right: 10),
                child: IconButton(
                    onPressed: () {
                      Navigator.of(context).pushNamed("/advices");
                    },
                    icon: const Icon(Icons.lightbulb)),
              )),
          FutureBuilder<Map<String, dynamic>>(
              future: user,
              builder: (BuildContext context,
                  AsyncSnapshot<Map<String, dynamic>> snapshot) {
                if (snapshot.hasData) {
                  var data = snapshot.data!;

                  return TitledContainer(
                    titleColor: Color.fromARGB(255, 38, 121, 64),
                    title: "Eco-Score",
                    textAlign: TextAlignTitledContainer.Left,
                    fontSize: 16.0,
                    backgroundColor: Color.fromARGB(255, 177, 201, 183),      
                    child: Container(
                      width: 120,
                      height: 70,
                      decoration: BoxDecoration(
                        border: Border.all(
                          color: Color.fromARGB(255, 85, 178, 48),
                        ),
                        borderRadius: const BorderRadius.all(
                          Radius.circular(10.0),
                        ),
                      ),
                      child: Center(
                        child: Text(
                          "${data["ecoScore"] ?? 0}/100",
                          style: const TextStyle(fontSize: 28.0),
                        ),
                      ),
                    ),
                  );
                } else {
                  return const Padding(
                      padding: EdgeInsets.all(20.0),
                      child: CircularProgressIndicator());
                }
              }),
          const SizedBox(height: 20.0),
          Padding(
              padding:
                  EdgeInsets.only(right: MediaQuery.of(context).size.width / 3),
              child: GestureDetector(
                  onTap: () {
                    Navigator.of(context).pushNamed("/info/water");
                  },
                  child: FutureBuilder<Map<String, dynamic>>(
                      future: user,
                      builder: (BuildContext context,
                          AsyncSnapshot<Map<String, dynamic>> snapshot) {
                        if (snapshot.hasData) {
                          var data = snapshot.data!;
                          int waterScore = data["waterScore"] ?? 0;

                          return Image.asset(
                              waterScore > 80
                                  ? 'assets/home/eau-vert.png'
                                  : waterScore > 40
                                      ? "assets/home/eau-orange.png"
                                      : "assets/home/eau-rouge.png",
                              height: MediaQuery.of(context).size.width / 4.5,
                              width: MediaQuery.of(context).size.width / 4.5,
                              fit: BoxFit.fitWidth);
                        } else {
                          return const Padding(
                              padding: EdgeInsets.all(20.0),
                              child: CircularProgressIndicator());
                        }
                      }))),
          Padding(
              padding:
                  EdgeInsets.only(left: MediaQuery.of(context).size.width / 3),
              child: GestureDetector(
                  onTap: () {
                    Navigator.of(context).pushNamed("/info/electricity");
                  },
                  child: FutureBuilder<Map<String, dynamic>>(
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
                      }))),
          Padding(
              padding:
                  EdgeInsets.only(right: MediaQuery.of(context).size.width / 3),
              child: GestureDetector(
                  onTap: () {
                    return;
                  },
                  child: FutureBuilder<Map<String, dynamic>>(
                      future: user,
                      builder: (BuildContext context,
                          AsyncSnapshot<Map<String, dynamic>> snapshot) {
                        if (snapshot.hasData) {
                          var data = snapshot.data!;
                          int gazScore = data["gazScore"] ?? 0;

                          return Image.asset(
                              gazScore > 80
                                  ? 'assets/home/gaz-vert.png'
                                  : gazScore > 40
                                      ? "assets/home/gaz-orange.png"
                                      : "assets/home/gaz-rouge.png",
                              height: MediaQuery.of(context).size.width / 4.5,
                              width: MediaQuery.of(context).size.width / 4.5,
                              fit: BoxFit.fitWidth);
                        } else {
                          return const Padding(
                              padding: EdgeInsets.all(20.0),
                              child: CircularProgressIndicator());
                        }
                      }))),
          Padding(
              padding:
                  EdgeInsets.only(left: MediaQuery.of(context).size.width / 3),
              child: GestureDetector(
                  onTap: () {
                    return;
                  },
                  child: FutureBuilder<Map<String, dynamic>>(
                      future: user,
                      builder: (BuildContext context,
                          AsyncSnapshot<Map<String, dynamic>> snapshot) {
                        if (snapshot.hasData) {
                          var data = snapshot.data!;
                          int pollutionScore = data["pollutionScore"] ?? 0;

                          return Image.asset(
                              pollutionScore > 80
                                  ? 'assets/home/pollution-vert.png'
                                  : pollutionScore > 40
                                      ? "assets/home/pollution-orange.png"
                                      : "assets/home/pollution-rouge.png",
                              height: MediaQuery.of(context).size.width / 4.5,
                              width: MediaQuery.of(context).size.width / 4.5,
                              fit: BoxFit.fitWidth);
                        } else {
                          return const Padding(
                              padding: EdgeInsets.all(20.0),
                              child: CircularProgressIndicator());
                        }
                      }))),
          const Spacer(),
          AdvicesViewerWidget(
            apiUrl:
                "http://localhost:8080/advices/getByCategory?category=global&language=FR",
          ),
          const Spacer()
        ],
      ),
    );
  }

  Future<Map<String, dynamic>> getUser() async {
    SharedPreferences prefs = await SharedPreferences.getInstance();
    Map<String, dynamic> userMap =
        jsonDecode(prefs.getString('user')!) as Map<String, dynamic>;

    return userMap;
  }
}
