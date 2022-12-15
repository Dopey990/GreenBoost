import 'package:flutter/material.dart';
import '/components/menu.dart';

class SettingsProfilePage extends StatelessWidget {
  @override
  Widget build(BuildContext context){
    return Scaffold(
      appBar: AppBar(
        centerTitle: true,
        title: Text('Settings Profile'),
      ),
      drawer: Menu(),
      body: SingleChildScrollView(
        child : Column (          
          children: <Widget>[
            Row(
              //icon of settings on the right
              mainAxisAlignment: MainAxisAlignment.center,
              children: const <Widget>[
                Icon(
                  Icons.person,
                  color: Colors.blue,
                  size: 40,
                ),
              ],
            ),
          ]


      ),
    ),);
  }
}

