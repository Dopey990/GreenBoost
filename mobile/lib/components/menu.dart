//create a component to display the menu
import 'package:GreenBoost/profilePage.dart';
import 'package:flutter/material.dart';

class Menu extends StatelessWidget {
  const Menu({super.key});

  @override
  Widget build(BuildContext context) {
    return Drawer(child: ListView(
      children: <Widget>
      [
        GestureDetector(
          onTap: () {
           Navigator.push(context, MaterialPageRoute(builder: (context) => ProfilePage()));
          },
          child:
            SizedBox(
              height: 115,
              child : 
                DrawerHeader(
                  decoration: const BoxDecoration(
                  color: Color(0xFF3D69D9),
                  ),
                  child: Row(
                    mainAxisAlignment: MainAxisAlignment.start,
                    children: <Widget>[
                      const CircleAvatar(
                        radius: 40,
                        child: Icon(Icons.person),
                      ),
                      Column(
                        mainAxisAlignment: MainAxisAlignment.center,
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: const [
                          Text('Alexandre',
                          style: TextStyle(
                            fontWeight: FontWeight.bold,
                            color: Colors.white,
                            fontSize: 25,
                          )),
                          Text('Score : 100',
                            style: TextStyle(
                            color: Colors.white,
                            fontSize: 15,
                          )),
                        ],
                      ),
                    ],
                  ),
                ),
            ),
        ),



        ListTile(
          leading: const Icon(Icons.home),
          title: const Text('Accueil'),
          onTap: () {
            Navigator.pop(context);
            Navigator.pushNamed(context, '/home');
          },
        ),
        ListTile(
          leading: const Icon(Icons.flash_on),
          title: const Text('Consommation'),
          onTap: () {
            Navigator.pop(context);
            Navigator.pushNamed(context, '/consommation');
          },
        ),
        ListTile(
          leading: const Icon(Icons.star),
          title: const Text('Challenges'),
          onTap: () {
            Navigator.pop(context);
            Navigator.pushNamed(context, '/challenges');
          },
        ),
        ListTile(
          leading: const Icon(Icons.contact_mail),
          title: const Text('Contact'),
          onTap: () {
            Navigator.pop(context);
            Navigator.pushNamed(context, '/contact');
          },
        ),
      ],
    ));
  }
}
