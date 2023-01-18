import 'package:GreenBoost/loginPage.dart';
import 'package:flutter/material.dart';

import 'homePage.dart';

class droitOubli extends StatelessWidget {
  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: Color.fromARGB(255, 177, 201, 183),
      appBar: AppBar(
        backgroundColor: Color.fromARGB(255, 78, 129, 91),
        centerTitle: true,
        title: const Text("Droit à l'oubli"),
      ),
      body: SingleChildScrollView(
        child: Column(
          children: <Widget>[
            Padding(
              padding: const EdgeInsets.only(top: 1.0),
              child: Center(
                child: Image.asset(
                  'assets/img/greenboost-full-logo.png',
                  height: 200,
                  width: 300,
                  fit: BoxFit.fitWidth,
                ),
              ),
            ),
            const Padding(
              padding: EdgeInsets.only(top: 10.0),
              child: Text(
                "Faire valoir mon droit à l'oubli",
                style: TextStyle(
                  fontSize: 20,
                  fontWeight: FontWeight.bold,
                ),
              ),
            ),
            const Padding(
              padding:
                  EdgeInsets.only(left: 15.0, right: 15.0, top: 15, bottom: 15),
              child: TextField(
                decoration: InputDecoration(
                    border: OutlineInputBorder(),
                    labelText: 'Email',
                    hintText: 'Enter valid email id as abc@gmail.com'),
              ),
            ),
            const Padding(
              padding:
                  EdgeInsets.only(left: 15.0, right: 15.0, top: 15, bottom: 15),
              child: TextField(
                decoration: InputDecoration(
                    border: OutlineInputBorder(),
                    labelText: 'Pour quelle raison ?',
                    hintText: 'Ce champs est optionnel'),
              ),
            ),
            Container(
              height: 50,
              width: 250,
              decoration: BoxDecoration(
                color: Colors.green,
                borderRadius: BorderRadius.circular(20),
              ),
              child: TextButton(
                onPressed: () {
                  showDialog(
                    context: context,
                    builder: (BuildContext context) {
                      return AlertDialog(
                        title: Text("Droit à l'oubli"),
                        content: Text(
                            "Votre demande à bien été prise en compte, elle sera traité sous 30 jours"),
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
                child: const Text(
                  'Envoyer',
                  style: TextStyle(
                    color: Colors.white,
                    fontSize: 20,
                  ),
                ),
              ),
            ),
          ],
        ),
      ),
    );
  }
}
