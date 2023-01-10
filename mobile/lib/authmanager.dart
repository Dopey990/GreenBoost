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
    int status = response.statusCode - HttpStatus.ok;
    if (0 > status && status >= 100) {
      //If not a 2xx status, login failed (dsl c guetto mais ca tourne issou)
      print("Login failed with status : ${response.statusCode}");
      token = null;
      return false;
    }
    print("Login OK");
    //final user = json.decode(response.body) as Map<String, dynamic>;
    //token = user['token'];
    token = response.body;
    return true;
  }

  static AuthManager? of(BuildContext context) {
    return context.dependOnInheritedWidgetOfExactType<AuthManager>();
  }

  Future<bool> createUser(
      String email, String password, String firstname, String lastname) async {
    final Map<String, dynamic> data = {
      'email': email,
      'password': password,
      'firstname': firstname,
      'lastname': lastname,
    };
    final String body = json.encode(data);

    final http.Response response = await http.post(
      Uri.parse('http://localhost:8080/user/createUser/'),
      headers: {'Content-Type': 'application/json'},
      body: body,
    );

    if (response.statusCode != HttpStatus.created) {
      print('Failed to create user');
      return false;
    }
    return true;
  }
}
