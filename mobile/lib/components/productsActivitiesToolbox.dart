import 'dart:convert';

import 'package:flutter/material.dart';
import 'package:flutter/services.dart';
import 'package:flutter_titled_container/flutter_titled_container.dart';

import 'package:http/http.dart' as http;
import 'package:shared_preferences/shared_preferences.dart';

class ProductsActivitiesToolboxWidget extends StatefulWidget {
  ProductsActivitiesToolboxWidget({super.key});

  @override
  State<StatefulWidget> createState() => ProductsActivitiesToolboxState();
}

class ProductsActivitiesToolboxState
    extends State<ProductsActivitiesToolboxWidget> {
  late Future<Set<String>> categories;
  List<Map<String, dynamic>>? shownProducts;
  String? categoriesDropdownValue;
  String? selectedProductId;

  final productNameController = TextEditingController();
  final productBrandController = TextEditingController();
  final productQuantityController = TextEditingController();

  @override
  void initState() {
    productQuantityController.text = '1';
    categories = getCategories();
    super.initState();
  }

  @override
  Widget build(BuildContext context) {
    return Padding(
      padding: const EdgeInsets.all(32.0),
      child: Container(
        width: MediaQuery.of(context).size.width * 0.85,
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
              label: const Text(
                "Ajouter une activité",
                style: TextStyle(fontSize: 12),
              ),
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
                                  child: Column(
                                    mainAxisSize: MainAxisSize.min,
                                    children: <Widget>[
                                      Padding(
                                        padding: const EdgeInsets.all(8.0),
                                        child: TextFormField(
                                            controller: productNameController,
                                            decoration: const InputDecoration(
                                              labelText: 'Nom du produit',
                                              labelStyle: TextStyle(
                                                  color: Colors.black87,
                                                  fontSize: 17,
                                                  fontFamily: 'AvenirLight'),
                                              focusedBorder:
                                                  UnderlineInputBorder(
                                                borderSide: BorderSide(
                                                    color: Colors.purple),
                                              ),
                                              enabledBorder:
                                                  UnderlineInputBorder(
                                                      borderSide: BorderSide(
                                                          color: Colors.grey,
                                                          width: 1.0)),
                                            ),
                                            style: const TextStyle(
                                                color: Colors.black87,
                                                fontSize: 17,
                                                fontFamily: 'AvenirLight')),
                                      ),
                                      Padding(
                                        padding: const EdgeInsets.all(8.0),
                                        child: TextFormField(
                                            controller: productBrandController,
                                            decoration: const InputDecoration(
                                              labelText: 'Marque',
                                              labelStyle: TextStyle(
                                                  color: Colors.black87,
                                                  fontSize: 17,
                                                  fontFamily: 'AvenirLight'),
                                              focusedBorder:
                                                  UnderlineInputBorder(
                                                borderSide: BorderSide(
                                                    color: Colors.purple),
                                              ),
                                              enabledBorder:
                                                  UnderlineInputBorder(
                                                      borderSide: BorderSide(
                                                          color: Colors.grey,
                                                          width: 1.0)),
                                            ),
                                            style: const TextStyle(
                                                color: Colors.black87,
                                                fontSize: 17,
                                                fontFamily: 'AvenirLight')),
                                      ),
                                      TextFormField(
                                          controller: productQuantityController,
                                          keyboardType: TextInputType.number,
                                          inputFormatters: [
                                            FilteringTextInputFormatter
                                                .digitsOnly
                                          ],
                                          decoration: const InputDecoration(
                                              labelText: "Quantité",
                                              icon: Icon(Icons.numbers))),
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
                                                      child: StatefulBuilder(
                                                          builder: (BuildContext
                                                                  context,
                                                              StateSetter
                                                                  dropDownState) {
                                                        return Column(
                                                            children: [
                                                              DropdownButton(
                                                                value:
                                                                    categoriesDropdownValue,
                                                                hint: const Text(
                                                                    "Catégorie"),
                                                                icon: const Icon(
                                                                    Icons
                                                                        .keyboard_arrow_down),
                                                                items: data.map(
                                                                    (String
                                                                        product) {
                                                                  return DropdownMenuItem(
                                                                    value:
                                                                        product,
                                                                    child: Text(
                                                                        product),
                                                                  );
                                                                }).toList(),
                                                                onChanged: (String?
                                                                    newValue) async {
                                                                  shownProducts =
                                                                      await getProducts(
                                                                          newValue!);
                                                                  dropDownState(
                                                                      () {
                                                                    categoriesDropdownValue =
                                                                        newValue;
                                                                  });
                                                                },
                                                              ),
                                                              SizedBox(
                                                                width: 300,
                                                                height: 200,
                                                                child: shownProducts ==
                                                                        null
                                                                    ? const Text(
                                                                        "Veuillez sélectionner une catégorie")
                                                                    : ListView
                                                                        .builder(
                                                                        key: ValueKey(
                                                                            shownProducts),
                                                                        shrinkWrap:
                                                                            true,
                                                                        itemCount:
                                                                            shownProducts!.length,
                                                                        itemBuilder:
                                                                            (context,
                                                                                index) {
                                                                          return StatefulBuilder(
                                                                            builder:
                                                                                (BuildContext context, StateSetter listTileState) {
                                                                              return ListTile(
                                                                                visualDensity: const VisualDensity(vertical: -2),
                                                                                tileColor: shownProducts![index]["id"] == selectedProductId ? Colors.blue : null,
                                                                                leading: CircleAvatar(
                                                                                  child: Text(shownProducts![index]["energyClass"].toString()),
                                                                                ),
                                                                                title: Text(shownProducts![index]["id"].toString()),
                                                                                //trailing: Text(DateTime.fromMillisecondsSinceEpoch(shownProducts![index]["onMarketStartDateTS"] * 1000).toString()),
                                                                                subtitle: Text(shownProducts![index]["supplierOrTrademark"].toString()),
                                                                                onTap: () {
                                                                                  listTileState(() {
                                                                                    setState(() {
                                                                                      selectedProductId = shownProducts![index]["id"];
                                                                                    });
                                                                                  });
                                                                                },
                                                                              );
                                                                            },
                                                                          );
                                                                        },
                                                                      ),
                                                              ),
                                                              Padding(
                                                                padding:
                                                                    const EdgeInsets
                                                                            .all(
                                                                        8.0),
                                                                child: Row(
                                                                    children: [
                                                                      TextButton(
                                                                        child: const Text(
                                                                            "Annuler"),
                                                                        onPressed:
                                                                            () {
                                                                          Navigator.of(context, rootNavigator: true)
                                                                              .pop();
                                                                        },
                                                                      ),
                                                                      const Spacer(),
                                                                      TextButton(
                                                                        onPressed: /*selectedProductId == null ||
                                                                                productQuantityController.text.isEmpty
                                                                            ? null*/
                                                                            /*:*/ () async =>
                                                                                {
                                                                          if (await addProduct(
                                                                            selectedProductId!,
                                                                            productQuantityController.text,
                                                                          ))
                                                                            {
                                                                              Navigator.of(context, rootNavigator: true).pop()
                                                                            }
                                                                          else
                                                                            {}
                                                                        },
                                                                        child: const Text(
                                                                            "Ajouter"),
                                                                      )
                                                                    ]),
                                                              )
                                                            ]);
                                                      }));
                                                } else {
                                                  return const CircularProgressIndicator();
                                                }
                                              })),
                                    ],
                                  ),
                                ),
                              ],
                            ),
                          );
                        })
                  }),
          const Spacer(),
          TextButton.icon(
              icon: const Icon(Icons.add_home_rounded),
              label: const Text("Ajouter un appareil",
                  style: TextStyle(fontSize: 12)),
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

  Future<List<Map<String, dynamic>>> getProducts(String category) async {
    final response = await http.get(Uri.parse(
        'http://localhost:8080/products/getProducts?category=$category'));
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

  Future<bool> addProduct(String productId, String quantity) async {
    SharedPreferences prefs = await SharedPreferences.getInstance();
    Map<String, dynamic> userMap =
        jsonDecode(prefs.getString('user')!) as Map<String, dynamic>;

    final response = await http.post(Uri.parse(
        'http://localhost:8080/houses/addProduct?userId=${userMap["id"]}&productId=$productId&quantity=${quantity == '' ? 0 : quantity}'));

    if (response.statusCode == 200) {
      return true;
    } else {
      return false;
    }
  }

  Function? checkButtonState() {
    if (selectedProductId == null || productQuantityController.text.isEmpty) {
      return null;
    } else {
      return () {
        () async => {
              if (await addProduct(
                selectedProductId!,
                productQuantityController.text,
              ))
                {
                  selectedProductId = null,
                  Navigator.of(context, rootNavigator: true).pop()
                }
              else
                {}
            };
      };
    }
  }
}
