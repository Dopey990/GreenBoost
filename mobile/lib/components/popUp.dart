import 'package:flutter/material.dart';

class PopUp extends StatefulWidget {
  final String text;

  const PopUp({super.key, required this.text});

  @override
  State<StatefulWidget> createState() => PopUpState();
}

class PopUpState extends State<PopUp> {
  @override
  Widget build(BuildContext context) {
    return AlertDialog(
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
    );
  }
}
