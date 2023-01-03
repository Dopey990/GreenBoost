import 'package:GreenBoost/classementPage.dart';
import 'package:flutter/material.dart';
import '/components/menu.dart';
import 'package:GreenBoost/settingsProfilePage.dart';

class ProfilePage extends StatelessWidget {
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
          children: <Widget>[
            Row(
              //icon of settings on the right
              mainAxisAlignment: MainAxisAlignment.end,
              children: <Widget>[
                IconButton(
                  onPressed: () => {
                    Navigator.push(context,MaterialPageRoute(builder: (context) => SettingsProfilePage()),)
                  },
                  color: Colors.blue,
                  icon: const Icon(Icons.settings, size: 40),
                ),
              ],
            ),

            Padding(
              padding: const EdgeInsets.only(top: 1.0),
              child: Center(
                child: Image.asset(
                  'assets/img/trophy.png',
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
                  color: Colors.blue,
                  borderRadius: BorderRadius.circular(10),
                ),
              child: Column(
                mainAxisAlignment: MainAxisAlignment.spaceEvenly,
                children: <Widget>[
                  Row(
                    mainAxisAlignment: MainAxisAlignment.start,
                    children: <Widget>[
                      GestureDetector(
                        onTap: () => {
                          Navigator.push(context,MaterialPageRoute(builder: (context) => ClassementPage()),)
                        },
                        child: 
                        const Image(
                          image: AssetImage('assets/img/podium.png'),
                          height: 50,
                          width: 50,
                        ),
                      ),
                      
                    ],
                  ),
                  const Padding(
                    padding: EdgeInsets.only(bottom: 10),
                    child: Text(
                      "Position : 65467764ème",
                      style: TextStyle(
                        color: Colors.white,
                        fontSize: 25,
                      ),
                    ),
                  ),
                  SizedBox(
                    width: MediaQuery.of(context).size.width * 0.8,
                    child: 
                      Card(
                        child: Container(
                          decoration: BoxDecoration(
                            color: Colors.green,
                            borderRadius: BorderRadius.circular(10),
                          ),
                          padding: const EdgeInsets.all(20),
                          child: const Text("Electricité : 123456ème"),
                          ),),),
                  SizedBox(
                    width: MediaQuery.of(context).size.width * 0.8,
                    child: 
                      Card(
                        child: Container(
                          decoration: BoxDecoration(
                            color: Colors.orange,
                            borderRadius: BorderRadius.circular(10),
                          ),
                          padding: const EdgeInsets.all(20),
                          child: const Text("Eau : 123456ème"),
                          ),),),
                  SizedBox(
                    width:  MediaQuery.of(context).size.width * 0.8,
                    child: 
                      Card(
                        child: Container(
                          decoration: BoxDecoration(
                            color: Colors.green,
                            borderRadius: BorderRadius.circular(10),
                          ),
                          padding: const EdgeInsets.all(20),
                          child: const Text("Gaz : 123456ème"),
                          ),),),
                  SizedBox(
                    width:  MediaQuery.of(context).size.width * 0.8,
                    child: 
                      Card(
                        child: Container(
                          decoration: BoxDecoration(
                            color: Colors.red,
                            borderRadius: BorderRadius.circular(10),
                          ),
                          padding: const EdgeInsets.all(20),
                          child: const Text("Pollution : 123456ème"),
                          ),),),
                ]
            ),
            )
          ]
        )
      ),
    );
  }
}

