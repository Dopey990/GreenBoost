import 'package:flutter/material.dart';
import 'dart:html';
import 'package:flutter/material.dart';
import 'dart:convert';
import 'dart:async';
import 'dart:js';
import 'package:http/http.dart' as http;
import 'package:charts_flutter/flutter.dart' as charts;

class WaterTemperatureChart extends StatefulWidget {
  @override
  _WaterTemperatureChartState createState() => _WaterTemperatureChartState();
}

class _WaterTemperatureChartState extends State<WaterTemperatureChart> {
  late List<charts.Series<WaterTemperatureData, String>> _data;

  @override
  void initState() {
    super.initState();
    _fetchData();
  }

  void _fetchData() async {
    final response = await http.get(
        "https://hubeau.eaufrance.fr/page/api-temperature-en-continu-cours-deau/v1/temperature/chronique/");
    if (response.statusCode == 200) {
      final List<WaterTemperatureData> data = [];
      final Map<String, dynamic> jsonData = json.decode(response.body);
      jsonData['data'].forEach((datapoint) {
        data.add(
            WaterTemperatureData(datapoint['time'], datapoint['water_temp']));
      });
      setState(() {
        _data = [
          charts.Series<WaterTemperatureData, String>(
            id: 'Water Temperature',
            data: data,
            domainFn: (WaterTemperatureData data, _) => data.time,
            measureFn: (WaterTemperatureData data, _) => data.temperature,
          )
        ];
      });
    }
  }

  @override
  Widget build(BuildContext context) {
    return _data == null
        ? Center(child: CircularProgressIndicator())
        : Container(
            height: 300,
            padding: EdgeInsets.all(20),
            child: charts.LineChart(_data,
                animate: true,
                defaultRenderer: charts.LineRendererConfig(includeArea: true)),
          );
  }
}

class WaterTemperatureData {
  final String time;
  final double temperature;

  WaterTemperatureData(this.time, this.temperature);
}
