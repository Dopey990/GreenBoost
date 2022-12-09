import 'package:GreenBoost/advicesPage.dart';
import 'package:flutter/material.dart';
import 'homePage.dart';
import 'loginPage.dart';

//create main function to run the app
void main() {
  runApp(MyApp());
}

//create a class to build the widget
class MyApp extends StatelessWidget {
  @override
  Widget build(BuildContext context) {
    return MaterialApp(
      title: 'Flutter Demo',
      theme: ThemeData(
        primarySwatch: Colors.blue,
      ),
      home: LoginPage(),
      routes: {
        '/home': (context) => HomePage(),
        '/advices': (context) => AdvicesPage(),
      },
    );
  }
}
