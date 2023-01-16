import 'package:flutter/material.dart';

class popUp extends StatefulWidget {
  final String text;

  const popUp({super.key, required this.text});

  @override
  State<StatefulWidget> createState() => popUpState();
}

class popUpState extends State<popUp> {
  @override
  Widget build(BuildContext context) {
    return Container( child :
    AlertDialog(
          title: Text(widget.text),
          content: Column(
            mainAxisSize: MainAxisSize.min,
            crossAxisAlignment: CrossAxisAlignment.start,
          ),
          actions: <Widget>[
            ElevatedButton(
              onPressed: () {
                Navigator.of(context).pop();
              },
              child: const Text('Ok'),
            ),
          ],
        ));
  }
}


