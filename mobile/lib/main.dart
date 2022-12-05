//create home page 
import 'package:flutter/material.dart';
import 'homePage.dart';

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
      home: HomePage(),
      routes: {
        '/home': (context) => HomePage(),
        //'/about': (context) => AboutPage(),
        //'/contact': (context) => ContactPage(),
      },
    );
  }
}