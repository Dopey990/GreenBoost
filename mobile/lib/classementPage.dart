import 'package:GreenBoost/profilePage.dart';
import 'package:flutter/material.dart';
import '/components/menu.dart';

class ClassementPage extends StatelessWidget {
  
  
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
              //icon of return on the left and help on the right
              mainAxisAlignment: MainAxisAlignment.start,
              children: <Widget>[
                //icon button return
                IconButton(
                  onPressed: (() => {
                    Navigator.push(context, MaterialPageRoute(builder: (context) => ProfilePage()),),
                  }),
                  icon: const Icon(Icons.arrow_back, color: Colors.blue, size: 40),
                  ),
              ],
            ),

            
            Row(
              mainAxisAlignment: MainAxisAlignment.center,
              children: const <Widget>[
                Image(
                  image: AssetImage('assets/img/podium.png'),
                  height: 100,
                  width: 100,
                ),
              ],
            ),

            Padding(
              padding: const EdgeInsets.only(bottom: 30),
              child : 
                Row(
                  mainAxisAlignment: MainAxisAlignment.center,
                  children: const <Widget>[
                    Text(
                      'Classement',
                      style: TextStyle(
                        color: Colors.blue,
                        fontSize: 30,
                        fontWeight: FontWeight.bold,
                      ),
                    ),
                  ],
                ),),

            //ligne avec les trois premiers
            Row(
              mainAxisAlignment: MainAxisAlignment.spaceEvenly,
              children: <Widget>[
                Padding(
                  padding: const EdgeInsets.only(top: 20),
                  child : Column(
                  children: const <Widget>[
                    Image(
                      image: AssetImage('assets/img/trophy.png'),
                      width: 10,
                    ),
                    Text(
                      '2nd',
                      style: TextStyle(
                        color: Colors.blue,
                        fontSize: 20,
                        fontWeight: FontWeight.bold,
                      ),
                    ),
                  ],
                ),),
                Column(
                  children: const <Widget>[
                    Image(
                      image: AssetImage('assets/img/trophy.png'),
                      width: 10,
                    ),
                    Text(
                      '1st',
                      style: TextStyle(
                        color: Colors.blue,
                        fontSize: 20,
                        fontWeight: FontWeight.bold,
                      ),
                    ),
                  ],
                ),
                Padding(
                  padding: const EdgeInsets.only(top: 40,bottom: 20),
                  child:
                    Column(
                      children: const <Widget>[
                        Image(
                          image: AssetImage('assets/img/trophy.png'),
                          width: 10,
                        ),
                        Text(
                          '3rd',
                          style: TextStyle(
                            color: Colors.blue,
                            fontSize: 20,
                            fontWeight: FontWeight.bold,
                          ),
                        ),
                      ],
                    ),),
              ],
            ),

            Container(
              width: MediaQuery.of(context).size.width * 0.8,
              padding: const EdgeInsets.only(left: 15.0, right: 15.0, top: 15, bottom: 15),
              decoration: BoxDecoration(
                  color: Colors.blue,
                  borderRadius: BorderRadius.circular(10),
                ),
              child: Column(
                //include list of the 10 first

                children: <Widget>[
                  Padding(
                    padding : const EdgeInsets.only(bottom: 10),
                    child: Row(
                      mainAxisAlignment: MainAxisAlignment.spaceBetween,
                      children: const <Widget>[
                        Image(
                          image: AssetImage('assets/img/trophy.png'),
                          width: 20,
                        ),
                        Text(
                          'A. Dupont',
                          style: TextStyle(
                            color: Colors.white,
                            fontSize: 15,
                            fontWeight: FontWeight.bold,
                          ),
                        ),
                        Text(
                          '567543 arbres',
                          style: TextStyle(
                            color: Colors.white,
                            fontSize: 15,
                            fontWeight: FontWeight.bold,
                          ),
                        ),
                    ],
                  ),),

                  Padding(
                    padding : const EdgeInsets.only(bottom: 10),
                    child: Row(
                      mainAxisAlignment: MainAxisAlignment.spaceBetween,
                      children: const <Widget>[
                        Image(
                          image: AssetImage('assets/img/trophy.png'),
                          width: 20,
                        ),
                        Text(
                          'B. Dupont',
                          style: TextStyle(
                            color: Colors.white,
                            fontSize: 15,
                            fontWeight: FontWeight.bold,
                          ),
                        ),
                        Text(
                          '567543 arbres',
                          style: TextStyle(
                            color: Colors.white,
                            fontSize: 15,
                            fontWeight: FontWeight.bold,
                          ),
                        ),
                    ],
                  ),),
                  
                  ]
                  ),),
          ],
        )
      )
    );
}
}
    