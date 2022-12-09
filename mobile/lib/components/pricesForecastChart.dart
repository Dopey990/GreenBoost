import 'dart:math';
import 'package:fl_chart/fl_chart.dart';
import 'package:flutter/material.dart';

class PricesForecastChart extends StatelessWidget {
  List<double> values;
  final Color titlesColor = const Color.fromARGB(255, 255, 153, 0);

  PricesForecastChart({super.key, required this.values});

  @override
  Widget build(BuildContext context) {
    return LineChart(
      linesData,
      swapAnimationDuration: const Duration(milliseconds: 250),
    );
  }

  LineChartData get linesData => LineChartData(
      lineTouchData: lineTouchData,
      gridData: gridData,
      titlesData: titlesData,
      borderData: borderData,
      lineBarsData: [lineChartBarData],
      minX: 0,
      maxX: 24,
      minY: 0,
      maxY: (((values.reduce(max) + 50) / 50) * 50).round().toDouble());

  LineTouchData get lineTouchData => LineTouchData(
        handleBuiltInTouches: true,
        touchTooltipData: LineTouchTooltipData(
          tooltipBgColor: Colors.blueGrey.withOpacity(0.8),
          getTooltipItems: (touchedSpots) => touchedSpots
              .map((spot) => LineTooltipItem(
                    "${values[spot.barIndex]}€",
                    const TextStyle(
                      color: Colors.white,
                      fontWeight: FontWeight.bold,
                      fontSize: 14,
                    ),
                  ))
              .toList(),
        ),
      );

  FlTitlesData get titlesData => FlTitlesData(
        bottomTitles: AxisTitles(
          sideTitles: bottomTitles,
        ),
        rightTitles: AxisTitles(
          sideTitles: SideTitles(showTitles: false),
        ),
        topTitles: AxisTitles(
          sideTitles: SideTitles(showTitles: false),
        ),
        leftTitles: AxisTitles(
          sideTitles: leftTitles(),
        ),
      );

  Widget leftTitleWidgets(double value, TitleMeta meta) {
    return Text("$value€",
        style: TextStyle(
          color: titlesColor,
          fontWeight: FontWeight.bold,
          fontSize: 14,
        ),
        textAlign: TextAlign.center);
  }

  SideTitles leftTitles() => SideTitles(
        getTitlesWidget: leftTitleWidgets,
        showTitles: true,
        interval: 50,
        reservedSize: 40,
      );

  Widget bottomTitleWidgets(double value, TitleMeta meta) {
    return SideTitleWidget(
      axisSide: meta.axisSide,
      space: 10,
      child: Text("${value}h",
          style: TextStyle(
            color: titlesColor,
            fontWeight: FontWeight.bold,
            fontSize: 16,
          )),
    );
  }

  SideTitles get bottomTitles => SideTitles(
        showTitles: true,
        reservedSize: 32,
        interval: 1,
        getTitlesWidget: bottomTitleWidgets,
      );

  FlGridData get gridData => FlGridData(show: true);

  FlBorderData get borderData => FlBorderData(
        show: true,
        border: Border(
          bottom: BorderSide(color: titlesColor, width: 4),
          left: const BorderSide(color: Colors.transparent),
          right: const BorderSide(color: Colors.transparent),
          top: const BorderSide(color: Colors.transparent),
        ),
      );

  LineChartBarData get lineChartBarData => LineChartBarData(
      isCurved: true,
      gradient: const LinearGradient(
        begin: Alignment.topCenter,
        end: Alignment.bottomCenter,
        colors: <Color>[
          Color(0xffff3126),
          Color(0xfff17000),
          Color(0xffcf9e00),
          Color(0xff9bc400),
          Color(0xff36e327),
        ],
        tileMode: TileMode.mirror,
      ),
      barWidth: 8,
      isStrokeCapRound: true,
      dotData: FlDotData(show: false),
      belowBarData: BarAreaData(show: false),
      spots: values
          .asMap()
          .entries
          .map((entry) => FlSpot(entry.key.toDouble(), entry.value))
          .toList());
}

class PricesForecastWidget extends StatefulWidget {
  String chartTitle;
  List<double> values;
  Function() refreshCallback;

  PricesForecastWidget(
      {super.key,
      required this.values,
      required this.chartTitle,
      required this.refreshCallback});

  @override
  State<StatefulWidget> createState() => PricesForecastState();
}

class PricesForecastState extends State<PricesForecastWidget> {
  @override
  void initState() {
    super.initState();
  }

  @override
  Widget build(BuildContext context) {
    return Padding(
        padding: const EdgeInsets.all(10),
        child: AspectRatio(
          aspectRatio: 1.5,
          child: DecoratedBox(
            decoration: const BoxDecoration(
              borderRadius: BorderRadius.all(Radius.circular(18)),
              gradient: LinearGradient(
                colors: [
                  Color.fromARGB(255, 7, 73, 21),
                  Color.fromARGB(255, 3, 51, 43)
                ],
                begin: Alignment.bottomCenter,
                end: Alignment.topCenter,
              ),
            ),
            child: Stack(
              children: <Widget>[
                Column(
                  crossAxisAlignment: CrossAxisAlignment.stretch,
                  children: <Widget>[
                    const SizedBox(
                      height: 37,
                    ),
                    const Text(
                      "Durant ces 24h",
                      style: TextStyle(
                        color: Color(0xff827daa),
                        fontSize: 16,
                      ),
                      textAlign: TextAlign.center,
                    ),
                    const SizedBox(
                      height: 4,
                    ),
                    Text(
                      widget.chartTitle,
                      style: const TextStyle(
                        color: Colors.white,
                        fontSize: 32,
                        fontWeight: FontWeight.bold,
                        letterSpacing: 2,
                      ),
                      textAlign: TextAlign.center,
                    ),
                    const SizedBox(
                      height: 37,
                    ),
                    Expanded(
                      child: Padding(
                        padding: const EdgeInsets.only(right: 16, left: 6),
                        child: PricesForecastChart(values: widget.values),
                      ),
                    ),
                    const SizedBox(
                      height: 10,
                    ),
                  ],
                ),
                IconButton(
                  icon: Icon(
                    Icons.refresh,
                    color: Colors.white.withOpacity(1.0),
                  ),
                  onPressed: () {
                    setState(() {
                      widget.refreshCallback();
                    });
                  },
                )
              ],
            ),
          ),
        ));
  }
}
