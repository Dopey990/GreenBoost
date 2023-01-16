import 'dart:convert';

import 'package:GreenBoost/profilePage.dart';
import 'package:flutter/material.dart';
import 'package:shared_preferences/shared_preferences.dart';
import '/components/menu.dart';
import 'package:http/http.dart' as http;

class ContactPage extends StatelessWidget {
  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: Text('Contact'),
      ),
      body: Padding(
        padding: const EdgeInsets.all(16.0),
        child: Column(
          children: <Widget>[
            Text('Site web:'),
            InkWell(
              child: Text('www.greenbost.fr',
                  style: TextStyle(color: Colors.blue)),
              onTap: () {
                //launch('');
              },
            ),
            SizedBox(height: 16.0),
            Text('Développeurs:'),
            Text('thomas.ribaul@gmail.com'),
            Text('julie.watier@gmail.com'),
            Text('bobin.dautel@gmail.com'),
            Text('naim.gallouj@gmail.com'),
            Text('josue.pro@gmail.com'),
            Text('maxime.cy@gmail.com'),
            SizedBox(height: 16.0),
            Text('Associations partenaires:'),
            Image.asset('assets/images/association1.jpg'),
            Text('Association 1'),
            Image.asset('assets/images/association2.jpg'),
            Text('Association 2'),
          ],
        ),
      ),
    );
  }
}
