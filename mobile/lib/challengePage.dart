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
      body:
      SingleChildScrollView(
        child: 
      Column(
        children: [
          Padding(
            padding: const EdgeInsets.only(top: 1.0, bottom:8),
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
            width: MediaQuery.of(context).size.width * 0.9,
            height: MediaQuery.of(context).size.height * 0.7,
            decoration: BoxDecoration(
              color: Color.fromARGB(255, 20, 151, 171),
              borderRadius: BorderRadius.circular(10),
            ),
            child :                    
              ListView(
                shrinkWrap: true,
                padding: EdgeInsets.only(top:8, bottom:8, left: 5, right: 5),
                children: const <Widget>[
                  Card(
                    color: Color.fromARGB(255, 93, 147, 155),
                    child: ListTile(
                      title: Text('Challenge 1'),
                      subtitle: Text('Description of the challenge'),
                      trailing: IconButton(
                        icon : Icon(Icons.check),
                        color : Color.fromARGB(255, 12, 227, 19),
                        onPressed: null,
                      ),
                      ),
                  ),
                  Card(
                    color: Color.fromARGB(255, 93, 147, 155),
                    child: ListTile(
                      title: Text('Challenge 2'),
                      subtitle: Text('Description of the challenge'),
                      trailing: IconButton(
                        icon : Icon(Icons.check),
                        color : Color.fromARGB(255, 12, 227, 19),
                        onPressed: null,
                        ),
                    ),
                  ),
                  Card(
                    color: Color.fromARGB(255, 93, 147, 155),
                    child: ListTile(
                      title: Text('Challenge 3'),
                      subtitle: Text('Description of the challenge'),
                      trailing: IconButton(
                        icon : Icon(Icons.check),
                        color : Color.fromARGB(255, 12, 227, 19),
                        onPressed: null,
                        ),
                    ),
                  ),
                  Card(
                    color: Color.fromARGB(255, 93, 147, 155),
                    child: ListTile(
                      title: Text('Challenge 4'),
                      subtitle: Text('Description of the challenge'),
                      trailing: IconButton(
                        icon : Icon(Icons.check),
                        color : Color.fromARGB(255, 12, 227, 19),
                        onPressed: null,
                        ),
                    ),
                  ),
                  Card(
                    color: Color.fromARGB(255, 93, 147, 155),
                    child: ListTile(
                      title: Text('Challenge 5'),
                      subtitle: Text('Description of the challenge'),
                      trailing: IconButton(
                        icon : Icon(Icons.check),
                        color : Color.fromARGB(255, 12, 227, 19),
                        onPressed: null,
                        ),
                    ),
                  ),
                  Card(
                    color: Color.fromARGB(255, 93, 147, 155),
                    child: ListTile(
                      title: Text('Challenge 6'),
                      subtitle: Text('Description of the challenge'),
                      trailing: IconButton(
                        icon : Icon(Icons.check),
                        color : Color.fromARGB(255, 12, 227, 19),
                        onPressed: null,
                        ),
                    ),
                  ),
                  Card(
                    color: Color.fromARGB(255, 93, 147, 155),
                    child: ListTile(
                      title: Text('Challenge 7'),
                      subtitle: Text('Description of the challenge'),
                      trailing: IconButton(
                        icon : Icon(Icons.check),
                        color : Color.fromARGB(255, 12, 227, 19),
                        onPressed: null,
                        ),
                    ),
                  ),
                  Card(
                    color: Color.fromARGB(255, 93, 147, 155),
                    child: ListTile(
                      title: Text('Challenge 8'),
                      subtitle: Text('Description of the challenge'),
                      trailing: IconButton(
                        icon : Icon(Icons.check),
                        color : Color.fromARGB(255, 12, 227, 19),
                        onPressed: null,
                        ),
                    ),
                  ),
                ],
              ),
          ),
          
          ],
        ),  
      ),
    );
  }
}

