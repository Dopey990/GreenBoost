//create a component to display the menu
import 'package:flutter/material.dart';

class Menu extends StatelessWidget {
  const Menu({super.key});

  @override
  Widget build(BuildContext context) {
    return Drawer(child: ListView(
      children: <Widget>
      [
        
        DrawerHeader(
          decoration: BoxDecoration(
            // color 3D69D9
            color: Color(0xFF3D69D9),
          ),
        
        child: Row(
          mainAxisAlignment: MainAxisAlignment.spaceEvenly,
          children: <Widget>[
            CircleAvatar(
              child: Icon(Icons.person),
              radius: 40,
            ),
            Column(
              mainAxisAlignment: MainAxisAlignment.center,
              crossAxisAlignment: CrossAxisAlignment.start,
              children: <Widget>[
                Text('John Doe',
                style:TextStyle(
                  fontWeight: FontWeight.bold,
                  color: Colors.white,
                  fontSize: 25,
                )),
                Text('Score : 100',
                style:TextStyle(
                  color: Colors.white,
                  fontSize: 15,
                )),
                ],
            ),
          ],
        ),),
        ListTile(
          leading: Icon(Icons.home),
          title: Text('Accueil'),
          onTap: () {
            Navigator.pop(context);
            Navigator.pushNamed(context, '/home');
          },
        ),
        ListTile(
          leading: Icon(Icons.flash_on),
          title: Text('Consommation'),
          onTap: () {
            Navigator.pop(context);
            Navigator.pushNamed(context, '/consommation');
          },
        ),
        ListTile(
          //icon winner /compost
          leading: Icon(Icons.star),
          title: Text('Challenges'),
          onTap: () {
            Navigator.pop(context);
            Navigator.pushNamed(context, '/challenge');
          },
        ),
        ListTile(
          //icon contact
          leading: Icon(Icons.contact_mail),
          title: Text('Contact'),
          onTap: () {
            Navigator.pop(context);
            Navigator.pushNamed(context, '/contact');
          },
        ),
      ],
    ));
  }
}
