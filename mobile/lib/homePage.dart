import 'package:flutter/material.dart';
import 'package:flutter_titled_container/flutter_titled_container.dart';

import 'components/menu.dart';

class HomePage extends StatelessWidget {
  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        centerTitle: true,
        title: Text('Home'),
      ),
      drawer: const Menu(),
      body: Column(
        children: [
          const SizedBox(height: 10),
          Align(
              alignment: Alignment.centerRight,
              child: Padding(
                  padding: const EdgeInsets.only(right: 10),
                  child: IconButton(
                      onPressed: () {
                        Navigator.of(context).pushNamed("/advices");
                      },
                      icon: const Icon(Icons.lightbulb)))),
          TitledContainer(
            titleColor: Colors.blue,
            title: "Eco-Score",
            textAlign: TextAlignTitledContainer.Left,
            fontSize: 16.0,
            backgroundColor: Colors.white,
            child: Container(
              width: 150,
              height: 100,
              decoration: BoxDecoration(
                border: Border.all(
                  color: Colors.blue,
                ),
                borderRadius: const BorderRadius.all(
                  Radius.circular(10.0),
                ),
              ),
              child: const Center(
                child: Text(
                  "46/100",
                  style: TextStyle(fontSize: 28.0),
                ),
              ),
            ),
          ),
          const SizedBox(height: 20.0),
          Padding(
              padding:
                  EdgeInsets.only(right: MediaQuery.of(context).size.width / 3),
              child: GestureDetector(
                onTap: () {
                  Navigator.of(context).pushNamed("/info/electricity");
                },
                child: Image.asset('home/electricite/electricite-vert.png',
                    height: MediaQuery.of(context).size.width / 8,
                    width: MediaQuery.of(context).size.width / 8),
              )),
          Padding(
              padding:
                  EdgeInsets.only(left: MediaQuery.of(context).size.width / 3),
              child: GestureDetector(
                onTap: () {
                  return;
                },
                child: Image.asset('home/eau/eau-rouge.png',
                    height: MediaQuery.of(context).size.width / 8,
                    width: MediaQuery.of(context).size.width / 8),
              )),
          Padding(
              padding:
                  EdgeInsets.only(right: MediaQuery.of(context).size.width / 3),
              child: GestureDetector(
                onTap: () {
                  return;
                },
                child: Image.asset('home/gaz/gaz-orange.png',
                    height: MediaQuery.of(context).size.width / 8,
                    width: MediaQuery.of(context).size.width / 8),
              )),
          Padding(
              padding:
                  EdgeInsets.only(left: MediaQuery.of(context).size.width / 3),
              child: GestureDetector(
                onTap: () {
                  return;
                },
                child: Image.asset('home/pollution/pollution-vert.png',
                    height: MediaQuery.of(context).size.width / 8,
                    width: MediaQuery.of(context).size.width / 8),
              )),
          const Spacer(),
          Center(
              child: TitledContainer(
            titleColor: Colors.blue,
            title: "Défis",
            textAlign: TextAlignTitledContainer.Center,
            fontSize: 16.0,
            backgroundColor: Colors.white,
            child: Container(
              width: MediaQuery.of(context).size.width / 1.25,
              height: 100,
              decoration: BoxDecoration(
                border: Border.all(
                  color: Colors.blue,
                ),
                borderRadius: const BorderRadius.all(
                  Radius.circular(10.0),
                ),
              ),
              child: const Padding(
                  padding: EdgeInsets.only(top: 10.0),
                  child: Text(
                    "Débrancher trois appareils inutilisés chez vous",
                    style: TextStyle(fontSize: 28.0),
                    textAlign: TextAlign.center,
                  )),
            ),
          )),
          Padding(
              padding: const EdgeInsets.only(top: 10, bottom: 10),
              child: Row(
                mainAxisAlignment: MainAxisAlignment.spaceEvenly,
                children: [
                  IconButton(
                      onPressed: () {
                        print("ok");
                      },
                      icon: const Icon(Icons.check)),
                  IconButton(
                      onPressed: () {
                        print("ok");
                      },
                      icon: const Icon(Icons.close))
                ],
              ))
        ],
      ),
    );
  }
}
