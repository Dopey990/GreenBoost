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
            Text('Développeurs:',
                style: TextStyle(fontWeight: FontWeight.bold)),
            Text('thomas.ribault@gmail.com'),
            Text('julie.watier@gmail.com'),
            Text('bobin.dautel@gmail.com'),
            Text('Buisness contact :',
                style: TextStyle(fontWeight: FontWeight.bold)),
            Text('naim.gallouj@gmail.com'),
            Text('josue.vidrequin@gmail.com'),
            Text('maxime.consigne@gmail.com'),
            SizedBox(height: 16.0),
            Text('Associations partenaires:',
                style: TextStyle(fontWeight: FontWeight.bold)),
            Image.asset('assets/home/eau-vert.png',
                height: MediaQuery.of(context).size.width / 6,
                width: MediaQuery.of(context).size.width / 6),
            Center(
              child: Text(
                "La goute de gingembre est une associattion aidant les personnes manquant d'eau",
              ),
            ),
          ],
        ),
      ),
    );
  }
}
