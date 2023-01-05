import 'package:GreenBoost/subscriptionPage.dart';
import 'package:GreenBoost/advicesPage.dart';
import 'package:flutter/material.dart';
import 'electricityInfoPage.dart';
import 'homePage.dart';
import 'loginPage.dart';

class Auth extends StatefulWidget {
  @override
  _AuthState createState() => _AuthState();
}

class _AuthState extends State<Auth> {
  bool _showSignUp = true;

  void _toggleShowSignUp() {
    setState(() {
      _showSignUp = !_showSignUp;
    });
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: Text("Temp's auth"),
        elevation: 16.0,
        actions: [
          IconButton(
            icon: Icon(Icons.swap_horiz),
            onPressed: _toggleShowSignUp,
          )
        ],
      ),
      body: Container(
        child: _showSignUp ? LoginPage() : LoginPage(),
      ),
    );
  }
}
