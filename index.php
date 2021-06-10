<?php
    // <title></title>に表示する変数
    $title = "認知症予防システム";
    // ヘッダー部分をimport
    require('./header.php');
    // どのページか判定する変数
    $page = "home";
?>
    <h1 class="mx-auto" style="width: 200px; font-family: ヒラギノ丸ゴ ProN W4;">スコア表</h1>
    <table class="table">
        <thead class="table-light" style="background-color: #008b8b;">
            <th>日付</th>
            <th>曲名</th>
            <th>スコア</th>
            <th>GREAT</th>
            <th>GOOD</th>
            <th>BAD</th>
            <th>COMBO</th>
            <th>%</th>
        </thead>
<?php
    ini_set('display_errors', "On");
    // DB接続処理include
    include "./pdo_connect.php";
    // SQL文
    $sql = "SELECT * FROM score JOIN music WHERE score.id = music.id ORDER BY score.datetime DESC";
    $stmt = $pdo -> query($sql);
    foreach($stmt as $row){
?>
        <tbody id = "<?=$row['num']?>">
            <td><?=date('Y年m月d日 H:i',strtotime($row['datetime']))?></td>
            <td><?=$row['name']?></td>
            <td><?=$row['score']?></td>
            <td><?=$row['great']?></td>
            <td><?=$row['good']?></td>
            <td><?=$row['bad']?></td>
            <td><?=$row['combo']?></td>
            <td><?=round(($row['score'] / $row['max']) * 100)?></td>
        </tbody>
<?php
    }
?>
    </table>
<?php
    // フッター部分をimport
    require('./footer.php');
?>