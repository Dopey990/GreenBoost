import 'package:GreenBoost/classementPage.dart';
import 'package:flutter/material.dart';
import '/components/menu.dart';
import 'package:GreenBoost/settingsProfilePage.dart';
import 'package:GreenBoost/auth.dart';

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
            child: TextField(
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
                  title: Text("Titre du sujet 1"),
                  subtitle: Text("Dernier message posté il y a 5 minutes"),
                  onTap: () {
                    // Navigate to the topic page
                  },
                ),
                ListTile(
                  leading: Icon(Icons.question_answer),
                  title: Text("Titre du sujet 2"),
                  subtitle: Text("Dernier message posté il y a 2 heures"),
                  onTap: () {
                    // Navigate to the topic page
                  },
                ),
                ListTile(
                  leading: Icon(Icons.question_answer),
                  title: Text("Titre du sujet 3"),
                  subtitle: Text("Dernier message posté hier"),
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
