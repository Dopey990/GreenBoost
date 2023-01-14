import 'dart:convert';

import 'package:flutter/material.dart';
import 'package:flutter_titled_container/flutter_titled_container.dart';
import 'package:shared_preferences/shared_preferences.dart';

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
      backgroundColor: const Color.fromRGBO(168, 203, 208, 1),
      appBar: AppBar(
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
                    titleColor: const Color.fromRGBO(31, 120, 180, 1),
                    title: "Eco-Score",
                    textAlign: TextAlignTitledContainer.Left,
                    fontSize: 16.0,
                    backgroundColor: const Color.fromRGBO(168, 203, 208, 1),
                    child: Container(
                      width: 150,
                      height: 100,
                      decoration: BoxDecoration(
                        border: Border.all(
                          color: const Color.fromRGBO(48, 69, 178, 1),
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

                          return Image.asset(
                              data["waterScore"] ?? 0 > 80
                                  ? 'assets/home/eau-vert.png'
                                  : data["waterScore"] ?? 0 > 40
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

                          return Image.asset(
                              data["electricityScore"] ?? 0 > 80
                                  ? 'assets/home/electricite-vert.png'
                                  : data["electricityScore"] ?? 0 > 40
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

                          return Image.asset(
                              data["gazScore"] ?? 0 > 80
                                  ? 'assets/home/gaz-vert.png'
                                  : data["gazScore"] ?? 0 > 40
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

                          return Image.asset(
                              data["pollutionScore"] ?? 0 > 80
                                  ? 'assets/home/pollution-vert.png'
                                  : data["pollutionScore"] ?? 0 > 40
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
          Center(
              child: TitledContainer(
            titleColor: const Color.fromRGBO(48, 69, 178, 1),
            title: "Défis",
            textAlign: TextAlignTitledContainer.Center,
            fontSize: 16.0,
            backgroundColor: const Color.fromRGBO(168, 203, 208, 1),
            child: Container(
              width: MediaQuery.of(context).size.width / 1.25,
              height: 100,
              decoration: BoxDecoration(
                border: Border.all(
                  color: const Color.fromRGBO(48, 69, 178, 1),
                ),
                borderRadius: const BorderRadius.all(
                  Radius.circular(10.0),
                ),
              ),
              child: const Padding(
                  padding: EdgeInsets.only(top: 10.0),
                  child: Text(
                    "Débrancher trois appareils inutilisés chez vous",
                    style: TextStyle(fontSize: 20.0),
                    textAlign: TextAlign.center,
                  )),
            ),
          )),
          Padding(
              padding: const EdgeInsets.only(top: 10, bottom: 10),
              child: Row(
                mainAxisAlignment: MainAxisAlignment.spaceEvenly,
                children: [
                  IconButton(
                      onPressed: () {
                        print("ok");
                      },
                      icon: const Icon(Icons.check)),
                  IconButton(
                      onPressed: () {
                        print("ok");
                      },
                      icon: const Icon(Icons.close))
                ],
              ))
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
