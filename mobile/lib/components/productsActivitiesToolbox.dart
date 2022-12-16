import 'dart:convert';

import 'package:flutter/material.dart';
import 'package:flutter_titled_container/flutter_titled_container.dart';

import 'package:http/http.dart' as http;

class ProductsActivitiesToolboxWidget extends StatefulWidget {
  ProductsActivitiesToolboxWidget({super.key});

  @override
  State<StatefulWidget> createState() => ProductsActivitiesToolboxState();
}

class ProductsActivitiesToolboxState
    extends State<ProductsActivitiesToolboxWidget> {
  @override
  void initState() {
    super.initState();
  }

  @override
  Widget build(BuildContext context) {
    return Padding(
      padding: const EdgeInsets.all(32.0),
      child: Container(
        width: MediaQuery.of(context).size.width * 0.95,
        height: 60.0,
        decoration: const BoxDecoration(
          color: Colors.blue,
          shape: BoxShape.rectangle,
          borderRadius: BorderRadius.all(
            Radius.circular(8.0),
          ),
        ),
        child: Row(children: [
          IconButton(
              onPressed: () => {}, icon: const Icon(Icons.add_task_rounded)),
          const Text("Ajouter une activité"),
          IconButton(
              onPressed: () => {}, icon: const Icon(Icons.add_home_rounded)),
          const Text("Ajouter un appareil"),
        ]),
      ),
    );
  }
}
