import 'package:flutter/material.dart';

import 'components/menu.dart';

class AdvicesPage extends StatelessWidget {
  @override
  Widget build(BuildContext context) {
    return Scaffold(
        appBar: AppBar(
          centerTitle: true,
          title: Text('Home'),
        ),
        drawer: Menu(),
        body: Center(child: Text("Advices")));
  }
}
