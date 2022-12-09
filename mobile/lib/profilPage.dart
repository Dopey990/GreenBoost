//create profil page
import 'package:flutter/material.dart';
import '/components/menu.dart';

class ProfilPage extends StatelessWidget {
  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        centerTitle: true,
        title: Text('Profil'),
      ),
      drawer: Menu(),
      body: SingleChildScrollView(
        child : Column (
          //icon of parameters on right and icon of podium on left
          children: [
            Row(
              mainAxisAlignment: MainAxisAlignment.spaceBetween,
              children: [
                IconButton(
                  onPressed: () {
                    Navigator.of(context).pushNamed("/advices");
                  },
                  icon: const Icon(Icons.stadium),
                ),
                IconButton(
                  onPressed: () {
                    Navigator.of(context).pushNamed("/advices");
                  },
                  icon: const Icon(Icons.settings),
                ),
              ],
            ),
            //container with 4 padding displaying the user's name and score
            Container(
              padding: EdgeInsets.all(20),
              child: Column(
                children : <Widget>[
                  const Padding(
                    padding: const EdgeInsets.only(left:15.0,right: 15.0,top:10,bottom: 0),
                    //padding: EdgeInsets.symmetric(horizontal: 15),
                    child: //display informations about user in a box with round border
                      Text('Position : 1245ème '),
                  ),
                ]
            ),
            )
          ]
        )
      ),
    );
  }
}

