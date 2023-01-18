import 'package:GreenBoost/authmanager.dart';
import 'package:GreenBoost/loginPage.dart';
import 'package:flutter/material.dart';

import 'homePage.dart';

class SubscriptionPage extends StatefulWidget {
  const SubscriptionPage({super.key});

  @override
  State<StatefulWidget> createState() => _SubscriptionPageState();
}

class _SubscriptionPageState extends State<SubscriptionPage> {
  String email = "";
  String password = "";
  String firstname = "";
  String lastname = "";

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: Color.fromARGB(255, 178, 205, 185),
      appBar: AppBar(
        centerTitle: true,
        title: const Text('Inscription'),
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
            Padding(
              padding: const EdgeInsets.only(
                  left: 15.0, right: 15.0, top: 0, bottom: 0),
              //padding: EdgeInsets.symmetric(horizontal: 15),
              child: TextField(
                decoration: const InputDecoration(
                    border: OutlineInputBorder(
                      borderRadius: BorderRadius.all(
                        Radius.circular(50.0),
                      ),
                      borderSide: BorderSide(
                        width: 5,
                        style: BorderStyle.solid,
                      ),
                    ),
                    labelText: 'Nom',
                    hintText: 'Enter your name'),
                onChanged: (value) => setState(() {
                  lastname = value;
                }),
              ),
            ),
            Padding(
              padding: const EdgeInsets.only(
                  left: 15.0, right: 15.0, top: 10, bottom: 0),
              //padding: EdgeInsets.symmetric(horizontal: 15),
              child: TextField(
                decoration: const InputDecoration(
                    border: OutlineInputBorder(
                      borderRadius: BorderRadius.all(
                        Radius.circular(50.0),
                      ),
                      borderSide: BorderSide(
                        width: 5,
                        style: BorderStyle.solid,
                      ),
                    ),
                    labelText: 'Prénom',
                    hintText: 'Enter your surname'),
                onChanged: (value) => setState(() {
                  firstname = value;
                }),
              ),
            ),
            Padding(
              padding: const EdgeInsets.only(
                  left: 15.0, right: 15.0, top: 10, bottom: 0),
              //padding: EdgeInsets.symmetric(horizontal: 15),
              child: TextField(
                decoration: const InputDecoration(
                    border: OutlineInputBorder(
                      borderRadius: BorderRadius.all(
                        Radius.circular(50.0),
                      ),
                      borderSide: BorderSide(
                        width: 5,
                        style: BorderStyle.solid,
                      ),
                    ),
                    labelText: 'Email',
                    hintText: 'Enter valid email id as abc@gmail.com'),
                onChanged: (value) => setState(() {
                  email = value;
                }),
              ),
            ),
            Padding(
              padding: const EdgeInsets.only(
                  left: 15.0, right: 15.0, top: 10, bottom: 15),
              //padding: EdgeInsets.symmetric(horizontal: 15),
              child: TextField(
                obscureText: true,
                decoration: const InputDecoration(
                    border: OutlineInputBorder(
                      borderRadius: BorderRadius.all(
                        Radius.circular(50.0),
                      ),
                      borderSide: BorderSide(
                        width: 5,
                        style: BorderStyle.solid,
                      ),
                    ),
                    labelText: 'Password',
                    hintText: 'Enter secure password'),
                onChanged: (value) => setState(() {
                  password = value;
                }),
              ),
            ),
            Container(
              height: 50,
              width: 250,
              decoration: BoxDecoration(
                  color: const Color.fromRGBO(48, 69, 178, 1),
                  borderRadius: BorderRadius.circular(30)),
              child: TextButton(
                onPressed: () async {
                  // @Frontender mettre les données que tapes les users dans les vars pour l'appeller ici
                  await AuthManager.of(context)
                      ?.createUser(email, password, firstname, lastname)
                      .then((value) => {
                            if (value)
                              {
                                Navigator.push(
                                    context,
                                    MaterialPageRoute(
                                        builder: (_) => const LoginPage()))
                              }
                            else
                              {
                                //TODO : Faire quelquechose s
                              }
                          });
                },
                child: const Text(
                  'Inscription',
                  style: TextStyle(color: Colors.white, fontSize: 20),
                ),
              ),
            ),
            TextButton(
              onPressed: () {
                Navigator.push(
                  context,
                  MaterialPageRoute(builder: (context) => LoginPage()),
                );
              },
              child: const Text(
                'Déjà un compte? Connectez vous ici',
                style: TextStyle(color: Colors.black, fontSize: 15),
              ),
            ),
          ],
        ),
      ),
    );
  }
}
