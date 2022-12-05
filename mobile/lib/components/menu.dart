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
            color: Colors.grey.shade500,
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
                  fontSize: 20,
                )),
                ],
            ),
          ],
        ),),
        ListTile(
          title: Text('Home'),
          onTap: () {
            Navigator.pop(context);
            Navigator.pushNamed(context, '/home');
          },
        ),
        ListTile(
          title: Text('About'),
          onTap: () {
            Navigator.pop(context);
            Navigator.pushNamed(context, '/about');
          },
        ),
        ListTile(
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
