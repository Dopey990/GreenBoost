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
                    // Navigate to the topic page
                  },
                ),
                ListTile(
                  leading: Icon(Icons.question_answer),
                  title: Text("Comment améliorer ma consomation d'eau ?"),
                  subtitle: Text("Auteur : NaimG"),
                  onTap: () {
                    // Navigate to the topic page
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
          // Navigate to the create topic page
        },
      ),
    );
  }
}
