import 'dart:convert';
import 'dart:math';

import 'package:GreenBoost/classementPage.dart';
import 'package:GreenBoost/components/popUp.dart';
import 'package:flutter/material.dart';
import 'package:shared_preferences/shared_preferences.dart';
import '/components/menu.dart';
import 'package:flutter/material.dart';

class Rgpd extends StatelessWidget {
  @override
  Widget build(BuildContext context) {
    return Scaffold(
      body: Center(
        child: Column(
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            Text(
              'Politique de confidentialité',
              style: TextStyle(
                fontSize: 24,
                fontWeight: FontWeight.bold,
              ),
            ),
            SizedBox(height: 24),
            Expanded(
              child: Container(
                width: 0.8 * MediaQuery.of(context).size.width,
                child: Text(
                  "Le site web GreenBoost est détenu par GreenBoost, qui est un contrôleur de données de vos données personnelles. Nous avons adopté cette politique de confidentialité, qui détermine la manière dont nous traitons les informations collectées par GreenBoost, qui fournit également les raisons pour lesquelles nous devons collecter certaines données personnelles vous concernant. Par conséquent, vous devez lire cette politique de confidentialité avant d'utiliser le site web de GreenBoost.",
                  style: TextStyle(
                    fontSize: 18,
                    fontFamily: 'Open Sans',
                  ),
                  textAlign: TextAlign.center,
                ),
              ),
            ),
          ],
        ),
      ),
    );
  }
}
