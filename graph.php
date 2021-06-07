<?php
    $title = "グラフ";
    require('./header.php');
    $page = "graph";
    $labels = [];
    $datetime_list = [];
    for ($i = 6; $i >= 0; $i--) {
        $m = (int)date("m", strtotime("-"."$i"." day"));
        $d = (int)date("d", strtotime("-"."$i"." day"));
        $labels[] = $m."月".$d."日";
        $datetime_list[] = date("Y-m-d", strtotime("-"."$i"." day"));
    }
    $start = date("Y-m-d", strtotime("-6 day"));
    $start .= " 00:00:00";
    $end = date("Y-m-d", strtotime("-0 day"));
    $end .= " 23:59:59";

    include "./pdo_connect.php";
    $sql = "SELECT * FROM score JOIN music WHERE score.id = music.id AND score.datetime BETWEEN '$start' AND '$end' ORDER BY score.datetime ASC";
    $stmt = $pdo -> query($sql);
    $cnt = 0;
    $max_list = [0, 0, 0, 0, 0, 0, 0];
    $min_list = [200, 200, 200, 200, 200, 200, 200];
    foreach($stmt as $row){
        for ($i = $cnt; $i < 7; $i++) {
            if ($datetime_list[$i]." 00:00:00" <= $row['datetime'] && $row['datetime'] <= $datetime_list[$i]." 99:99:99") {
                if ($max_list[$cnt] < ($row['score'] / $row['max']) * 100) {
                    $max_list[$cnt] = ($row['score'] / $row['max']) * 100;
                }
                if ($min_list[$cnt] > ($row['score'] / $row['max']) * 100) {
                    $min_list[$cnt] = ($row['score'] / $row['max']) * 100;
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.bundle.js"></script>

  <script>
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
    require('./footer.php');
?>