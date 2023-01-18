import 'package:GreenBoost/classementPage.dart';
import 'package:flutter/material.dart';
import '/components/menu.dart';
import 'package:GreenBoost/settingsProfilePage.dart';

class UserRankingsPage extends StatefulWidget {
  @override
  _UserRankingsPageState createState() => _UserRankingsPageState();
}

class _UserRankingsPageState extends State<UserRankingsPage> {
  final TextEditingController _searchController = TextEditingController();
  String _searchText = '';
  List<User> _users = [];

  @override
  void initState() {
    super.initState();
    _populateUsers();
  }

  void _populateUsers() {
    // add des users ici
  }

  Widget _buildSearchField() {
    return TextField(
      controller: _searchController,
      decoration: InputDecoration(
        hintText: 'Rechercher un utilisateur',
        prefixIcon: Icon(Icons.search),
        border: OutlineInputBorder(
          borderRadius: BorderRadius.circular(12),
        ),
      ),
      onChanged: (text) {
        setState(() {
          _searchText = text;
        });
      },
    );
  }

  Widget _buildUserList() {
    List<User> users = _users;

    if (_searchText.isNotEmpty) {
      users = users
          .where((user) =>
              user.name.contains(_searchText) ||
              user.email.contains(_searchText))
          .toList();
    }

    return ListView.builder(
      itemCount: users.length,
      itemBuilder: (context, index) {
        final user = users[index];
        return ListTile(
          title: Text(user.name),
          subtitle: Text(user.email),
          leading: CircleAvatar(
            backgroundImage: NetworkImage(user.profilePictureUrl),
          ),
          trailing: Text('# ${index + 1}'),
        );
      },
    );
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        backgroundColor: Color.fromARGB(255, 178, 205, 185),
        title: Text('Classement des utilisateurs'),
      ),
      body: Column(
        children: [
          _buildSearchField(),
          Expanded(
            child: _buildUserList(),
          ),
        ],
      ),
    );
  }
}

//temp si j'ai pas le temps de le co au back
class User {
  final String name;
  final String email;
  final String profilePictureUrl;

  User(this.name, this.email, this.profilePictureUrl);
}
