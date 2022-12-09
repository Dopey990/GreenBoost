//create profil page
import 'package:flutter/material.dart';
import '/components/menu.dart';

class ProfilPage extends StatelessWidget {
  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        centerTitle: true,
        title: Text('Profil'),
      ),
      drawer: Menu(),
      body: Center(
        child: Text('Profil Page'),
      ),
    );
  }
}