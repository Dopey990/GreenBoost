import 'package:GreenBoost/advicesPage.dart';
import 'package:flutter/material.dart';

import 'components/menu.dart';

class AdvicesMenuPage extends StatelessWidget {
  @override
  Widget build(BuildContext context) {
    return Scaffold(
        backgroundColor: Color.fromARGB(255, 177, 201, 183),
      appBar: AppBar(
        backgroundColor: Color.fromARGB(255, 78, 129, 91),
        centerTitle: true,
        title: const Text('Conseils'),
      ),
      drawer: const Menu(),
      body: Column(mainAxisAlignment: MainAxisAlignment.center, children: [
        Padding(
          padding: const EdgeInsets.all(20.0),
          child: Row(
            mainAxisAlignment: MainAxisAlignment.spaceEvenly,
            children: [
              TextButton(
                onPressed: () => {
                  Navigator.push(
                    context,
                    MaterialPageRoute(
                        builder: (context) => AdvicesPage(
                              category: "gaz",
                              title: "le gaz",
                            )),
                  )
                },
                style: TextButton.styleFrom(
                  foregroundColor: Colors.white,
                  backgroundColor: Colors.blue,
                  minimumSize: const Size(150, 80),
                ),
                child: const Text('GAZ', style: TextStyle(fontSize: 20)),
              ),
            ],
          ),
        ),
        Row(
          mainAxisAlignment: MainAxisAlignment.spaceEvenly,
          children: [
            TextButton(
              onPressed: () => {
                Navigator.push(
                  context,
                  MaterialPageRoute(
                      builder: (context) => AdvicesPage(
                            category: "water",
                            title: "l'eau",
                          )),
                )
              },
              style: TextButton.styleFrom(
                foregroundColor: Colors.white,
                backgroundColor: Colors.blue,
                minimumSize: const Size(110, 80),
              ),
              child: const Text('EAU', style: TextStyle(fontSize: 20)),
            ),
            Image.asset('assets/img/conseils_earth.png',
                width: 100, height: 100),
            TextButton(
              onPressed: () => {
                Navigator.push(
                  context,
                  MaterialPageRoute(
                      builder: (context) => AdvicesPage(
                            category: "pollution",
                            title: "pollution",
                          )),
                )
              },
              style: TextButton.styleFrom(
                foregroundColor: Colors.white,
                backgroundColor: Colors.blue,
                minimumSize: const Size(110, 80),
              ),
              child: const Text('CO2', style: TextStyle(fontSize: 20)),
            ),
          ],
        ),
        Padding(
          padding: const EdgeInsets.all(20.0),
          child: Row(
            mainAxisAlignment: MainAxisAlignment.center,
            children: [
              TextButton(
                onPressed: () => {
                  Navigator.push(
                    context,
                    MaterialPageRoute(
                        builder: (context) => AdvicesPage(
                              category: "electricity",
                              title: "l'élecricité",
                            )),
                  )
                },
                style: TextButton.styleFrom(
                  foregroundColor: Colors.white,
                  backgroundColor: Colors.blue,
                  minimumSize: const Size(150, 80),
                ),
                child:
                    const Text('ELECTRICITE', style: TextStyle(fontSize: 20)),
              ),
            ],
          ),
        ),
      ]),
    );
  }
}
