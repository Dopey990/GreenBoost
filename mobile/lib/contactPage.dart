import 'dart:convert';

import 'package:GreenBoost/profilePage.dart';
import 'package:flutter/material.dart';
import 'package:shared_preferences/shared_preferences.dart';
import '/components/menu.dart';
import 'package:http/http.dart' as http;

import 'package:flutter/material.dart';
import 'package:flutter/services.dart';

import 'package:flutter/material.dart';

class ContactPage extends StatelessWidget {
  @override
  Widget build(BuildContext context) {
    return Scaffold(
        backgroundColor: Color.fromARGB(255, 178, 205, 185),
        appBar: AppBar(
          title: Text("Contactez nous"),
        ),
        body: SingleChildScrollView(
            child: Column(
          children: <Widget>[
            Padding(
              padding: const EdgeInsets.only(top: 1.0),
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
              padding: EdgeInsets.all(20.0),
              child: Column(
                mainAxisAlignment: MainAxisAlignment.center,
                children: <Widget>[
                  TextField(
                    decoration: InputDecoration(
                      labelText: "Email",
                      border: OutlineInputBorder(
                        borderRadius: BorderRadius.all(Radius.circular(5.0)),
                      ),
                    ),
                  ),
                  SizedBox(
                    height: 20.0,
                  ),
                  TextField(
                    decoration: InputDecoration(
                      labelText: "Objet",
                      border: OutlineInputBorder(
                        borderRadius: BorderRadius.all(Radius.circular(5.0)),
                      ),
                    ),
                  ),
                  SizedBox(height: 20),
                  Container(
                    height: 70.0,
                    child: TextField(
                      decoration: InputDecoration(
                        labelText: "Message",
                        border: OutlineInputBorder(
                          borderRadius: BorderRadius.all(Radius.circular(5.0)),
                        ),
                      ),
                      maxLines: null,
                      keyboardType: TextInputType.multiline,
                    ),
                  ),
                  SizedBox(
                    height: 40.0,
                  ),
                  Container(
                    height: 50,
                    width: 250,
                    decoration: BoxDecoration(
                        color: const Color.fromRGBO(48, 69, 178, 1),
                        borderRadius: BorderRadius.circular(30)),
                    child: TextButton(
                      onPressed: () {
                        //Ajoutez ici les actions à effectuer lorsque le bouton est pressé
                      },
                      child: const Text(
                        'Envoyer',
                        style: TextStyle(color: Colors.white, fontSize: 20),
                      ),
                    ),
                  ),
                  SizedBox(height: 20.0),
                  Row(
                    mainAxisAlignment: MainAxisAlignment.center,
                    children: <Widget>[
                      //J'ai eu des couilles avec mes assets, toute les images sont dans le dossier assets/social/
                      Image.network(
                        "https://www.unipile.com/wp-content/uploads/2022/06/logo-facebook.png",
                        height: 40,
                        width: 40,
                      ),
                      SizedBox(
                        width: 10,
                      ),
                      Image.network(
                        "https://upload.wikimedia.org/wikipedia/commons/thumb/a/a5/Instagram_icon.png/640px-Instagram_icon.png",
                        height: 40,
                        width: 40,
                      ),
                      SizedBox(
                        width: 10,
                      ),
                      Image.network(
                        "https://png.pngtree.com/png-vector/20221018/ourmid/pngtree-whatsapp-mobile-software-icon-png-image_6315991.png",
                        height: 40,
                        width: 40,
                      ),
                      SizedBox(
                        width: 10,
                      ),
                      Image.network(
                        "https://upload.wikimedia.org/wikipedia/commons/4/4e/Gmail_Icon.png",
                        height: 40,
                        width: 40,
                      ),
                    ],
                  ),
                ],
              ),
            ),
          ],
        )));
  }
}
