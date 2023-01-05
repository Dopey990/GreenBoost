import 'package:GreenBoost/classementPage.dart';
import 'package:flutter/material.dart';
import '/components/menu.dart';
import 'package:GreenBoost/settingsProfilePage.dart';

class ChallengePage extends StatelessWidget {
  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: const Color.fromRGBO(168, 203, 208, 1),
      appBar: AppBar(
        centerTitle: true,
        title: Text('Challenges'),
      ),
      drawer: Menu(),
      body: SingleChildScrollView(
        child : Column (          
          children: <Widget>[
            
            Padding(
              padding: const EdgeInsets.only(top: 15),
              child: Center(
                child: Image.asset(
                  'assets/img/challenge.png',
                  height: 100,
                  width: 100,
                  fit: BoxFit.fitWidth,
                ),
              ),
            ),

            Container(
              height: MediaQuery.of(context).size.height * 0.6,
              width: MediaQuery.of(context).size.width * 0.8,
              padding: const EdgeInsets.only(left: 15.0, right: 15.0, top: 10, bottom: 10),
              decoration: BoxDecoration(
                  color: Color.fromRGBO(217, 217, 217, 1),
                  borderRadius: BorderRadius.circular(10),
                ),
              child: Column(
                mainAxisAlignment: MainAxisAlignment.spaceEvenly,
                children: <Widget>[
                  SizedBox(
                    width: MediaQuery.of(context).size.width * 0.8,
                    child: 
                      Card(
                        child: Container(                          
                          decoration: BoxDecoration(
                            color: Color.fromRGBO(125, 192, 120, 1),
                            borderRadius: BorderRadius.circular(10),
                          ),
                          padding: const EdgeInsets.all(20),
                          child: const Text("Débrancher les appareils en veille"),
                          ),
                          
                          ),
                          
                          ),
                  SizedBox(
                    width: MediaQuery.of(context).size.width * 0.8,
                    child: 
                      Card(
                        child: Container(
                          decoration: BoxDecoration(
                            color: Color.fromRGBO(236, 188, 118, 1),
                            borderRadius: BorderRadius.circular(10),
                          ),
                          padding: const EdgeInsets.all(20),
                          child: const Text("Couvrez les casserolles ",
                          
                          ),
                          
                          ),),),
                  SizedBox(
                    width:  MediaQuery.of(context).size.width * 0.8,
                    child: 
                      Card(
                        child: Container(
                          decoration: BoxDecoration(
                            color: Color.fromRGBO(125, 192, 120, 1),
                            borderRadius: BorderRadius.circular(10),
                          ),
                          padding: const EdgeInsets.all(20),
                          child: const Text("Faites vos courses en vrac"),
                          ),),),
                  SizedBox(
                    width:  MediaQuery.of(context).size.width * 0.8,
                    child: 
                      Card(
                        child: Container(
                          decoration: BoxDecoration(
                            color: Color.fromRGBO(212, 115, 127, 1),
                            borderRadius: BorderRadius.circular(10),
                          ),
                          padding: const EdgeInsets.all(20),
                          child: const Text("Faites vos courses en vrac"),
                          
                          ),),),
                  SizedBox(
                    width:  MediaQuery.of(context).size.width * 0.8,
                    child: 
                      Card(
                        child: Container(
                          decoration: BoxDecoration(
                            color: Color.fromRGBO(212, 115, 127, 1),
                            borderRadius: BorderRadius.circular(10),
                          ),
                          padding: const EdgeInsets.all(20),
                          child: const Text("Faites vos courses en vrac"),
                          
                          ),),),
                  SizedBox(
                    width:  MediaQuery.of(context).size.width * 0.8,
                    child: 
                      Card(
                        child: Container(
                          decoration: BoxDecoration(
                            color: Color.fromRGBO(212, 115, 127, 1),
                            borderRadius: BorderRadius.circular(10),
                          ),
                          padding: const EdgeInsets.all(20),
                          child: const Text("Faites vos courses en vrac"),
                          
                          ),),),
                ]
            ),
            ),
          ]
        )
      ),
    );
  }
}

