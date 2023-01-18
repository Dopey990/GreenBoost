// ignore_for_file: prefer_const_constructors

import 'package:GreenBoost/classementPage.dart';
import 'package:flutter/material.dart';
import '/components/menu.dart';
import 'package:GreenBoost/settingsProfilePage.dart';

class ForumPage extends StatelessWidget {
  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: Text("Forum"),
      ),
      body: Column(
        children: [
          Container(
            padding: const EdgeInsets.all(8.0),
            child: const TextField(
              decoration: InputDecoration(
                hintText: "Rechercher dans le forum...",
              ),
            ),
          ),
          Expanded(
            child: ListView(
              children: [
                ListTile(
                  leading: Icon(Icons.question_answer),
                  title: Text("Nouvelle version de GreenBoost !"),
                  subtitle: Text("Auteur : Greenboost"),
                  onTap: () {
                    showDialog(
                      context: context,
                      builder: (BuildContext context) {
                        return AlertDialog(
                          title: Text("GreenBoost V1 est enfin la"),
                          content: Text(
                              "Page pollution\n Page gaz\n Renforcement structure\n Finalisation du visuel\nAmélioration robustesse \n Association partenaire \n page de challenge"),
                          actions: <Widget>[
                            FloatingActionButton(
                              child: Text("OK"),
                              onPressed: () {
                                Navigator.of(context).pop();
                              },
                            ),
                          ],
                        );
                      },
                    );
                  },
                ),
                ListTile(
                  leading: Icon(Icons.question_answer),
                  title: Text("Comment améliorer ma consomation d'eau ?"),
                  subtitle: Text("Auteur : NaimG"),
                  onTap: () {
                    showDialog(
                      context: context,
                      builder: (BuildContext context) {
                        return AlertDialog(
                          title: Text("Améliorer ma consomation d'eau"),
                          content: Text(
                              "installez des mousseurs sur les robinets et dans le pommeau de douche ; Ils réduisent le débit de 30 % à 50 %, sans perte de confort ni de pression ; privilégiez les douches plutôt que les bains (une douche rapide consomme de 35 à 60 L d'eau quand un bain consommera a minima 150 L)."),
                          actions: <Widget>[
                            FloatingActionButton(
                              child: Text("OK"),
                              onPressed: () {
                                Navigator.of(context).pop();
                              },
                            ),
                          ],
                        );
                      },
                    );
                  },
                ),
                ListTile(
                  leading: Icon(Icons.question_answer),
                  title: Text("Comment améliorer ma consomation de Gaz ?"),
                  subtitle: Text("Auteur : JosuV"),
                  onTap: () {
                    // Navigate to the topic page
                  },
                ),
                ListTile(
                  leading: Icon(Icons.question_answer),
                  title:
                      Text("Comment améliorer ma consomation d'éléctricité ?"),
                  subtitle: Text("Auteur : ThomasR"),
                  onTap: () {
                    // Navigate to the topic page
                  },
                ),
                ListTile(
                  leading: Icon(Icons.question_answer),
                  title: Text(
                      "Laver mes vétements à la main pour économiser de l'eau ?"),
                  subtitle: Text("Auteur : MaximeC"),
                  onTap: () {
                    // Navigate to the topic page
                  },
                ),
                ListTile(
                  leading: Icon(Icons.question_answer),
                  title: Text(
                      "Comment me chauffer au bois à changer ma consomation énergétique ?"),
                  subtitle: Text("Auteur : JulieW"),
                  onTap: () {
                    // Navigate to the topic page
                  },
                ),
              ],
            ),
          ),
        ],
      ),
      floatingActionButton: FloatingActionButton(
        child: Icon(Icons.add),
        onPressed: () {
          showDialog(
            context: context,
            builder: (BuildContext context) {
              return AlertDialog(
                title: Text("Ajouter un sujet"),
                content: Text(
                    "Seul l'équipe Greenboost peut actuellement créer des sujets"),
                actions: <Widget>[
                  FloatingActionButton(
                    child: Text("OK"),
                    onPressed: () {
                      Navigator.of(context).pop();
                    },
                  ),
                ],
              );
            },
          );
        },
      ),
    );
  }
}
