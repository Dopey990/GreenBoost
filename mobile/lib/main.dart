import 'package:GreenBoost/subscriptionPage.dart';
import 'package:GreenBoost/advicesPage.dart';
import 'package:flutter/material.dart';
import 'electricityInfoPage.dart';
import 'homePage.dart';
import 'loginPage.dart';

Map<int, Color> color = const {
  50: Color.fromRGBO(61, 105, 217, .1),
  100: Color.fromRGBO(61, 105, 217, .2),
  200: Color.fromRGBO(61, 105, 217, .3),
  300: Color.fromRGBO(61, 105, 217, .4),
  400: Color.fromRGBO(61, 105, 217, .5),
  500: Color.fromRGBO(61, 105, 217, .6),
  600: Color.fromRGBO(61, 105, 217, .7),
  700: Color.fromRGBO(61, 105, 217, .8),
  800: Color.fromRGBO(61, 105, 217, .9),
  900: Color.fromRGBO(61, 105, 217, 1),
};

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
        primarySwatch: MaterialColor(0xFF3D69D9, color),
      ),
      home: SubscriptionPage(),
      routes: {
        "/home": (context) => HomePage(),
        "/advices": (context) => AdvicesPage(),
        "/info/electricity": (context) => ElectricityInfoPage(),
      },
    );
  }
}
