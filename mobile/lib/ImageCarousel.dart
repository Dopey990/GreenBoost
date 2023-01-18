import 'package:GreenBoost/authmanager.dart';
import 'package:GreenBoost/loginPage.dart';
import 'package:flutter/material.dart';

import 'homePage.dart';

class ImageCarousel extends StatefulWidget {
  @override
  _ImageCarouselState createState() => _ImageCarouselState();
}

class _ImageCarouselState extends State<ImageCarousel> {
  final List<String> images = [
    "https://cdn.futura-sciences.com/buildsv6/images/largeoriginal/5/2/1/5218135c58_50181753_crise-mondiale-eau.jpg",
    "https://www.example.com/image2.jpg",
    "https://www.example.com/image3.jpg",
    "https://www.example.com/image4.jpg",
    "https://www.example.com/image5.jpg",
    "https://www.example.com/image6.jpg",
  ];

  int _currentPage = 0;

  @override
  Widget build(BuildContext context) {
    return Container(
      width: double.infinity,
      height: 200.0,
      child: PageView.builder(
        itemCount: images.length,
        onPageChanged: (index) {
          setState(() {
            _currentPage = index;
          });
        },
        itemBuilder: (context, index) {
          return Image.network(
            images[index],
            fit: BoxFit.cover,
          );
        },
        controller: PageController(initialPage: 0, viewportFraction: 0.8),
      ),
    );
  }
}
