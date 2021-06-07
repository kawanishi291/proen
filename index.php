<?php
    $title = "認知症予防システム";
    require('./header.php');
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
            <th>%</th>
        </thead>
<?php
    ini_set('display_errors', "On");
    //phpinfo();
    include "./pdo_connect.php";
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
            <td><?=($row['score'] / $row['max']) * 100?></td>
        </tbody>
<?php
    }
?>
    </table>
<?php
    require('./footer.php');
?>