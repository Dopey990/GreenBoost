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
  late Future<Set<String>> categories;
  String? categoriesDropdownValue;

  @override
  void initState() {
    categories = getCategories();
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
          TextButton.icon(
              icon: const Icon(Icons.add_task_rounded),
              label: const Text("Ajouter une activité"),
              onPressed: () => {
                    showDialog(
                        context: context,
                        builder: (BuildContext context) {
                          return AlertDialog(
                            content: Stack(
                              children: <Widget>[
                                Positioned(
                                  right: -40.0,
                                  top: -40.0,
                                  child: InkResponse(
                                    onTap: () {
                                      Navigator.of(context).pop();
                                    },
                                    child: const CircleAvatar(
                                      backgroundColor: Colors.red,
                                      child: Icon(Icons.close),
                                    ),
                                  ),
                                ),
                                Form(
                                  key: UniqueKey(),
                                  child: Column(
                                    mainAxisSize: MainAxisSize.min,
                                    children: <Widget>[
                                      Padding(
                                          padding: const EdgeInsets.all(8.0),
                                          child: FutureBuilder<Set<String>>(
                                              future: categories,
                                              builder: (BuildContext context,
                                                  AsyncSnapshot<Set<String>>
                                                      snapshot) {
                                                if (snapshot.hasData) {
                                                  var data = snapshot.data!;

                                                  return Container(
                                                    decoration:
                                                        const BoxDecoration(
                                                      color: Colors.white,
                                                    ),
                                                    child: DropdownButton(
                                                      value:
                                                          categoriesDropdownValue,
                                                      hint: const Text(
                                                          "Catégorie"),
                                                      icon: const Icon(Icons
                                                          .keyboard_arrow_down),
                                                      items: data.map(
                                                          (String jsonTown) {
                                                        return DropdownMenuItem(
                                                          value: jsonTown,
                                                          child: Text(jsonTown),
                                                        );
                                                      }).toList(),
                                                      onChanged:
                                                          (String? newValue) {
                                                        setState(() {
                                                          categoriesDropdownValue =
                                                              newValue!;
                                                        });
                                                      },
                                                    ),
                                                  );
                                                } else {
                                                  return const CircularProgressIndicator();
                                                }
                                              })),
                                      Padding(
                                        padding: const EdgeInsets.all(8.0),
                                        child: TextFormField(
                                          decoration: const InputDecoration(
                                            labelText: 'Nom du produit',
                                            labelStyle: TextStyle(
                                                color: Colors.black87,
                                                fontSize: 17,
                                                fontFamily: 'AvenirLight'),
                                            focusedBorder: UnderlineInputBorder(
                                              borderSide: BorderSide(
                                                  color: Colors.purple),
                                            ),
                                            enabledBorder: UnderlineInputBorder(
                                                borderSide: BorderSide(
                                                    color: Colors.grey,
                                                    width: 1.0)),
                                          ),
                                          style: const TextStyle(
                                              color: Colors.black87,
                                              fontSize: 17,
                                              fontFamily: 'AvenirLight'),
                                          //  controller: _passwordController,
                                          obscureText: true,
                                        ),
                                      ),
                                      Center(
                                        child: FutureBuilder<
                                            List<Map<String, dynamic>>>(
                                          future: getProducts(),
                                          builder: (context, snapshot) {
                                            if (categoriesDropdownValue !=
                                                    null &&
                                                snapshot.hasData) {
                                              return ListView.builder(
                                                shrinkWrap: true,
                                                itemCount:
                                                    snapshot.data!.length,
                                                itemBuilder: (context, index) {
                                                  return ListTile(
                                                    leading: CircleAvatar(
                                                      child: Image.network(snapshot
                                                          .data![index][
                                                              "calculatedEnergyClass"]
                                                          .toString()),
                                                    ),
                                                    title: Text(snapshot
                                                        .data![index]["name"]
                                                        .toString()),
                                                    trailing: Text(DateTime
                                                            .fromMillisecondsSinceEpoch(
                                                                snapshot.data![
                                                                            index]
                                                                        [
                                                                        "onMarketStartDateTS"] *
                                                                    1000)
                                                        .toString()),
                                                    subtitle: Text(snapshot
                                                        .data![index]["noise"]
                                                        .toString()),
                                                  );
                                                },
                                              );
                                            }
                                            return const Text(
                                                "Veuillez sélectionner une catégorie");
                                          },
                                        ),
                                      ),
                                      Padding(
                                        padding: const EdgeInsets.all(8.0),
                                        child: Row(children: [
                                          TextButton(
                                            child: const Text("Annuler"),
                                            onPressed: () {},
                                          ),
                                          const Spacer(),
                                          TextButton(
                                            child: const Text("Ajouter"),
                                            onPressed: () {},
                                          )
                                        ]),
                                      )
                                    ],
                                  ),
                                ),
                              ],
                            ),
                          );
                        })
                  }),
          TextButton.icon(
              icon: const Icon(Icons.add_home_rounded),
              label: const Text("Ajouter un appareil"),
              onPressed: () => {}),
        ]),
      ),
    );
  }

  Future<Set<String>> getCategories() async {
    final response = await http
        .get(Uri.parse('http://localhost:8080/products/getCategories'));
    Set<String> result = {};

    if (response.statusCode == 200) {
      jsonDecode(response.body).forEach((category) {
        result.add(category);
      });
      return result;
    } else {
      throw Exception('Failed to load categories');
    }
  }

  Future<List<Map<String, dynamic>>> getProducts() async {
    final response = await http.get(Uri.parse(
        'http://localhost:8080/products/getProducts?category=${categoriesDropdownValue!}'));
    List<Map<String, dynamic>> result = [];

    if (response.statusCode == 200) {
      jsonDecode(response.body).forEach((product) {
        result.add(product);
      });
      return result;
    } else {
      throw Exception(
          'Failed to load products with category: ${categoriesDropdownValue!}');
    }
  }
}
