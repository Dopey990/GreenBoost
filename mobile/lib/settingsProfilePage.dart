import 'dart:convert';
import 'dart:core';

import 'package:GreenBoost/profilePage.dart';
import 'package:flutter/material.dart';
import 'package:flutter/services.dart';
import 'package:shared_preferences/shared_preferences.dart';
import '/components/menu.dart';
import 'package:http/http.dart' as http;

import 'package:lite_rolling_switch/lite_rolling_switch.dart';

class SettingsProfilePage extends StatefulWidget {
  @override
  _SettingsProfilePageState createState() => _SettingsProfilePageState();
}

class _SettingsProfilePageState extends State<SettingsProfilePage> {
  late bool _enabledContact;
  late bool _enabledHouse;
  late Future<Map<String, dynamic>> user;
  late Future<Map<String, dynamic>> house;
  final houseAreaController = TextEditingController();
  final houseNbLivingPersonController = TextEditingController();
  final userFirstNameController = TextEditingController();
  final userLastNameController = TextEditingController();
  final userEmailController = TextEditingController();

  @override
  void initState() {
    super.initState();
    user = getUser();
    house = getHouse();
    _enabledContact = false;
    _enabledHouse = false;
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: Color.fromARGB(255, 178, 205, 185),
      appBar: AppBar(
        centerTitle: true,
        title: const Text('Settings Profile'),
      ),
      drawer: const Menu(),
      body: SingleChildScrollView(
        child: FutureBuilder<Map<String, dynamic>>(
            future: user,
            builder: (BuildContext context,
                AsyncSnapshot<Map<String, dynamic>> snapshot) {
              if (snapshot.hasData) {
                var userData = snapshot.data!;
                return Column(children: <Widget>[
                  Row(
                    //icon of return on the left and help on the right
                    mainAxisAlignment: MainAxisAlignment.spaceBetween,
                    children: <Widget>[
                      //icon button return
                      IconButton(
                        onPressed: (() => {
                              Navigator.push(
                                context,
                                MaterialPageRoute(
                                    builder: (context) => ProfilePage()),
                              ),
                            }),
                        icon: const Icon(Icons.arrow_back,
                            color: Colors.blue, size: 40),
                      ),
                      IconButton(
                          onPressed: () {
                            showDialog(
                              context: context,
                              builder: (BuildContext context) =>
                                  _buildPopupDialog(context),
                            );
                          },
                          icon: const Icon(Icons.help,
                              color: Colors.blue, size: 40),
                          padding: const EdgeInsets.all(30)),
                    ],
                  ),
                  Row(
                    mainAxisAlignment: MainAxisAlignment.center,
                    children: <Widget>[
                      Center(
                        child: Padding(
                          padding: const EdgeInsets.all(10),
                          child: Image.asset(
                            'assets/img/person.png',
                            height: 100,
                            width: 100,
                            fit: BoxFit.fitWidth,
                          ),
                        ),
                      ),
                    ],
                  ),
                  Container(
                    height: MediaQuery.of(context).size.height * 0.35,
                    width: MediaQuery.of(context).size.width * 0.8,
                    padding: const EdgeInsets.all(10),
                    decoration: BoxDecoration(
                      color: const Color.fromRGBO(217, 217, 217, 1),
                      borderRadius: BorderRadius.circular(20),
                    ),
                    child: Column(
                      children: <Widget>[
                        Row(
                          mainAxisAlignment: MainAxisAlignment.end,
                          children: <Widget>[
                            IconButton(
                              onPressed: () => setState(() {
                                if (_enabledContact == false) {
                                  _enabledContact = true;
                                } else {
                                  _enabledContact = false;
                                  user = updateUser(userData['id'], userFirstNameController.text, userLastNameController.text, userEmailController.text);
                                }
                              }),
                              icon: const Icon(Icons.edit,
                                  color: Colors.black, size: 20),
                            )
                          ],
                        ),
                        Row(
                          mainAxisAlignment: MainAxisAlignment.start,
                          children: <Widget>[
                            const Text(
                              'Nom : ',
                              style: TextStyle(
                                color: Colors.black,
                                fontSize: 15,
                              ),
                            ),
                            Flexible(
                              child: TextField(
                                controller: userLastNameController,
                                enabled: _enabledContact,
                                cursorHeight: 15,
                                decoration: InputDecoration(
                                  border: InputBorder.none,
                                  hintText: userData["lastName"],
                                  hintStyle: const TextStyle(
                                    color: Colors.black,
                                    fontSize: 15,
                                  ),
                                ),
                              ),
                            ),
                          ],
                        ),
                        Row(
                          mainAxisAlignment: MainAxisAlignment.start,
                          children: <Widget>[
                            const Text(
                              'Prénom : ',
                              style: TextStyle(
                                color: Colors.black,
                                fontSize: 15,
                              ),
                            ),
                            Flexible(
                              child: TextField(
                                controller: userFirstNameController,
                                enabled: _enabledContact,
                                cursorHeight: 15,
                                decoration: InputDecoration(
                                  border: InputBorder.none,
                                  hintText: userData['firstName'],
                                  hintStyle: const TextStyle(
                                    color: Colors.black,
                                    fontSize: 15,
                                  ),
                                ),
                              ),
                            ),
                          ],
                        ),
                        Row(
                          mainAxisAlignment: MainAxisAlignment.start,
                          children: <Widget>[
                            const Text(
                              'Email : ',
                              style: TextStyle(
                                color: Colors.black,
                                fontSize: 15,
                              ),
                            ),
                            Flexible(
                              child: TextField(
                                controller: userEmailController,
                                enabled: _enabledContact,
                                cursorHeight: 15,
                                decoration: InputDecoration(
                                  border: InputBorder.none,
                                  hintText: userData['email'],
                                  hintStyle: const TextStyle(
                                    color: Colors.black,
                                    fontSize: 15,
                                  ),
                                ),
                              ),
                            ),
                          ],
                        ),
                      ],
                    ),
                  ),
                  Row(
                    mainAxisAlignment: MainAxisAlignment.center,
                    children: <Widget>[
                      Center(
                        child: Image.asset(
                          'assets/img/maison.png',
                          height: 100,
                          width: 100,
                          fit: BoxFit.fitWidth,
                        ),
                      ),
                    ],
                  ),
                  FutureBuilder<Map<String, dynamic>>(
                      future: house,
                      builder: (BuildContext context,
                          AsyncSnapshot<Map<String, dynamic>> snapshot) {
                        if (snapshot.hasData) {
                          var houseData = snapshot.data!;
                          return Container(
                            width: MediaQuery.of(context).size.width * 0.8,
                            padding: const EdgeInsets.all(10),
                            decoration: BoxDecoration(
                              color: const Color.fromRGBO(217, 217, 217, 1),
                              borderRadius: BorderRadius.circular(20),
                            ),
                            child: Column(children: <Widget>[
                              Row(
                                mainAxisAlignment: MainAxisAlignment.end,
                                children: <Widget>[
                                  IconButton(
                                    onPressed: () => setState(() {
                                      if (_enabledHouse == false) {
                                        _enabledHouse = true;
                                      } else {
                                        _enabledHouse = false;
                                        house = updateHouse(houseData['id'], houseAreaController.text, houseNbLivingPersonController.text);
                                      }
                                    }),
                                    icon: const Icon(Icons.edit,
                                        color: Colors.black, size: 20),
                                  )
                                ],
                              ),
                              Row(
                                mainAxisAlignment: MainAxisAlignment.center,
                                children: <Widget>[
                                  SizedBox(
                                    width: MediaQuery.of(context).size.width *
                                        0.35,
                                    height: MediaQuery.of(context).size.height *
                                        0.08,
                                    child: Container(
                                      alignment: Alignment.center,
                                      padding: const EdgeInsets.all(10),
                                      decoration: BoxDecoration(
                                        color: const Color.fromRGBO(
                                            193, 190, 190, 1),
                                        borderRadius: BorderRadius.circular(20),
                                      ),
                                      child: Row(children: <Widget>[
                                        Flexible(
                                            child: TextField(
                                              controller: houseNbLivingPersonController,
                                              keyboardType: TextInputType.number,
                                          inputFormatters: <TextInputFormatter>[
                                            FilteringTextInputFormatter
                                                .digitsOnly
                                          ],
                                          textAlign: TextAlign.center,
                                          enabled: _enabledHouse,
                                          cursorHeight: 15,
                                          decoration: InputDecoration(
                                            border: InputBorder.none,
                                            hintText:
                                                "${houseData['nbLivingPerson']}",
                                            hintStyle: const TextStyle(
                                              color: Colors.black,
                                              fontSize: 15,
                                              fontWeight: FontWeight.bold,
                                            ),
                                          ),
                                        )),
                                        const Text(
                                          'personnes',
                                          style: TextStyle(
                                            color: Colors.black,
                                            fontSize: 15,
                                            fontWeight: FontWeight.bold,
                                          ),
                                        ),
                                      ]),
                                    ),
                                  ),
                                  const Spacer(),
                                  SizedBox(
                                      width: MediaQuery.of(context).size.width *
                                          0.35,
                                      height:
                                          MediaQuery.of(context).size.height *
                                              0.08,
                                      child: Container(
                                        padding: const EdgeInsets.all(10),
                                        alignment: Alignment.center,
                                        decoration: BoxDecoration(
                                          color: const Color.fromRGBO(
                                              193, 190, 190, 1),
                                          borderRadius:
                                              BorderRadius.circular(20),
                                        ),
                                        child: Row(children: <Widget>[
                                          Flexible(
                                            child: TextField(
                                              controller: houseAreaController,
                                              keyboardType: TextInputType.number,
                                              inputFormatters: <TextInputFormatter>[
                                                FilteringTextInputFormatter
                                                    .digitsOnly
                                              ],
                                              textAlign: TextAlign.center,
                                              enabled: _enabledHouse,
                                              cursorHeight: 15,
                                              decoration: InputDecoration(
                                                border: InputBorder.none,
                                                hintText:
                                                    "${houseData['area']} ",
                                                hintStyle: const TextStyle(
                                                    color: Colors.black,
                                                    fontSize: 15,
                                                    fontWeight:
                                                        FontWeight.bold),
                                              ),
                                            ),
                                          ),
                                          const Text(
                                            'm²',
                                            style: TextStyle(
                                              color: Colors.black,
                                              fontSize: 15,
                                              fontWeight: FontWeight.bold,
                                            ),
                                          ),
                                        ]),
                                      )),
                                ],
                              ),

                              /*Row(
                    mainAxisAlignment: MainAxisAlignment.center,
                    children: [
                      SizedBox(
                        width:  MediaQuery.of(context).size.width * 0.7,
                        height: MediaQuery.of(context).size.height * 0.08,
                        child: Card(
                          child: Container(
                            alignment: Alignment.center,
                            decoration: BoxDecoration(
                              color: Color.fromRGBO(193, 190, 190, 1),
                              borderRadius: BorderRadius.circular(10),
                            ),
                            child:
                              TextField(
                                textAlign: TextAlign.center,
                                enabled: _enabledHouse,
                                cursorHeight: 15,
                                decoration: const InputDecoration(
                                  border: InputBorder.none,
                                  hintText: 'Isolation de classe G',
                                  hintStyle: TextStyle(
                                    color: Colors.black,
                                    fontSize: 15,
                                    fontWeight: FontWeight.bold
                                  ),
                                ),
                              ),
                            ),),),
                    ],
                  ),  */
                            ]),
                          );
                        } else {
                          return const Center(
                              child: CircularProgressIndicator());
                        }
                      })
                ]);
              } else {
                return const Center(child: CircularProgressIndicator());
              }
            }),
      ),
    );
  }

  Future<Map<String, dynamic>> getHouse() async {
    Map<String, dynamic> user = await getUser();
    final response = await http.get(
        Uri.parse('http://localhost:8080/houses/getById?id=${user['id']}'));
    if (response.statusCode == 200) {
      return jsonDecode(response.body);
    } else {
      throw Exception('Failed to load house');
    }
  }

  Future<Map<String, dynamic>> getUser() async {
    SharedPreferences prefs = await SharedPreferences.getInstance();
    Map<String, dynamic> userMap =
        jsonDecode(prefs.getString('user')!) as Map<String, dynamic>;
    return userMap;
  }

  Future<Map<String, dynamic>> updateHouse(String id, String area, String nbLivingPerson) async {
    final response = await http.post(
        Uri.parse('http://localhost:8080/houses/updateHouse?id=$id&area=$area&nbLivingPerson=$nbLivingPerson'),
    );
    if (response.statusCode == 200) {
      return jsonDecode(response.body);
    }else {
      throw Exception('Failed to update house');
    }
  }

  Future<Map<String, dynamic>> updateUser(String id, String firstName, String lastName, String email) async {
    final response = await http.post(
      Uri.parse('http://localhost:8080/user/update?id=$id&firstName=$firstName&lastName=$lastName&email=$email'),
    );
    if (response.statusCode == 200) {
      final prefs = await SharedPreferences.getInstance();
      prefs.setString('user', response.body);
      return jsonDecode(response.body);
    }else {
      throw Exception('Failed to update house');
    }
  }

}

