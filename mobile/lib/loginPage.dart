import 'package:GreenBoost/forgotPasswordPage.dart';
import 'package:GreenBoost/subscriptionPage.dart';
import 'package:flutter/material.dart';
import 'dart:convert';
import 'dart:io';
import 'package:http/http.dart' as http;
import 'package:shared_preferences/shared_preferences.dart';
import 'authmanager.dart';
import 'homePage.dart';

// remplacer API_URL par l'URL de votre API
/** 
final email = 'EMAIL';
final password = 'PASSWORD';

void login() async {
  final body = jsonEncode({'email': email, 'password': password});
  const API_URL = 'https://my-api.com/login';
  final response = await http.post(API_URL, body: body);
  final responseBody = jsonDecode(response.body);
  // traitez la réponse ici
}**/

class LoginPage extends StatefulWidget {
  const LoginPage({super.key});

  @override
  State<StatefulWidget> createState() => _LoginPageState();
}

class _LoginPageState extends State<LoginPage> {
  String email = "";
  String password = "";

  @override
  Widget build(BuildContext context) {
    AuthManager? auth = AuthManager.of(context);

    return Scaffold(
         backgroundColor: Color.fromARGB(255, 177, 201, 183),
      appBar: AppBar(
        backgroundColor: Color.fromARGB(255, 78, 129, 91),
        title: const Text("Login Page"),
      ),
      body: SingleChildScrollView(
        child: Column(
          children: <Widget>[
            Padding(
              padding: const EdgeInsets.only(top: 60.0),
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
              //padding: const EdgeInsets.only(left:15.0,right: 15.0,top:0,bottom: 0),
              padding: const EdgeInsets.symmetric(horizontal: 15),
              child: TextField(
                decoration: const InputDecoration(
                    border: OutlineInputBorder(),
                    labelText: 'Email',
                    hintText: 'Enter valid email id as abc@gmail.com'),
                onChanged: (value) => setState(() {
                  email = value;
                }),
              ),
            ),
            Padding(
              padding: const EdgeInsets.only(
                  left: 15.0, right: 15.0, top: 15, bottom: 0),
              //padding: EdgeInsets.symmetric(horizontal: 15),
              child: TextField(
                obscureText: true,
                decoration: const InputDecoration(
                    border: OutlineInputBorder(),
                    labelText: 'Password',
                    hintText: 'Enter secure password'),
                onChanged: (value) => setState(() {
                  password = value;
                }),
              ),
            ),
            //text button for inscription
            TextButton(
              onPressed: () {
                Navigator.push(context,
                    MaterialPageRoute(builder: (_) => SubscriptionPage()));
              },
              child: const Text(
                'Pas encore de compte? inscrivez-vous ici',
                style: TextStyle(color:Color.fromARGB(255, 43, 127, 65), fontSize: 15),
              ),
            ),

            TextButton(
              onPressed: () {
                Navigator.push(context,
                    MaterialPageRoute(builder: (_) => ForgotPasswordPage()));
              },
              child: const Text(
                'Mot de passe oublié? cliquez ici',
                style: TextStyle(color: Color.fromARGB(255, 43, 127, 65), fontSize: 15),
              ),
            ),
            Container(
              height: 50,
              width: 250,
              decoration: BoxDecoration(
                  color: Color.fromARGB(255, 31, 108, 71), borderRadius: BorderRadius.circular(20)),
              child: TextButton(
                onPressed: () async {
                  bool? canConnect =
                      await AuthManager.of(context)?.login(email, password);

                  if (canConnect == true) {
                    final prefs = await SharedPreferences.getInstance();

                    final response = await http.post(Uri.parse(
                        'http://localhost:8080/user/getUserByToken?token=${AuthManager.of(context)!.token}'));

                    if (response.statusCode == 200) {
                      print("ok");
                      print(response.body);
                      await prefs.setString('user', response.body);
                    }

                    Navigator.push(
                        context, MaterialPageRoute(builder: (_) => HomePage()));
                  } else {
                    //TODO : Faire quelquechose s
                  }
                  /*.then((value) async => {
                            if (value) {
                                final prefs = await SharedPreferences.getInstance();
                                await prefs.setString('user', jsonEncode(user));

                                Navigator.push(
                                    context,
                                    MaterialPageRoute(
                                        builder: (_) => HomePage()))
                              }
                            else
                              {
                                //TODO : Faire quelquechose s
                              }
                          });*/
                },
                child: const Text(
                  'Login',
                  style: TextStyle(color: Colors.white, fontSize: 25),
                ),
              ),
            ),
          ],
        ),
      ),
    );
  }
}
