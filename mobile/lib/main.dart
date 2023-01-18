import 'package:GreenBoost/contactPage.dart';
import 'package:GreenBoost/eauInfoPage.dart';
import 'package:GreenBoost/subscriptionPage.dart';
import 'package:GreenBoost/advicesMenuPage.dart';
import 'package:flutter/material.dart';
import 'authmanager.dart';
import 'challengePage.dart';
import 'creditPage.dart';
import 'electricityInfoPage.dart';
import 'homePage.dart';
import 'loginPage.dart';
import 'forumPage.dart';

Map<int, Color> color = const {
  50: Color.fromARGB(24, 61, 217, 103),
  100: Color.fromARGB(51, 61, 217, 87),
  200: Color.fromARGB(75, 61, 217, 136),
  300: Color.fromARGB(102, 61, 217, 105),
  400: Color.fromARGB(126, 61, 217, 118),
  500: Color.fromARGB(153, 61, 217, 134),
  600: Color.fromARGB(199, 106, 161, 98),
  700: Color.fromARGB(204, 48, 174, 92),
  800: Color.fromARGB(227, 55, 134, 75),
  900: Color.fromARGB(255, 23, 124, 58),
};

//create main function to run the app
void main() {
  runApp(MyApp());
}

//create a class to build the widget
class MyApp extends StatelessWidget {
  @override
  Widget build(BuildContext context) {
    return AuthManager(
        child: MaterialApp(
      title: 'Flutter Demo',
      theme: ThemeData(
        primarySwatch: MaterialColor(0xFF3D69D9, color),
      ),
      home: SubscriptionPage(),
      routes: {
        "/home": (context) => HomePage(),
        "/advices": (context) => AdvicesMenuPage(),
        "/login": (context) => LoginPage(),
        "/subscription": (context) => SubscriptionPage(),
        "/challenges": (context) => ChallengePage(),
        "/forum": (context) => ForumPage(),
        "/contact": (context) => ContactPage(),
        "/info/electricity": (context) => ElectricityInfoPage(),
        "/info/water": (context) => EauInfoPage(),
        "/partenaires": (context) => CreditPage(),
      },
    ));
  }
}
