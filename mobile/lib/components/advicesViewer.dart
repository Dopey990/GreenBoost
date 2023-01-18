import 'dart:convert';

import 'package:flutter/material.dart';
import 'package:flutter_titled_container/flutter_titled_container.dart';

import 'package:http/http.dart' as http;

class AdvicesViewerWidget extends StatefulWidget {
  String apiUrl;

  AdvicesViewerWidget({super.key, required this.apiUrl});

  @override
  State<StatefulWidget> createState() => AdvicesViewerState();
}

class AdvicesViewerState extends State<AdvicesViewerWidget> {
  late Future<List<String>> advices;
  int currentAdviceIndex = 0;

  @override
  void initState() {
    advices = getAdvices();
    super.initState();
  }

  @override
  Widget build(BuildContext context) {
    return FutureBuilder<List<String>>(
        future: advices,
        builder: (BuildContext context, AsyncSnapshot<List<String>> snapshot) {
          if (snapshot.hasData) {
            var data = snapshot.data!;

            return Padding(
                padding: const EdgeInsets.all(10),
                child: Row(
                  mainAxisAlignment: MainAxisAlignment.center,
                  children: [
                    IconButton(
                      iconSize: 30,
                      icon: const Icon(Icons.keyboard_arrow_left),
                      onPressed: () {
                        setState(() {
                          currentAdviceIndex = currentAdviceIndex == 0
                              ? data.length - 1
                              : currentAdviceIndex - 1;
                        });
                      },
                    ),
                    TitledContainer(
                      titleColor: Colors.blue,
                      title: "Conseils",
                      textAlign: TextAlignTitledContainer.Left,
                      fontSize: 20,
                      backgroundColor: Colors.white,
                      child: Container(
                        width: MediaQuery.of(context).size.width / 1.5,
                        decoration: BoxDecoration(
                          border: Border.all(
                            color: Colors.blue,
                          ),
                          borderRadius: const BorderRadius.all(
                            Radius.circular(10.0),
                          ),
                        ),
                        child: Padding(
                            padding: const EdgeInsets.all(5.0),
                            child: Text(
                              data[currentAdviceIndex],
                              textAlign: TextAlign.center,
                            )),
                      ),
                    ),
                    IconButton(
                        onPressed: () {
                          setState(() {
                            currentAdviceIndex =
                                currentAdviceIndex == data.length - 1
                                    ? 0
                                    : currentAdviceIndex + 1;
                          });
                        },
                        iconSize: 30,
                        icon: const Icon(Icons.keyboard_arrow_right))
                  ],
                ));
          } else {
            return const Padding(
                padding: EdgeInsets.all(20.0),
                child: CircularProgressIndicator());
          }
        });
  }

  Future<List<String>> getAdvices() async {
    final response = await http.get(Uri.parse(widget.apiUrl));

    List<String> result = [];

    if (response.statusCode == 200) {
      jsonDecode(response.body).forEach((line) {
        result.add(line);
      });

      return result;
    } else {
      throw Exception("Failed to load advices");
    }
  }
}
