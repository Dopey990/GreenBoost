import 'package:flutter/material.dart';

import 'components/menu.dart';

class HomePage extends StatelessWidget {
  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        centerTitle: true,
        title: Text('Home'),
      ),
      drawer: Menu(),
      body: Center(
        child: Text('Home Page'),
      ),
    );
  }
}