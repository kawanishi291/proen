<?php
    // <title></title>に表示する変数
    $title = "グラフ";
    // ヘッダー部分をimport
    require('./header.php');
    // どのページか判定する変数
    $page = "graph";
    // グラフの x軸(日付)配列
    $labels = [];
    // date配列
    $datetime_list = [];
    // 本日から一週間前の日付のみ取得
    for ($i = 6; $i >= 0; $i--) {
        $m = (int)date("m", strtotime("-"."$i"." day"));
        $d = (int)date("d", strtotime("-"."$i"." day"));
        $labels[] = $m."月".$d."日";
        $datetime_list[] = date("Y-m-d", strtotime("-"."$i"." day"));
    }
    // SQL文・BETWEEN条件(過去一週間)
    $start = date("Y-m-d", strtotime("-6 day"));
    $start .= " 00:00:00";
    $end = date("Y-m-d", strtotime("-0 day"));
    $end .= " 23:59:59";

    // DB接続処理include
    include "./pdo_connect.php";
    // SQL文
    $sql = "SELECT * FROM score JOIN music WHERE score.id = music.id AND score.datetime BETWEEN '$start' AND '$end' ORDER BY score.datetime ASC";
    $stmt = $pdo -> query($sql);
    $cnt = 0;
    // 最高得点率配列
    $max_list = [0, 0, 0, 0, 0, 0, 0];
    // 最低得点率配列
    $min_list = [200, 200, 200, 200, 200, 200, 200];
    foreach($stmt as $row){
        // その日付ごとの最高得点率、最低得点率を配列に格納
        for ($i = $cnt; $i < 7; $i++) {
            if ($datetime_list[$i]." 00:00:00" <= $row['datetime'] && $row['datetime'] <= $datetime_list[$i]." 99:99:99") {
                if ($max_list[$cnt] < ($row['score'] / $row['max']) * 100) {
                    $max_list[$cnt] = round(($row['score'] / $row['max']) * 100);
                }
                if ($min_list[$cnt] > ($row['score'] / $row['max']) * 100) {
                    $min_list[$cnt] = round(($row['score'] / $row['max']) * 100);
                }
                break;
            } else {
                $cnt ++;
            }
        }
    }
    for ($i = 0; $i < 7; $i++) {
        if ($min_list[$i] == 200) {
            $min_list[$i] = 0;
        }
    }
?>
<h1>得点率グラフ</h1>
  <canvas id="myLineChart"></canvas>
    <!-- Chart.js CDN -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.bundle.js"></script>

  <script>
  // グラフ描画設定
  var ctx = document.getElementById("myLineChart");
  var myLineChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: ['<?=$labels[0]?>', '<?=$labels[1]?>', '<?=$labels[2]?>', '<?=$labels[3]?>', '<?=$labels[4]?>', '<?=$labels[5]?>', '<?=$labels[6]?>'],
      datasets: [
        {
          label: '最高得点率(%）',
          data: [<?=$max_list[0]?>, <?=$max_list[1]?>, <?=$max_list[2]?>, <?=$max_list[3]?>, <?=$max_list[4]?>, <?=$max_list[5]?>, <?=$max_list[6]?>],
          borderColor: "rgba(255,0,0,1)",
          backgroundColor: "rgba(0,0,0,0)"
        },
        {
          label: '最低得点率(%）',
          data: [<?=$min_list[0]?>, <?=$min_list[1]?>, <?=$min_list[2]?>, <?=$min_list[3]?>, <?=$min_list[4]?>, <?=$min_list[5]?>, <?=$min_list[6]?>],
          borderColor: "rgba(0,0,255,1)",
          backgroundColor: "rgba(0,0,0,0)"
        }
      ],
    },
    options: {
      title: {
        display: true,
        text: '得点率（<?=$labels[0]?> ~ <?=$labels[6]?>）'
      },
      scales: {
        yAxes: [{
          ticks: {
            suggestedMax: 100,
            suggestedMin: 0,
            stepSize: 10,
            callback: function(value, index, values){
              return  value +  '%'
            }
          }
        }]
      },
    }
  });
  </script>
<?php
    // フッター部分をimport
    require('./footer.php');
?>