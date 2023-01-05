import 'dart:html';

import 'package:flutter/material.dart';
import 'dart:convert';
import 'package:http/http.dart' as http;

class AuthManager extends InheritedWidget {
  String? token;

  AuthManager({super.key, child}) : super(child: child);

  @override
  bool updateShouldNotify(covariant InheritedWidget oldWidget) {
    // TODO: implement updateShouldNotify
    throw UnimplementedError();
  }

  Map<String, String> get headers => {
        'Content-Type': 'application/json; charset=UTF-8',
        "Accept": "application/json"
      };

  Future<http.Response> _loginHttp(String email, String password) async {
    String body = jsonEncode({'email': email, 'password': password});

    http.Response response = await http.post(
        Uri.parse("http://localhost:8080/authentication/login"),
        headers: headers,
        body: body);

    return response;
  }

  Future<bool> login(String email, String password) async {
    var response = await _loginHttp(email, password);
    if (response.statusCode != HttpStatus.ok) {
      token = null;
      return false;
    }

    //TODO : refactor au besoin
    token = response.body;
    return true;
  }

  static AuthManager? of(BuildContext context) {
    return context.dependOnInheritedWidgetOfExactType<AuthManager>();
  }
}
