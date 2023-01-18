import 'dart:convert';

import 'package:GreenBoost/classementPage.dart';
import 'package:flutter/material.dart';
import 'package:shared_preferences/shared_preferences.dart';
import '/components/menu.dart';
import 'package:GreenBoost/settingsProfilePage.dart';

class ProfilePage extends StatefulWidget {
  const ProfilePage({super.key});

  @override
  State<StatefulWidget> createState() => ProfileState();
}

class ProfileState extends State<ProfilePage> {
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
        title: const Text('Profil'),
      ),
      drawer: const Menu(),
      body: SingleChildScrollView(
          child: Column(children: <Widget>[
        Row(
          //icon of settings on the right
          mainAxisAlignment: MainAxisAlignment.end,

          children: <Widget>[
            IconButton(
              onPressed: () => {
                Navigator.push(
                  context,
                  MaterialPageRoute(
                      builder: (context) => SettingsProfilePage()),
                )
              },
              color: Colors.blue,
              padding: const EdgeInsets.all(30),
              icon: const Icon(Icons.settings, size: 40),
            ),
          ],
        ),
        Padding(
          padding: const EdgeInsets.only(top: 1.0),
          child: Center(
            child: Image.asset(
              'assets/img/trophy.png',
              height: 100,
              width: 100,
              fit: BoxFit.fitWidth,
            ),
          ),
        ),
        Container(
          height: MediaQuery.of(context).size.height * 0.6,
          width: MediaQuery.of(context).size.width * 0.8,
          padding: const EdgeInsets.only(
              left: 15.0, right: 15.0, top: 10, bottom: 10),
          decoration: BoxDecoration(
            color: const Color.fromRGBO(217, 217, 217, 1),
            borderRadius: BorderRadius.circular(10),
          ),
          child: FutureBuilder<Map<String, dynamic>>(
              future: user,
              builder: (BuildContext context,
                  AsyncSnapshot<Map<String, dynamic>> snapshot) {
                if (snapshot.hasData) {
                  var data = snapshot.data!;

                  return Column(
                      mainAxisAlignment: MainAxisAlignment.spaceEvenly,
                      children: <Widget>[
                        Row(
                          mainAxisAlignment: MainAxisAlignment.start,
                          children: <Widget>[
                            GestureDetector(
                              onTap: () => {
                                Navigator.push(
                                  context,
                                  MaterialPageRoute(
                                      builder: (context) => ClassementPage()),
                                )
                              },
                              child: const Image(
                                image: AssetImage('assets/img/podium.png'),
                                height: 50,
                                width: 50,
                              ),
                            ),
                          ],
                        ),
                        Padding(
                          padding: const EdgeInsets.only(bottom: 10),
                          child: Text(
                            "Position : ${data["rank"] ?? "?"}ème",
                            style: const TextStyle(
                                color: Color.fromRGBO(31, 120, 180, 1),
                                fontSize: 25,
                                fontWeight: FontWeight.bold),
                          ),
                        ),
                        SizedBox(
                          width: MediaQuery.of(context).size.width * 0.8,
                          child: Card(
                            child: Container(
                              decoration: BoxDecoration(
                                color: const Color.fromRGBO(125, 192, 120, 1),
                                borderRadius: BorderRadius.circular(10),
                              ),
                              padding: const EdgeInsets.all(20),
                              child: Text(
                                  "Electricité : ${data["electricityRank"] ?? "?"}ème"),
                            ),
                          ),
                        ),
                        SizedBox(
                          width: MediaQuery.of(context).size.width * 0.8,
                          child: Card(
                            child: Container(
                              decoration: BoxDecoration(
                                color: const Color.fromRGBO(236, 188, 118, 1),
                                borderRadius: BorderRadius.circular(10),
                              ),
                              padding: const EdgeInsets.all(20),
                              child: Text(
                                "Eau : ${data["waterRank"] ?? "?"}ème",
                              ),
                            ),
                          ),
                        ),
                        SizedBox(
                          width: MediaQuery.of(context).size.width * 0.8,
                          child: Card(
                            child: Container(
                              decoration: BoxDecoration(
                                color: const Color.fromRGBO(125, 192, 120, 1),
                                borderRadius: BorderRadius.circular(10),
                              ),
                              padding: const EdgeInsets.all(20),
                              child: Text("Gaz : ${data["gazRank"] ?? "?"}ème"),
                            ),
                          ),
                        ),
                        SizedBox(
                          width: MediaQuery.of(context).size.width * 0.8,
                          child: Card(
                            child: Container(
                              decoration: BoxDecoration(
                                color: const Color.fromRGBO(212, 115, 127, 1),
                                borderRadius: BorderRadius.circular(10),
                              ),
                              padding: const EdgeInsets.all(20),
                              child: Text(
                                  "Pollution : ${data["pollutionRank"] ?? "?"}ème"),
                            ),
                          ),
                        ),
                      ]);
                } else {
                  return const Padding(
                      padding: EdgeInsets.all(20.0),
                      child: CircularProgressIndicator());
                }
              }),
        )
      ])),
    );
  }

  Future<Map<String, dynamic>> getUser() async {
    SharedPreferences prefs = await SharedPreferences.getInstance();
    Map<String, dynamic> userMap =
        jsonDecode(prefs.getString('user')!) as Map<String, dynamic>;

    return userMap;
  }
}
