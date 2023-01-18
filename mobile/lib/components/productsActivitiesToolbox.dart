import 'dart:convert';

import 'package:GreenBoost/components/popUp.dart';
import 'package:flutter/material.dart';
import 'package:flutter/services.dart';
import 'package:flutter_titled_container/flutter_titled_container.dart';
import 'package:day_night_time_picker/day_night_time_picker.dart';

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
  List<Map<String, dynamic>>? houseProducts;
  String? categoriesDropdownValue;
  String? selectedProductId;
  String? selectedProductIdHouse;
  String? chosenCategory;
  TimeOfDay _timeStart = TimeOfDay.now();
  TimeOfDay _timeEnd = TimeOfDay.now();

  void onTimeStartChanged(TimeOfDay newTime) {
    setState(() {
      _timeStart = newTime;
    });
  }

  void onTimeEndChanged(TimeOfDay newTime) {
    setState(() {
      _timeEnd = newTime;
    });
  }

  final productNameController = TextEditingController();
  final productBrandController = TextEditingController();
  final productQuantityController = TextEditingController();

  @override
  void initState() {
    productQuantityController.text = '1';
    categories = getCategories();
    // houseProducts = getHouseProducts(chosenCategory);
    super.initState();
  }

  void initStateActivity() {
    selectedProductIdHouse = null;
    _timeStart = TimeOfDay.now();
    _timeEnd = TimeOfDay.now();
  }

  @override
  Widget build(BuildContext context) {
    return Padding(
      padding: const EdgeInsets.all(32.0),
      child: Container(
        width: MediaQuery.of(context).size.width * 0.85,
        height: 60.0,
        decoration: const BoxDecoration(
          color: Color.fromARGB(255, 78, 129, 91),
          shape: BoxShape.rectangle,
          borderRadius: BorderRadius.all(
            Radius.circular(8.0),
          ),
        ),
        child: Row(children: [
          TextButton.icon(
              icon: const Icon(Icons.add_home_rounded),
              style: TextButton.styleFrom(
                foregroundColor: const Color.fromARGB(255, 255, 153, 0),
              ),
              label: const Text(
                "Ajouter un appareil",
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
              icon: const Icon(Icons.add_task_rounded),
              style: TextButton.styleFrom(
                foregroundColor: const Color.fromARGB(255, 255, 153, 0),
              ),
              label: const Text("Ajouter une activité",
                  style: TextStyle(fontSize: 12)),
              onPressed: () => {
                    showDialog(
                        context: context,
                        builder: (BuildContext context) {
                          return AlertDialog(
                              content: Stack(children: <Widget>[
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
                                    child: FutureBuilder<Set<String>>(
                                        future: categories,
                                        builder: (context, snapshot) {
                                          return snapshot.hasData
                                              ? DecoratedBox(
                                                  decoration: BoxDecoration(
                                                    borderRadius:
                                                        BorderRadius.circular(
                                                            15),
                                                  ),
                                                  child: StatefulBuilder(
                                                      builder: (BuildContext
                                                              context,
                                                          StateSetter
                                                              dropDownState) {
                                                    return Column(children: [
                                                      DropdownButton(
                                                          menuMaxHeight: 350,
                                                          borderRadius:
                                                              BorderRadius
                                                                  .circular(15),
                                                          value: chosenCategory,
                                                          items: snapshot.data?.map<
                                                              DropdownMenuItem<
                                                                  String>>((String
                                                              category) {
                                                            return DropdownMenuItem<
                                                                    String>(
                                                                value: category,
                                                                child: Padding(
                                                                  padding:
                                                                      const EdgeInsets
                                                                          .all(10),
                                                                  child: Text(
                                                                    category,
                                                                    style: const TextStyle(
                                                                        fontWeight:
                                                                            FontWeight.bold),
                                                                  ),
                                                                ));
                                                          }).toList()
                                                            ?..add(
                                                                const DropdownMenuItem<
                                                                    String>(
                                                              value: 'ALL',
                                                              child: Padding(
                                                                padding:
                                                                    EdgeInsets
                                                                        .all(
                                                                            10),
                                                                child: Text(
                                                                  'ALL',
                                                                  style: TextStyle(
                                                                      fontWeight:
                                                                          FontWeight
                                                                              .bold),
                                                                ),
                                                              ),
                                                            )),
                                                          hint: const Text(
                                                            "Catégorie",
                                                          ),
                                                          onChanged: (String?
                                                              value) async {
                                                            houseProducts =
                                                                await getHouseProducts(
                                                                    value!);
                                                            dropDownState(() {
                                                              chosenCategory =
                                                                  value;
                                                            });
                                                          }),
                                                      Padding(
                                                          padding:
                                                              const EdgeInsets
                                                                  .all(8.0),
                                                          child: SizedBox(
                                                              width: 300,
                                                              height: 200,
                                                              child: houseProducts ==
                                                                      null
                                                                  ? const Text(
                                                                      "Veuillez sélectionner une catégorie")
                                                                  : ListView
                                                                      .builder(
                                                                      key: ValueKey(
                                                                          houseProducts),
                                                                      shrinkWrap:
                                                                          true,
                                                                      itemCount:
                                                                          houseProducts
                                                                              ?.length,
                                                                      itemBuilder:
                                                                          (context,
                                                                              index) {
                                                                        return StatefulBuilder(
                                                                          builder:
                                                                              (BuildContext context, StateSetter listTileState) {
                                                                            return ListTile(
                                                                              visualDensity: const VisualDensity(vertical: -2),
                                                                              tileColor: houseProducts![index]["id"] == selectedProductId ? Colors.blue : null,
                                                                              leading: CircleAvatar(
                                                                                child: Text(houseProducts![index]["energyClass"].toString()),
                                                                              ),
                                                                              title: Text(houseProducts![index]["id"].toString()),
                                                                              //trailing: Text(DateTime.fromMillisecondsSinceEpoch(shownProducts![index]["onMarketStartDateTS"] * 1000).toString()),
                                                                              subtitle: Text(houseProducts![index]["supplierOrTrademark"].toString()),
                                                                              onTap: () {
                                                                                listTileState(() {
                                                                                  selectedProductIdHouse = houseProducts![index]["id"];
                                                                                });
                                                                              },
                                                                            );
                                                                          },
                                                                        );
                                                                      },
                                                                    )))
                                                    ]);
                                                  }))
                                              : const Center(
                                                  child:
                                                      CircularProgressIndicator());
                                        }),
                                  ),
                                  Row(
                                    children: [
                                      Column(children: [
                                        TextButton(
                                          style: TextButton.styleFrom(
                                              elevation: 2,
                                              backgroundColor: Colors.amber),
                                          onPressed: () {
                                            Navigator.of(context).push(
                                              showPicker(
                                                context: context,
                                                value: _timeStart,
                                                onChange: onTimeStartChanged,
                                                iosStylePicker: true,
                                                is24HrFormat: true,
                                              ),
                                            );
                                          },
                                          child: const Text(
                                            "Heure de début",
                                            style:
                                                TextStyle(color: Colors.white),
                                          ),
                                        ),
                                      ]),
                                      const Spacer(),
                                      TextButton(
                                        style: TextButton.styleFrom(
                                            elevation: 2,
                                            backgroundColor: Colors.amber),
                                        onPressed: () {
                                          Navigator.of(context).push(
                                            showPicker(
                                              context: context,
                                              value: _timeEnd,
                                              onChange: onTimeEndChanged,
                                              iosStylePicker: true,
                                              is24HrFormat: true,
                                            ),
                                          );
                                        },
                                        child: const Text(
                                          "Heure de fin",
                                          style: TextStyle(color: Colors.white),
                                        ),
                                      ),
                                    ],
                                  ),
                                  Row(children: [
                                    Padding(
                                      padding: const EdgeInsets.all(8.0),
                                      child: Column(children: [
                                        TextButton(
                                          child: const Text("Annuler"),
                                          onPressed: () {
                                            Navigator.of(context,
                                                    rootNavigator: true)
                                                .pop();
                                            initStateActivity();
                                          },
                                        )
                                      ]),
                                    ),
                                    const Spacer(),
                                    Padding(
                                      padding: const EdgeInsets.all(8.0),
                                      child: Column(children: [
                                        TextButton(
                                          child: const Text("Valider"),
                                          onPressed: () async {
                                            int duree = (_timeEnd.hour * 60 +
                                                    _timeEnd.minute) -
                                                (_timeStart.hour * 60 +
                                                    _timeStart.minute);
                                            if (duree <= 0 ||
                                                selectedProductIdHouse ==
                                                    null) {
                                              showDialog(
                                                  context: context,
                                                  builder: (BuildContext
                                                          context) =>
                                                      const PopUp(
                                                          text:
                                                              "Veuillez renseigner les créneaux horaires ou le produit"));
                                            } else {
                                              int score =
                                                  await setPointsForActivity(
                                                      selectedProductIdHouse!,
                                                      duree);
                                              if (mounted) {
                                                Navigator.of(context,
                                                        rootNavigator: true)
                                                    .pop();
                                              }
                                              showDialog(
                                                  context: context,
                                                  builder: (BuildContext
                                                          context) =>
                                                      PopUp(
                                                          text:
                                                              "Vous avez obtenu $score points"));
                                              initStateActivity();
                                            }
                                          },
                                        )
                                      ]),
                                    ),
                                  ]),
                                ]))
                          ]));
                        })
                  }),
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

  Future<List<Map<String, dynamic>>> getHouseProducts(
      String chosenCategory) async {
    SharedPreferences prefs = await SharedPreferences.getInstance();
    Map<String, dynamic> userMap =
        jsonDecode(prefs.getString('user')!) as Map<String, dynamic>;
    String parameters = chosenCategory == "ALL"
        ? 'userId=${userMap["id"]}'
        : 'userId=${userMap["id"]}&category=$chosenCategory';
    final response = await http.get(
        Uri.parse('http://localhost:8080/houses/listProducts?$parameters'));
    List<Map<String, dynamic>> result = [];
    if (response.statusCode == 200) {
      jsonDecode(response.body).forEach((product) {
        result.add(product);
      });
      return result;
    } else {
      throw Exception(
          'Failed to load products with category: ${chosenCategory!}');
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
      final response = await http.post(Uri.parse(
          'http://localhost:8080/user/getUserByToken?token=${userMap['token']}'));
      prefs.setString('user', response.body);
      return true;
    } else {
      return false;
    }
  }

  Future<int> setPointsForActivity(String productId, int duree) async {
    SharedPreferences prefs = await SharedPreferences.getInstance();
    Map<String, dynamic> userMap =
        jsonDecode(prefs.getString('user')!) as Map<String, dynamic>;
    final response = await http.get(Uri.parse(
        'http://localhost:8080/houses/setPointsForActivity?userId=${userMap["id"]}&productId=$productId&duree=$duree'));
    if (response.statusCode == 200) {
      return int.parse(response.body);
    } else {
      return throw Exception('Failed to set point to the user');
    }
  }
}