Widget _buildPopupDialog(BuildContext context) {
  return AlertDialog(
      //give tutoriels to title and center it
      title: const Text('Tutoriel', textAlign: TextAlign.center),
      alignment: Alignment.center,
      content: Container(
          height: MediaQuery.of(context).size.height * 0.2,
          alignment: Alignment.center,
          child: Column(
            mainAxisSize: MainAxisSize.min,
            crossAxisAlignment: CrossAxisAlignment.center,
            children: [
              const Text(
                  "Réinitialiser les tutoriels pour l'ensemble de l'application",
                  textAlign: TextAlign.center),
              Padding(
                padding: const EdgeInsets.only(top: 20),
                child: LiteRollingSwitch(
                  value: true,
                  textOn: 'Yes',
                  textOff: 'No',
                  colorOn: Colors.cyan,
                  colorOff: Colors.red,
                  iconOn: Icons.check,
                  iconOff: Icons.power_settings_new,
                  animationDuration: const Duration(milliseconds: 800),
                  onChanged: (bool state) {
                    print('turned ${(state) ? 'yes' : 'no'}');
                  },

                  //TODO : CHANGE IT
                  onDoubleTap: () {},
                  onTap: () {},
                  onSwipe: () {},
                ),
              ),
            ],
          )));
}
