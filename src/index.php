<!DOCTYPE html>
<html lang="zh-CN">

<head>
  <meta charset="UTF-8">
  <title>Echarts</title>
  <style>
    * {
      padding: 0px;
      margin: 0px;
    }

    html,
    body {
      width: 100%;
      height: 100%;
    }

    .container2 {
      width: 100%;
      height: 100%;
      position: relative;
      background-color: coral;
    }

    select {
      width: 30% !important;
      position: absolute;
      top: 50px;
      left: 0;
      right: 0;
      margin: auto !important;
    }
  </style>
  <link rel="stylesheet" href="./lib/bootstrap/bootstrap.css">
</head>
<script src="./lib/jquery.min.js"></script>
<script src="./lib/echarts.min.js"></script>
<script src="./lib/world.js"></script>
<!-- <script src="./lib/worldjson.js"></script> -->
<script src="./lib/worldjsonWithPrediction.js"></script>
<script src="./lib/bootstrap/bootstrap.js"></script>

<body>
  <div class="container2" id="chart">
  </div>
  <select class="form-control" onchange="handleSelect()">

  </select>

  <script>
    // 页面加载
    var chart = null;
    var option = null;
    window.onload = function () {
      let chartDom = document.querySelector("#chart");
      chart = echarts.init(chartDom);
      option = {
        backgroundColor: '#fff',
        grid: {
          top: '40%'
        },
        title: {
          top: "15",
          text: '2017 Carbon Dioxide World Map',
          left: 'center'
        },
        roam: true,
        tooltip: {
          trigger: "item",
          formatter: function (params) {
            if (params.data) {
              return `${params.data.name}<br /> Year:${params.data.year}<br />CO2:${params.data.value}`
            } else {
              return "No Data"
            }
          }
        },
        visualMap: {
          tyep: "continuous",
          // min: 0
          max: 10000000000,
          show: true,
          left: 'left',
          top: 'bottom',
          text: ['high', 'low'], // 文本，默认为数值文本
          calculable: true,
          seriesIndex: [0],
          inRange: {
            color: ['rgba(255, 255, 51,0.1)', 'rgba(238,25,27,1)'],
            //symbolSize: [10, 50]
          }
        },
        series: [{
          type: 'map',
          mapType: 'world',
          name: "2017",
          itemStyle: {
            areaColor: 'rgba(0,0,0,0)',
            borderColor: '#72a8ff',

          },
          emphasis: {
            itemStyle: {
              areaColor: 'rgba(0,0,0,0)'
            },
            label: {
              normal: {
                show: false,
              },
            }
          },
          label: {
            normal: {
              show: false,
            }
          },
          data: []
        }]
      };
      initSelect();
      initChart(chart, option);

    }

    function initSelect() {
      let html = "";
      //  for(let i = 1949; i <= 2022; i++) {
      //    html += `<option selected="${i == 2017}">${i}</option>`;
      //  }
      for (let i = 1949; i <= 2099; i++) {
        if (i == 2017) {
          html += `<option selected="selected">${i}</option>`;
        } else {
          html += `<option>${i}</option>`;
        }
      }
      document.querySelector("select").innerHTML = html;
    }

    async function initChart(chart, option) {
      let co2Data = await getCO2Data();
      let seriesData = handleDataToSeries(co2Data, "2017");
      option.series[0].data = seriesData;
      chart.setOption(option);
    }

    // 二氧化碳数据转换成Echarts Series Data
    function handleDataToSeries(data, year) {
      return data.map(item => {
        if (item.Year == year) {
          return {
            name: item.Entity,
            year: item.Year,
            value: item["Annual CO2 emissions (tonnes )"]
          }
        }
      })
    }

    // 获取二氧化碳数据
    function getCO2Data() {
      return new Promise((resolve, reject) => {
        resolve(member.concat(prediction));
        //$.ajax({
        //    url: "./lib/world.json",
        //    dataType: 'json',
        //    type: "GET",
        //  success: function(res){
        //    resolve(res);
        //  }
        //})
      })
    }

    // 年份Select
    async function handleSelect(e) {
      e = e || window.event;
      let list = e.target.children;
      let res = [...list].filter(item => {
        return item.selected;
      })
      let Year = res[0].innerHTML;
      let co2Data = await getCO2Data();
      let seriesData = handleDataToSeries(co2Data, Year);
      option.series[0].data = seriesData;
      option.title.text = `${Year} Carbon Dioxide World Map`
      chart.setOption(option);
    }
  </script>
</body>

</html>
