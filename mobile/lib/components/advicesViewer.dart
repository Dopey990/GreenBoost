import 'package:flutter/material.dart';
import 'package:flutter_titled_container/flutter_titled_container.dart';

class AdvicesViewerWidget extends StatefulWidget {
  List<String> advices;

  AdvicesViewerWidget({super.key, required this.advices});

  @override
  State<StatefulWidget> createState() => AdvicesViewerState();
}

class AdvicesViewerState extends State<AdvicesViewerWidget> {
  String currentShownAdvice = "";

  @override
  void initState() {
    super.initState();
  }

  @override
  Widget build(BuildContext context) {
    return Center(
        child: Row(
      children: [
        IconButton(
            onPressed: () {
              print("ok");
            },
            icon: const Icon(Icons.check)),
        TitledContainer(
          titleColor: Colors.blue,
          title: "Conseils",
          textAlign: TextAlignTitledContainer.Left,
          fontSize: 16,
          backgroundColor: Colors.white,
          child: Container(
            width: MediaQuery.of(context).size.width / 2,
            height: 150,
            decoration: BoxDecoration(
              border: Border.all(
                color: Colors.blue,
              ),
              borderRadius: const BorderRadius.all(
                Radius.circular(10.0),
              ),
            ),
            child: Padding(
                padding: const EdgeInsets.only(top: 10.0),
                child: Text(
                  currentShownAdvice,
                  style: const TextStyle(fontSize: 28.0),
                  textAlign: TextAlign.center,
                )),
          ),
        ),
        IconButton(
            onPressed: () {
              print("ok");
            },
            icon: const Icon(Icons.close))
      ],
    ));
  }
}
