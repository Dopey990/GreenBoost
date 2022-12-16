import 'package:GreenBoost/profilePage.dart';
import 'package:flutter/material.dart';
import '/components/menu.dart';

import 'package:lite_rolling_switch/lite_rolling_switch.dart';


class SettingsProfilePage extends StatelessWidget {
  @override
  Widget build(BuildContext context){
    return Scaffold(
      appBar: AppBar(
        centerTitle: true,
        title: const Text('Settings Profile'),
      ),
      drawer: const Menu(),
      body: SingleChildScrollView(
        child : Column (          
          children: <Widget>[
            Row(
              //icon of return on the left and help on the right
              mainAxisAlignment: MainAxisAlignment.spaceBetween,
              children: <Widget>[
                //icon button return
                IconButton(
                  onPressed: (() => {
                    Navigator.push(context, MaterialPageRoute(builder: (context) => ProfilePage()),),
                  }),
                  icon: const Icon(Icons.arrow_back, color: Colors.blue, size: 40),
                  ),
                IconButton(
                  onPressed: () {
                     showDialog(
                        context: context,
                        builder: (BuildContext context) => _buildPopupDialog(context),
                      );
                  },
                  icon: const Icon(Icons.help, color: Colors.blue, size: 40),
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
            
            Row(
              mainAxisAlignment: MainAxisAlignment.center,
              children: const <Widget>[
                Icon(
                  Icons.house_rounded,
                  color: Colors.blue,
                  size: 80,
                ),
              ],
            ),

            Container(
              width: MediaQuery.of(context).size.width * 0.8,
              padding: const EdgeInsets.only(left: 10.0, right: 10.0, top: 10, bottom: 10),
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
                    mainAxisAlignment: MainAxisAlignment.center,
                    children: <Widget>[
                      SizedBox(
                        width:  MediaQuery.of(context).size.width * 0.35,
                        height: MediaQuery.of(context).size.height * 0.08,
                        child: Card(
                          child: Container(
                            alignment: Alignment.center,
                            decoration: BoxDecoration(
                              color: Colors.white,
                              borderRadius: BorderRadius.circular(10),
                            ),
                            child: const Text("6 personnes"),
                            ),),),
                      
                      SizedBox(
                        width:  MediaQuery.of(context).size.width * 0.35,
                        height: MediaQuery.of(context).size.height * 0.08,
                        child: Card(
                          child: Container(
                            alignment: Alignment.center,
                            decoration: BoxDecoration(
                              color: Colors.white,
                              borderRadius: BorderRadius.circular(10),
                            ),
                            child: const Text("245 m²"),
                            ),),),
                    ],
                  ),    

                  Row(
                    mainAxisAlignment: MainAxisAlignment.center,
                    children: [
                      SizedBox(
                        width:  MediaQuery.of(context).size.width * 0.7,
                        height: MediaQuery.of(context).size.height * 0.08,
                        child: Card(
                          child: Container(
                            alignment: Alignment.center,
                            decoration: BoxDecoration(
                              color: Colors.white,
                              borderRadius: BorderRadius.circular(10),
                            ),
                            child: const Text("Isolation de classe G"),
                            ),),),                      
                    ],
                  ),

                  Row(
                    mainAxisAlignment: MainAxisAlignment.center,
                    children: [
                      SizedBox(
                        width:  MediaQuery.of(context).size.width * 0.35,
                        height: MediaQuery.of(context).size.height * 0.08,
                        child: Card(
                          child: Container(
                            alignment: Alignment.center,
                            decoration: BoxDecoration(
                              color: Colors.white,
                              borderRadius: BorderRadius.circular(10),
                            ),
                            child: const Text("12 fenêtres"),
                            ),),),
                      SizedBox(
                        width:  MediaQuery.of(context).size.width * 0.35,
                        height: MediaQuery.of(context).size.height * 0.08,
                        child: Card(
                          child: Container(
                            alignment: Alignment.center,
                            decoration: BoxDecoration(
                              color: Colors.white,
                              borderRadius: BorderRadius.circular(10),
                            ),
                            child: 
                              const IconButton(
                                onPressed: null, 
                                icon: Icon(
                                  Icons.add, 
                                  color: Colors.blue,
                                  size: 20),
                                )
                              )
                            ),),                   
                    ],
                  ),
                               

                ]),

            ),

          ]


      ),
    ),);
  }
}


Widget _buildPopupDialog(BuildContext context) {
  return AlertDialog(
    //give tutoriels to title and center it 
    title: const Text('Tutoriel', textAlign: TextAlign.center),
    alignment: Alignment.center,
    content:
        Container(
          height: MediaQuery.of(context).size.height * 0.2,
          alignment: Alignment.center,
          child: Column(
            mainAxisSize: MainAxisSize.min,
            crossAxisAlignment: CrossAxisAlignment.center,
            children: [
              const Text("Réinitialiser les tutoriels pour l'ensemble de l'application",  textAlign: TextAlign.center),
              Padding(
                    padding: const EdgeInsets.only(top: 20),
                    child: LiteRollingSwitch(
                      value: true,
                      textOn: 'Yes',
                      textOff: 'No',
                      colorOn: Colors.cyan,
                      colorOff: Colors.red,
                      iconOn: Icons.check,
                      iconOff: Icons.power_settings_new,
                      animationDuration: const Duration(milliseconds: 800),
                      onChanged: (bool state) {
                        print('turned ${(state) ? 'yes' : 'no'}');
                      }, 
                      
                      //TODO : CHANGE IT
                      onDoubleTap: (){},
                      onTap: (){},
                      onSwipe: (){},
                      
                    ),
                  ),
            ],
          )
        )
    );
}