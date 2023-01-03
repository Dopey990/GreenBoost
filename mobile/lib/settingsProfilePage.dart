import 'package:GreenBoost/profilePage.dart';
import 'package:flutter/material.dart';
import '/components/menu.dart';

import 'package:lite_rolling_switch/lite_rolling_switch.dart';

class SettingsProfilePage extends StatefulWidget {
  @override
  _SettingsProfilePageState createState() => _SettingsProfilePageState();
}

class _SettingsProfilePageState extends State<SettingsProfilePage> {
  late bool _enabledContact;
  late bool _enabledHouse;

  @override
  void initState() {
    super.initState();
    _enabledContact = false;
    _enabledHouse = false;
  }

  @override
  Widget build(BuildContext context){
    return Scaffold(
      backgroundColor: const Color.fromRGBO(168, 203, 208, 1),
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
              height: MediaQuery.of(context).size.height * 0.35,
              width: MediaQuery.of(context).size.width * 0.8,
              padding: const EdgeInsets.only(left: 15.0, right: 15.0, top: 15, bottom: 15),
              decoration: BoxDecoration(
                  color: Color.fromRGBO(217, 217, 217, 1),
                  borderRadius: BorderRadius.circular(10),
                ),
              child: Column(
                children: <Widget> [
                  Row(
                    mainAxisAlignment: MainAxisAlignment.end,
                    children: <Widget>[
                      IconButton(
                        onPressed: () => setState(() {
                          if(_enabledContact == false){
                            _enabledContact = true;
                          } else {
                            _enabledContact = false;
                          }
                        }),
                        icon: const Icon(Icons.edit, color: Colors.black, size: 20),
                        )
                    ],
                  ),
                  Row(
                    mainAxisAlignment: MainAxisAlignment.start,
                    children: <Widget>[
                      const Text(
                        'Nom : ',
                        style: TextStyle(
                          color: Colors.black,
                          fontSize: 15,
                        ),
                      ),
                      
                      Flexible(
                        child: 
                          TextField(
                            enabled: _enabledContact,
                            cursorHeight: 15,
                            decoration: const InputDecoration(
                              border: InputBorder.none,
                              hintText: 'Dupont',
                              hintStyle: TextStyle(
                                color: Colors.black,
                                fontSize: 15,
                              ),
                            ),
                          ),
                      ),
                    ],
                  ),
                  Row(
                    mainAxisAlignment: MainAxisAlignment.start,
                    children:  <Widget>[
                      const Text(
                        'Prénom : ',
                        style: TextStyle(
                          color: Colors.black,
                          fontSize: 15,
                        ),
                      ),
                      
                      Flexible(
                        child: 
                          TextField(
                            enabled: _enabledContact,
                            cursorHeight: 15,
                            decoration: const InputDecoration(
                              border: InputBorder.none,
                              hintText: 'Alexandre',
                              hintStyle: TextStyle(
                                color: Colors.black,
                                fontSize: 15,
                              ),
                            ),
                          ),
                      ),
                    ],
                  ),
                  Row(
                    mainAxisAlignment: MainAxisAlignment.start,
                    children: <Widget>[
                      const Text(
                        'Email : ',
                        style: TextStyle(
                          color: Colors.black,
                          fontSize: 15,
                        ),
                      ),
                      
                      Flexible(
                        child: 
                          TextField(
                            enabled: _enabledContact,
                            cursorHeight: 15,
                            decoration: const InputDecoration(
                              border: InputBorder.none,
                              hintText: 'alexandre.dupont@gmail.com',
                              hintStyle: TextStyle(
                                color: Colors.black,
                                fontSize: 15,
                              ),
                            ),
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
                  color: Color.fromRGBO(217, 217, 217, 1),
                  borderRadius: BorderRadius.circular(10),
                ),
              child: Column(
                children: <Widget> [
                  Row(
                    mainAxisAlignment: MainAxisAlignment.end,
                    children: <Widget>[
                      IconButton(
                        onPressed: 
                          () => setState(() {
                            if(_enabledHouse == false){
                              _enabledHouse = true;
                            } else {
                              _enabledHouse = false;
                            }
                          }),
                        icon: const Icon(Icons.edit, color: Colors.black, size: 20),
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
                              color: Color.fromRGBO(193, 190, 190, 1),
                              borderRadius: BorderRadius.circular(10),
                            ),
                            child: 
                                TextField(
                                  textAlign: TextAlign.center,
                                  enabled: _enabledHouse,
                                  cursorHeight: 15,
                                  decoration: const InputDecoration(
                                    border: InputBorder.none,
                                    hintText: '6 personnes',
                                    hintStyle: TextStyle(
                                      color: Colors.black,
                                      fontSize: 15,
                                      fontWeight: FontWeight.bold,
                                  
                                    ),
                                  ),
                                ),
                              
                            ),),),
                      
                      SizedBox(
                        width:  MediaQuery.of(context).size.width * 0.35,
                        height: MediaQuery.of(context).size.height * 0.08,
                        child: Card(
                          child: Container(
                            alignment: Alignment.center,
                            decoration: BoxDecoration(
                              color: Color.fromRGBO(193, 190, 190, 1),
                              borderRadius: BorderRadius.circular(10),
                            ),
                            child:
                             TextField(
                              textAlign: TextAlign.center,
                                enabled: _enabledHouse,
                                cursorHeight: 15,
                                decoration: const InputDecoration(
                                  border: InputBorder.none,
                                  hintText: '245 m²',
                                  hintStyle: TextStyle(
                                    color: Colors.black,
                                    fontSize: 15,
                                    fontWeight: FontWeight.bold
                                  ),
                                ),
                              ), 
                            //const Text("245 m²"),
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
                              color: Color.fromRGBO(193, 190, 190, 1),
                              borderRadius: BorderRadius.circular(10),
                            ),
                            child: 
                              TextField(
                                textAlign: TextAlign.center,
                                enabled: _enabledHouse,
                                cursorHeight: 15,
                                decoration: const InputDecoration(
                                  border: InputBorder.none,
                                  hintText: 'Isolation de classe G',
                                  hintStyle: TextStyle(
                                    color: Colors.black,
                                    fontSize: 15,
                                    fontWeight: FontWeight.bold
                                  ),
                                ),
                              ),
                            ),),),                      
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