import 'package:GreenBoost/advicesPageElectricity.dart';
import 'package:flutter/material.dart';

import 'components/menu.dart';

class AdvicesPage extends StatelessWidget {
  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        centerTitle: true,
        title: const Text('Home'),
      ),
      drawer: const Menu(),
      body: Container(
        child: Column(children: [
          Row(
            mainAxisAlignment: MainAxisAlignment.center,
            children: [
              //icon of home
              IconButton(
                onPressed: () => {
                  Navigator.push(
                    context,
                    MaterialPageRoute(builder: (context) => AdvicesPage()),
                  )
                },
                color: Colors.blue,
                icon: const Icon(Icons.home, size: 40),
              ),
            ],
          ),
          Row(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            children: [
              //icon of home
              IconButton(
                onPressed: () => {
                  Navigator.push(
                    context,
                    MaterialPageRoute(builder: (context) => AdvicesPage()),
                  )
                },
                color: Colors.blue,
                icon: const Icon(Icons.home, size: 40),
              ),
              IconButton(
                onPressed: () => {
                  Navigator.push(
                    context,
                    MaterialPageRoute(builder: (context) => AdvicesPage()),
                  )
                },
                color: Colors.blue,
                icon: const Icon(Icons.home, size: 40),
              ),
              IconButton(
                onPressed: () => {
                  Navigator.push(
                    context,
                    MaterialPageRoute(builder: (context) => AdvicesPage()),
                  )
                },
                color: Colors.blue,
                icon: const Icon(Icons.home, size: 40),
              ),
            ],
          ),
          Row(
            mainAxisAlignment: MainAxisAlignment.center,
            children: [
              IconButton(
                onPressed: () => {
                  Navigator.push(
                    context,
                    MaterialPageRoute(
                        builder: (context) => advicesPageElectricity()),
                  )
                },
                color: Colors.blue,
                icon: const Icon(Icons.home, size: 40),
              ),
            ],
          ),
        ]),
      ),
    );
  }
}
