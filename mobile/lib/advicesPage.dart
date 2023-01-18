import 'dart:convert';

import 'package:GreenBoost/classementPage.dart';
import 'package:flutter/material.dart';
import 'package:shared_preferences/shared_preferences.dart';
import '/components/menu.dart';
import 'package:GreenBoost/settingsProfilePage.dart';

import 'package:http/http.dart' as http;

class AdvicesPage extends StatefulWidget {
  String category;
  String title;

  AdvicesPage({super.key, required this.category, required this.title});

  @override
  State<StatefulWidget> createState() => AdvicesState();
}

class AdvicesState extends State<AdvicesPage> {
  late Future<List<String>> advices;

  @override
  void initState() {
    advices = getAdvices(widget.category);
    super.initState();
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: Color.fromARGB(255, 178, 205, 185),
      appBar: AppBar(
        centerTitle: true,
        title: Text('Conseils sur ${widget.title}'),
      ),
      body: SingleChildScrollView(
        child: Column(
          mainAxisAlignment: MainAxisAlignment.center,
          children: <Widget>[
            const Padding(
              padding: EdgeInsets.only(top: 30, bottom: 30),
              child: Text(
                "Liste des conseils",
                style: TextStyle(
                    color: Color.fromRGBO(31, 120, 180, 1),
                    fontSize: 25,
                    fontWeight: FontWeight.bold),
              ),
            ),
            FutureBuilder<List<String>>(
                future: advices,
                builder: (BuildContext context,
                    AsyncSnapshot<List<String>> snapshot) {
                  if (snapshot.hasData) {
                    var data = snapshot.data!;

                    return ListView(
                        shrinkWrap: true,
                        padding: const EdgeInsets.all(10),
                        children: data
                            .map(
                              (e) => Card(
                                borderOnForeground: true,
                                child: Column(
                                  children: [
                                    Container(
                                      padding: const EdgeInsets.all(16),
                                      alignment: Alignment.center,
                                      child: Text(
                                        e.toString(),
                                        style: const TextStyle(
                                            fontWeight: FontWeight.bold),
                                      ),
                                    )
                                  ],
                                ),
                              ),
                            )
                            .toList());
                  } else {
                    return const Center(
                        child: Padding(
                            padding: EdgeInsets.all(20.0),
                            child: CircularProgressIndicator()));
                  }
                }),
          ],
        ),
      ),
    );
  }

  Future<Map<String, dynamic>> getUser() async {
    SharedPreferences prefs = await SharedPreferences.getInstance();
    Map<String, dynamic> userMap =
        jsonDecode(prefs.getString('user')!) as Map<String, dynamic>;

    return userMap;
  }

  Future<List<String>> getAdvices(String category) async {
    Map<String, dynamic> user = await getUser();
    final response = await http.get(Uri.parse(
        "http://localhost:8080/advices/getByCategory?category=$category&language=${user["language"]}"));

    List<String> result = [];

    if (response.statusCode == 200) {
      jsonDecode(response.body).forEach((line) {
        result.add(line);
      });

      return result;
    } else {
      throw Exception("Failed to load advices");
    }
  }
}
