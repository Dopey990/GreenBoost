//create a component to display the menu
import 'dart:convert';

import 'package:GreenBoost/profilePage.dart';
import 'package:flutter/material.dart';
import 'package:shared_preferences/shared_preferences.dart';

import '../advicesPage.dart';

class Menu extends StatefulWidget {
  const Menu({super.key});

  @override
  State<StatefulWidget> createState() => MenuState();
}

class MenuState extends State<Menu> {
  late Future<Map<String, dynamic>> user;

  @override
  void initState() {
    user = getUser();
    super.initState();
  }

  @override
  Widget build(BuildContext context) {
    return Drawer(
        child: ListView(
      children: <Widget>[
        GestureDetector(
          onTap: () {
            Navigator.push(context,
                MaterialPageRoute(builder: (context) => ProfilePage()));
          },
          child: SizedBox(
            height: 115,
            child: DrawerHeader(
                decoration: const BoxDecoration(
                  color: Color.fromARGB(255, 38, 103, 53),
                ),
                child: FutureBuilder<Map<String, dynamic>>(
                    future: user,
                    builder: (BuildContext context,
                        AsyncSnapshot<Map<String, dynamic>> snapshot) {
                      if (snapshot.hasData) {
                        var data = snapshot.data!;

                        return Row(
                          mainAxisAlignment: MainAxisAlignment.start,
                          children: <Widget>[
                            const CircleAvatar(
                              radius: 40,
                              child: Icon(Icons.person),
                            ),
                            Column(
                              mainAxisAlignment: MainAxisAlignment.center,
                              crossAxisAlignment: CrossAxisAlignment.start,
                              children: [
                                Text("${data["firstName"]} ${data["lastName"]}",
                                    style: const TextStyle(
                                      fontWeight: FontWeight.bold,
                                      color: Colors.white,
                                      fontSize: 25,
                                    )),
                                Text("Score : ${data["ecoScore"] ?? 0}",
                                    style: const TextStyle(
                                      color: Colors.white,
                                      fontSize: 15,
                                    )),
                              ],
                            ),
                          ],
                        );
                      } else {
                        return const Padding(
                            padding: EdgeInsets.all(20.0),
                            child: CircularProgressIndicator());
                      }
                    })),
          ),
        ),
        ListTile(
          leading: const Icon(Icons.home),
          title: const Text('Accueil'),
          onTap: () {
            Navigator.pop(context);
            Navigator.pushNamed(context, '/home');
          },
        ),
        ListTile(
          leading: const Icon(Icons.flash_on),
          title: const Text('Consommation'),
          onTap: () {
            Navigator.pop(context);
            Navigator.pushNamed(context, '/consommation');
          },
        ),
        ListTile(
          leading: const Icon(Icons.star),
          title: const Text('Challenges'),
          onTap: () {
            Navigator.pop(context);
            Navigator.pushNamed(context, '/challenges');
          },
        ),
        ListTile(
          leading: const Icon(Icons.lightbulb),
          title: const Text('Conseils généraux'),
          onTap: () {
            Navigator.pop(context);
            Navigator.push(
              context,
              MaterialPageRoute(
                  builder: (context) => AdvicesPage(
                        category: "global",
                        title: "tout",
                      )),
            );
          },
        ),
        ListTile(
          leading: const Icon(Icons.contact_mail),
          title: const Text('Contact'),
          onTap: () {
            Navigator.pop(context);
            Navigator.pushNamed(context, '/contact');
          },
        ),
        ListTile(
          leading: const Icon(Icons.forum),
          title: const Text('Forum'),
          onTap: () {
            Navigator.pop(context);
            Navigator.pushNamed(context, '/forum');
          },
        ),
      ],
    ));
  }

  Future<Map<String, dynamic>> getUser() async {
    SharedPreferences prefs = await SharedPreferences.getInstance();
    Map<String, dynamic> userMap =
        jsonDecode(prefs.getString('user')!) as Map<String, dynamic>;

    return userMap;
  }
}
