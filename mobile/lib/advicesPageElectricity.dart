import 'package:GreenBoost/classementPage.dart';
import 'package:flutter/material.dart';
import '/components/menu.dart';
import 'package:GreenBoost/settingsProfilePage.dart';

class advicesPageElectricity extends StatelessWidget {
  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: const Color.fromRGBO(168, 203, 208, 1),
      appBar: AppBar(
        centerTitle: true,
        title: Text('Conseils sur l\'électricité'),
      ),
      drawer: Menu(),
      body: SingleChildScrollView(
        child: Column(
            mainAxisAlignment: MainAxisAlignment.center,
            children: <Widget>[
              Padding(
                padding: EdgeInsets.only(top: 30, bottom: 30),
                child: Text(
                  "Liste des conseils",
                  style: TextStyle(
                      color: Color.fromRGBO(31, 120, 180, 1),
                      fontSize: 25,
                      fontWeight: FontWeight.bold),
                ),
              ),
              ListView(
                shrinkWrap: true,
                padding: const EdgeInsets.all(10),
                children: <Widget>[
                  Card(
                    borderOnForeground: true,
                    child: Column(
                      children: [
                        Container(
                          padding: EdgeInsets.all(16),
                          alignment: Alignment.center,
                          child: Text(
                            "Changer les éclairages classiques par de l\'éclairage LED, sur l\'emballage de l\'éclairage une étiquette énergétique indique la consommation de celle-ci. Il est également conseillé de privilégier l\'éclairage naturel en pleine journée.",
                            style: TextStyle(
                                fontWeight: FontWeight.bold, fontSize: 17),
                          ),
                        )
                      ],
                    ),
                  ),
                  Card(
                    borderOnForeground: true,
                    child: Column(
                      children: [
                        Container(
                          padding: EdgeInsets.all(16),
                          alignment: Alignment.center,
                          child: Text(
                            "coucou",
                            style: TextStyle(fontWeight: FontWeight.bold),
                          ),
                        )
                      ],
                    ),
                  ),
                  Card(
                    borderOnForeground: true,
                    child: Column(
                      children: [
                        Container(
                          padding: EdgeInsets.all(16),
                          alignment: Alignment.center,
                          child: Text(
                            "coucou",
                            style: TextStyle(fontWeight: FontWeight.bold),
                          ),
                        )
                      ],
                    ),
                  ),
                  Card(
                    borderOnForeground: true,
                    child: Column(
                      children: [
                        Container(
                          padding: EdgeInsets.all(16),
                          alignment: Alignment.center,
                          child: Text(
                            "coucou",
                            style: TextStyle(fontWeight: FontWeight.bold),
                          ),
                        )
                      ],
                    ),
                  ),
                  Card(
                    borderOnForeground: true,
                    child: Column(
                      children: [
                        Container(
                          padding: EdgeInsets.all(16),
                          alignment: Alignment.center,
                          child: Text(
                            "coucou",
                            style: TextStyle(fontWeight: FontWeight.bold),
                          ),
                        )
                      ],
                    ),
                  ),
                ],
              ),
            ]),
      ),
    );
  }
}
