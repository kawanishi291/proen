<?php
    // <title></title>に表示する変数
    $title = "カレンダー";
    // ヘッダー部分をimport
    require('./header.php');
    // どのページか判定する変数
    $page = "calendar";
?>
<div id='calendar-box'></div>
<?php
    // フッター部分をimport
    require('./footer.php');
?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar-box');
    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        events: [
<?php
// DB接続処理include
include "./pdo_connect.php";
// SQL文
$sql = "SELECT * FROM score JOIN music WHERE score.id = music.id";
$stmt = $pdo -> query($sql);
foreach($stmt as $row){
?>
                // カレンダー表示設定
                {
                    id: '<?=$row['num']?>',
                    title: '<?=$row['name']." ".(round(($row['score'] / $row['max']) * 100))."%"?>',
                    start: '<?=date('Y-m-d',strtotime($row['datetime']))?>',
                    url: './index.php#<?=$row['num']?>'
                },
<?php
}
?>

        ],
        // 日本語化
        locale: 'ja',
        buttonText: {
            prev:     '<',
            next:     '>',
            prevYear: '<<',
            nextYear: '>>',
            today:    '今日',
            month:    '月',
            week:     '週',
            day:      '日',
            list:     '一覧'
        },
    });
    calendar.render();
});
</script>