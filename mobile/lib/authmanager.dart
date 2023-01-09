import 'dart:html';

import 'package:flutter/material.dart';
import 'dart:convert';
import 'package:http/http.dart' as http;

// ignore: must_be_immutable
class AuthManager extends InheritedWidget {
  String? token;

  AuthManager({super.key, child}) : super(child: child);

  @override
  bool updateShouldNotify(AuthManager oldWidget) {
    return token != oldWidget.token;
  }

  Map<String, String> get headers => {
        'Content-Type': 'application/json; charset=UTF-8',
        "Accept": "application/json"
            "Access-Control-Allow-Origin: *"
      };

  Future<http.Response> _loginHttp(String email, String password) async {
    http.Response response = await http.get(Uri.parse(
        'http://localhost:8080/user/getUserToken?email=$email&password=$password'));
    return response;
  }

  Future<bool> login(String email, String password) async {
    final response = await _loginHttp(email, password);
    if (response.statusCode != HttpStatus.ok) {
      token = null;
      return false;
    }

    final user = json.decode(response.body) as Map<String, dynamic>;
    token = user['token'];
    return true;
  }

  static AuthManager? of(BuildContext context) {
    return context.dependOnInheritedWidgetOfExactType<AuthManager>();
  }
}
