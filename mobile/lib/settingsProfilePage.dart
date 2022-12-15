import 'package:flutter/material.dart';
import '/components/menu.dart';

class SettingsProfilePage extends StatelessWidget {
  @override
  Widget build(BuildContext context){
    return Scaffold(
      appBar: AppBar(
        centerTitle: true,
        title: Text('Settings Profile'),
      ),
      drawer: Menu(),
      body: SingleChildScrollView(
        child : Column (          
          children: <Widget>[
            Row(
              mainAxisAlignment: MainAxisAlignment.end,
              children: const <Widget>[
                Icon(
                  Icons.help,
                  color: Colors.blue,
                  size: 40,
                ),
              ],
            ),
          
            Row(
              mainAxisAlignment: MainAxisAlignment.center,
              children: const <Widget>[
                Icon(
                  Icons.person,
                  color: Colors.blue,
                  size: 80,
                ),
              ],
            ),

            Container(
              height: MediaQuery.of(context).size.height * 0.2,
              width: MediaQuery.of(context).size.width * 0.8,
              padding: const EdgeInsets.only(left: 15.0, right: 15.0, top: 15, bottom: 15),
              decoration: BoxDecoration(
                  color: Colors.blue,
                  borderRadius: BorderRadius.circular(10),
                ),
              child: Column(
                children: <Widget> [
                  Row(
                    mainAxisAlignment: MainAxisAlignment.end,
                    children: const <Widget>[
                      IconButton(
                        onPressed: null, 
                        icon: Icon(Icons.edit, color: Colors.white, size: 20),
                        )
                    ],
                  ),
                  Row(
                    mainAxisAlignment: MainAxisAlignment.start,
                    children: const <Widget>[
                      Text(
                        'Nom : Dupont',
                        style: TextStyle(
                          color: Colors.white,
                          fontSize: 15,
                        ),
                      ),
                    ],
                  ),
                  Row(
                    mainAxisAlignment: MainAxisAlignment.start,
                    children: const <Widget>[
                      Text(
                        'Prénom : Alexandre',
                        style: TextStyle(
                          color: Colors.white,
                          fontSize: 15,
                        ),
                      ),
                    ],
                  ),
                  Row(
                    mainAxisAlignment: MainAxisAlignment.start,
                    children: const <Widget>[
                      Text(
                        'Email : alexandre.dupont@gmail.com',
                        style: TextStyle(
                          color: Colors.white,
                          fontSize: 15,
                        ),
                      ),
                    ],
                  ),
                ],
              ),
            ),
          ]


      ),
    ),);
  }
}

