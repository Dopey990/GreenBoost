import 'dart:convert';

import 'package:GreenBoost/profilePage.dart';
import 'package:flutter/material.dart';
import 'package:shared_preferences/shared_preferences.dart';
import '/components/menu.dart';

import 'package:http/http.dart' as http;

class ClassementPage extends StatefulWidget {
  const ClassementPage({super.key});

  @override
  State<StatefulWidget> createState() => ClassementState();
}

class ClassementState extends State<ClassementPage> {
  late Future<Map<String, dynamic>> user;
  late Future<List<Map<String, dynamic>>> top10;

  @override
  void initState() {
    top10 = getTop10();
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
        title: const Text('Classement'),
      ),
      drawer: const Menu(),
      body: SingleChildScrollView(
        child: Column(children: <Widget>[
          Row(
            //icon of return on the left and help on the right
            mainAxisAlignment: MainAxisAlignment.start,
            children: <Widget>[
              //icon button return
              IconButton(
                onPressed: (() => {
                      Navigator.push(
                        context,
                        MaterialPageRoute(
                            builder: (context) => const ProfilePage()),
                      ),
                    }),
                icon:
                    const Icon(Icons.arrow_back, color: Colors.blue, size: 40),
              ),
            ],
          ),

          Row(
            mainAxisAlignment: MainAxisAlignment.center,
            children: const <Widget>[
              Image(
                image: AssetImage('assets/img/podium.png'),
                height: 100,
                width: 100,
              ),
            ],
          ),

          Padding(
            padding: const EdgeInsets.only(bottom: 30),
            child: Row(
              mainAxisAlignment: MainAxisAlignment.center,
              children: const <Widget>[
                Text(
                  'Classement',
                  style: TextStyle(
                    color: Colors.blue,
                    fontSize: 30,
                    fontWeight: FontWeight.bold,
                  ),
                ),
              ],
            ),
          ),
          //ligne avec les trois premiers
          Row(
            mainAxisAlignment: MainAxisAlignment.spaceEvenly,
            children: <Widget>[
              Padding(
                  padding: const EdgeInsets.only(top: 20),
                  child: FutureBuilder<List<Map<String, dynamic>>>(
                      future: top10,
                      builder: (BuildContext context,
                          AsyncSnapshot<List<Map<String, dynamic>>> snapshot) {
                        if (snapshot.hasData) {
                          var data = snapshot.data!;

                          return Column(
                            children: <Widget>[
                              const Image(
                                image: AssetImage('assets/img/trophy.png'),
                                width: 10,
                              ),
                              Column(children: [
                                const Text('2nd',
                                    style: TextStyle(
                                      color: Colors.blue,
                                      fontSize: 20,
                                      fontWeight: FontWeight.bold,
                                    )),
                                Text(
                                    '${data[1]["firstName"]}, ${data[1]["lastName"]}',
                                    style: const TextStyle(
                                      color: Colors.blue,
                                      fontSize: 20,
                                      fontWeight: FontWeight.bold,
                                    ))
                              ])
                            ],
                          );
                        } else {
                          return const Padding(
                              padding: EdgeInsets.all(20.0),
                              child: CircularProgressIndicator());
                        }
                      })

                  /*Column(
                  children: const <Widget>[
                    Image(
                      image: AssetImage('assets/img/trophy.png'),
                      width: 10,
                    ),
                    Text(
                      '2nd',
                      style: TextStyle(
                        color: Colors.blue,
                        fontSize: 20,
                        fontWeight: FontWeight.bold,
                      ),
                    ),
                  ],
                ),*/
                  ),
              FutureBuilder<List<Map<String, dynamic>>>(
                  future: top10,
                  builder: (BuildContext context,
                      AsyncSnapshot<List<Map<String, dynamic>>> snapshot) {
                    if (snapshot.hasData) {
                      var data = snapshot.data!;

                      return Column(
                        children: <Widget>[
                          const Image(
                            image: AssetImage('assets/img/trophy.png'),
                            width: 10,
                          ),
                          Column(children: [
                            const Text('1st',
                                style: TextStyle(
                                  color: Colors.blue,
                                  fontSize: 20,
                                  fontWeight: FontWeight.bold,
                                )),
                            Text(
                                '${data[0]["firstName"]}, ${data[0]["lastName"]}',
                                style: const TextStyle(
                                  color: Colors.blue,
                                  fontSize: 20,
                                  fontWeight: FontWeight.bold,
                                ))
                          ])
                        ],
                      );
                    } else {
                      return const Padding(
                          padding: EdgeInsets.all(20.0),
                          child: CircularProgressIndicator());
                    }
                  }),
              /*Column(
                children: const <Widget>[
                  Image(
                    image: AssetImage('assets/img/trophy.png'),
                    width: 10,
                  ),
                  Text(
                    '1st',
                    style: TextStyle(
                      color: Colors.blue,
                      fontSize: 20,
                      fontWeight: FontWeight.bold,
                    ),
                  ),
                ],
              ),*/
              Padding(
                  padding: const EdgeInsets.only(top: 40, bottom: 20),
                  child: FutureBuilder<List<Map<String, dynamic>>>(
                      future: top10,
                      builder: (BuildContext context,
                          AsyncSnapshot<List<Map<String, dynamic>>> snapshot) {
                        if (snapshot.hasData) {
                          var data = snapshot.data!;

                          return Column(
                            children: <Widget>[
                              const Image(
                                image: AssetImage('assets/img/trophy.png'),
                                width: 10,
                              ),
                              Column(children: [
                                const Text('3rd',
                                    style: TextStyle(
                                      color: Colors.blue,
                                      fontSize: 20,
                                      fontWeight: FontWeight.bold,
                                    )),
                                Text(
                                    '${data[2]["firstName"]}, ${data[2]["lastName"]}',
                                    style: const TextStyle(
                                      color: Colors.blue,
                                      fontSize: 20,
                                      fontWeight: FontWeight.bold,
                                    ))
                              ])
                            ],
                          );
                        } else {
                          return const Padding(
                              padding: EdgeInsets.all(20.0),
                              child: CircularProgressIndicator());
                        }
                      })
                  /*Column(
                  children: const <Widget>[
                    Image(
                      image: AssetImage('assets/img/trophy.png'),
                      width: 10,
                    ),
                    Text(
                      '3rd',
                      style: TextStyle(
                        color: Colors.blue,
                        fontSize: 20,
                        fontWeight: FontWeight.bold,
                      ),
                    ),
                  ],
                ),*/
                  ),
            ],
          ),

          Container(
              width: MediaQuery.of(context).size.width * 0.8,
              padding: const EdgeInsets.only(
                  left: 15.0, right: 15.0, top: 15, bottom: 15),
              decoration: BoxDecoration(
                color: Colors.blue,
                borderRadius: BorderRadius.circular(10),
              ),
              child: FutureBuilder<List<Map<String, dynamic>>>(
                  future: top10,
                  builder: (BuildContext context,
                      AsyncSnapshot<List<Map<String, dynamic>>> snapshot) {
                    if (snapshot.hasData) {
                      var data = snapshot.data!;

                      return Column(
                          children: data
                              .map((e) => Padding(
                                  padding: const EdgeInsets.only(bottom: 10),
                                  child: Row(
                                      mainAxisAlignment:
                                          MainAxisAlignment.spaceBetween,
                                      children: <Widget>[
                                        const Image(
                                          image: AssetImage(
                                              'assets/img/trophy.png'),
                                          width: 20,
                                        ),
                                        Text(
                                          "${e["firstName"]}",
                                          style: const TextStyle(
                                            color: Colors.white,
                                            fontSize: 15,
                                            fontWeight: FontWeight.bold,
                                          ),
                                        ),
                                        Text(
                                          '${e["rank"]} arbres',
                                          style: const TextStyle(
                                            color: Colors.white,
                                            fontSize: 15,
                                            fontWeight: FontWeight.bold,
                                          ),
                                        ),
                                      ])))
                              .toList());
                    } else {
                      return const Padding(
                          padding: EdgeInsets.all(20.0),
                          child: CircularProgressIndicator());
                    }
                  })),
          const SizedBox(
            height: 10,
          ),
          Container(
            width: MediaQuery.of(context).size.width * 0.8,
            padding: const EdgeInsets.only(
                left: 15.0, right: 15.0, top: 15, bottom: 15),
            decoration: BoxDecoration(
              color: Colors.blue,
              borderRadius: BorderRadius.circular(10),
            ),
            child: Padding(
                padding: const EdgeInsets.all(10),
                child: Row(
                  mainAxisAlignment: MainAxisAlignment.spaceBetween,
                  children: <Widget>[
                    FutureBuilder<Map<String, dynamic>>(
                        future: user,
                        builder: (BuildContext context,
                            AsyncSnapshot<Map<String, dynamic>> snapshot) {
                          if (snapshot.hasData) {
                            var data = snapshot.data!;

                            return Padding(
                                padding: const EdgeInsets.only(bottom: 10),
                                child: Row(
                                    mainAxisAlignment:
                                        MainAxisAlignment.spaceBetween,
                                    children: <Widget>[
                                      const Image(
                                        image:
                                            AssetImage('assets/img/trophy.png'),
                                        width: 20,
                                      ),
                                      Column(children: [
                                        Text(
                                          "${data["firstName"]}, ${data["lastName"]}",
                                          style: const TextStyle(
                                            color: Colors.white,
                                            fontSize: 15,
                                            fontWeight: FontWeight.bold,
                                          ),
                                        ),
                                        Text(
                                          '${data["rank"]} arbres',
                                          style: const TextStyle(
                                            color: Colors.white,
                                            fontSize: 15,
                                            fontWeight: FontWeight.bold,
                                          ),
                                        )
                                      ])
                                    ]));
                          } else {
                            return const Padding(
                                padding: EdgeInsets.all(20.0),
                                child: CircularProgressIndicator());
                          }
                        })
                  ],
                )),
          ),
        ]),
      ),
    );
  }

  Future<Map<String, dynamic>> getUser() async {
    SharedPreferences prefs = await SharedPreferences.getInstance();
    Map<String, dynamic> userMap =
        jsonDecode(prefs.getString('user')!) as Map<String, dynamic>;

    return userMap;
  }

  Future<List<Map<String, dynamic>>> getTop10() async {
    final response =
        await http.get(Uri.parse("http://localhost:8080/user/getTop10"));

    List<Map<String, dynamic>> result = [];

    if (response.statusCode == 200) {
      jsonDecode(response.body).forEach((line) {
        result.add(line);
      });

      return result;
    } else {
      throw Exception("Failed to load top 10");
    }
  }
}
