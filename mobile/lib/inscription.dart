import 'package:GreenBoost/inscription.dart';
import 'package:GreenBoost/forgotPasswordPage.dart';
import 'package:GreenBoost/subscriptionPage.dart';
import 'package:flutter/material.dart';
import 'dart:convert';
import 'dart:io';
import 'package:http/http.dart' as http;
import 'homePage.dart';

class inscription {
  static const String _createUserEndpoint =
      'http://127.0.0.1:8080/user/createUser/';

  static Future<void> createUser(
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

    if (response.statusCode != 200) {
      throw Exception('Failed to create user');
    }
  }
}
