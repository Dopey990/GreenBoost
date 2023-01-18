import 'dart:convert';
import 'dart:math';

import 'package:GreenBoost/classementPage.dart';
import 'package:GreenBoost/components/popUp.dart';
import 'package:flutter/material.dart';
import 'package:shared_preferences/shared_preferences.dart';
import '/components/menu.dart';
import 'package:GreenBoost/settingsProfilePage.dart';

import 'package:http/http.dart' as http;

class ChallengePage extends StatefulWidget {
  const ChallengePage({super.key});

  @override
  State<StatefulWidget> createState() => ChallengeState();
}

class ChallengeState extends State<ChallengePage> {
  late Future<List<Map<String, dynamic>>> challenges;
  late Future<Map<String, dynamic>> user;
  List<TextEditingController> controllers = [];

  @override
  void initState() {
    user = getUser();
    challenges = getChallenges();
    super.initState();
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: const Color.fromRGBO(168, 203, 208, 1),
      appBar: AppBar(
        centerTitle: true,
        title: const Text('Challenges'),
      ),
      drawer: const Menu(),
      body: SingleChildScrollView(
        child: Column(
          children: [
            Padding(
              padding: const EdgeInsets.only(top: 1.0, bottom: 8),
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
                  color: const Color.fromARGB(255, 20, 151, 171),
                  borderRadius: BorderRadius.circular(10),
                ),
                child: FutureBuilder<List<Map<String, dynamic>>>(
                    future: challenges,
                    builder: (BuildContext context,
                        AsyncSnapshot<List<Map<String, dynamic>>> snapshot) {
                      if (snapshot.hasData) {
                        var data = snapshot.data!;
                        controllers.clear();
                        for (int i = 0; i < data.length; i++) {
                          controllers.add(TextEditingController());
                        }

                        data.map(
                            (e) => controllers.add(TextEditingController()));

                        return data.isEmpty
                            ? const Text(
                                "Vous avez terminé tous les défis, bravo !")
                            : ListView(
                                shrinkWrap: true,
                                padding: const EdgeInsets.only(
                                    top: 8, bottom: 8, left: 5, right: 5),
                                children: Iterable<int>.generate(data.length)
                                    .toList()
                                    .map((index) => Card(
                                        color: Color.fromARGB(255, 166, 182, 185),
                                        child: Row(children: [
                                          SizedBox(
                                              width: MediaQuery.of(context)
                                                      .size
                                                      .width *
                                                  0.6,
                                              child: ListTile(
                                                title: Text(
                                                    "${data[index]["value"]}"),
                                                subtitle: Text(
                                                    "${data[index]["category"]}"),
                                              )),
                                          data[index]["hasAnswers"] == true
                                              ? Column(children: [
                                                  Padding(
                                                      padding:
                                                          const EdgeInsets.all(
                                                              5),
                                                      //width: 90,
                                                      child: SizedBox(
                                                          width: 90,
                                                          child: TextField(
                                                            controller:
                                                                controllers[
                                                                    index],
                                                            decoration:
                                                                const InputDecoration(
                                                              border:
                                                                  OutlineInputBorder(),
                                                              hintText:
                                                                  'Réponse',
                                                            ),
                                                          ))),
                                                  IconButton(
                                                    icon:
                                                        const Icon(Icons.check),
                                                    color: const Color.fromARGB(
                                                        255, 12, 227, 19),
                                                    onPressed: (() async {
                                                      int points =
                                                          await answerChallenge(
                                                              data[index]["id"],
                                                              controllers[index]
                                                                  .text);

                                                      List<Map<String, dynamic>>
                                                          selfChallenges =
                                                          await challenges;

                                                      setState(() {
                                                        selfChallenges
                                                            .removeWhere(
                                                                (item) =>
                                                                    item[
                                                                        "id"] ==
                                                                    data[index]
                                                                        ["id"]);
                                                      });

                                                      showDialog(
                                                          context: context,
                                                          builder: (BuildContext
                                                                  context) =>
                                                              PopUp(
                                                                  text:
                                                                      "Vous avez ${points > 0 ? "gagné" : "perdu"} $points points"));
                                                    }),
                                                  ),
                                                ])
                                              : Row(children: [
                                                  IconButton(
                                                      icon: const Icon(
                                                          Icons.check),
                                                      color:
                                                          const Color.fromARGB(
                                                              255, 12, 227, 19),
                                                      onPressed: (() async {
                                                        int points =
                                                            await answerChallenge(
                                                                data[index]
                                                                    ["id"],
                                                                "true");

                                                        List<
                                                                Map<String,
                                                                    dynamic>>
                                                            selfChallenges =
                                                            await challenges;

                                                        setState(() {
                                                          selfChallenges
                                                              .removeWhere((item) =>
                                                                  item["id"] ==
                                                                  data[index]
                                                                      ["id"]);
                                                        });

                                                        showDialog(
                                                            context: context,
                                                            builder: (BuildContext
                                                                    context) =>
                                                                PopUp(
                                                                    text:
                                                                        "Vous avez ${points > 0 ? "gagné" : "perdu"} $points points"));
                                                      })),
                                                  IconButton(
                                                      icon: const Icon(
                                                          Icons.close),
                                                      color:
                                                          const Color.fromARGB(
                                                              255, 12, 227, 19),
                                                      onPressed: (() async {
                                                        int points =
                                                            await answerChallenge(
                                                                data[index]
                                                                    ["id"],
                                                                "false");

                                                        List<
                                                                Map<String,
                                                                    dynamic>>
                                                            selfChallenges =
                                                            await challenges;

                                                        setState(() {
                                                          selfChallenges
                                                              .removeWhere((item) =>
                                                                  item["id"] ==
                                                                  data[index]
                                                                      ["id"]);
                                                        });
                                                        showDialog(
                                                            context: context,
                                                            builder: (BuildContext
                                                                    context) =>
                                                                PopUp(
                                                                    text:
                                                                        "Vous avez ${points > 0 ? "gagné" : "perdu"} ${points < 0 ? (points * -1) : points} points"));
                                                      }))
                                                ])
                                        ])))
                                    .toList());
                      } else {
                        return const Padding(
                            padding: EdgeInsets.all(20.0),
                            child: CircularProgressIndicator());
                      }
                    }))
          ],
        ),
      ),
    );
  }

  Future<Map<String, dynamic>> getUser() async {
    SharedPreferences prefs = await SharedPreferences.getInstance();
    Map<String, dynamic> userMap =
        jsonDecode(prefs.getString('user')!) as Map<String, dynamic>;

    return userMap;
  }

  Future<List<Map<String, dynamic>>> getChallenges() async {
    Map<String, dynamic> user = await getUser();

    final response = await http.get(Uri.parse(
        "http://localhost:8080/challenges/getChallengeForUser?token=${user["token"]}"));

    List<Map<String, dynamic>> result = [];

    if (response.statusCode == 200) {
      jsonDecode(response.body).forEach((line) {
        result.add(line);
      });

      return result;
    } else {
      throw Exception("Failed to load challenges");
    }
  }

  Future<int> answerChallenge(String challengeId, String answer) async {
    Map<String, dynamic> user = await getUser();

    final response = await http.post(Uri.parse(
        "http://localhost:8080/challenges/answerChallenge?challengeId=$challengeId&answer=$answer&token=${user["token"]}"));

    if (response.statusCode == 200) {
      return int.parse(response.body);
    } else {
      throw Exception("Failed to answer challenge");
    }
  }
}
