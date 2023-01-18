import 'dart:convert';

import 'package:GreenBoost/profilePage.dart';
import 'package:flutter/material.dart';
import 'package:shared_preferences/shared_preferences.dart';
import '/components/menu.dart';
import 'package:http/http.dart' as http;

class CreditPage extends StatelessWidget {
  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: Text('Page de crédit'),
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
            Text('Equipe technique :',
                style: TextStyle(fontWeight: FontWeight.bold)),
            Text('thomas.ribault@gmail.com'),
            Text('julie.watier@gmail.com'),
            Text('bobin.dautel@gmail.com'),
            Text('Equipe spécialiste :',
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
                "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.",
              ),
            ),
          ],
        ),
      ),
    );
  }
}
