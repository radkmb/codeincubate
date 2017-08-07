<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>課題</title>
</head>
<body>
  <?php
  $i = 1;
  $num = 0;

  while ($i * 3 <= 100) { // 1からループ処理する
    $num = $num + $i * 3;
    $i++;
    }

  print '合計値：'.$num;
  ?>
</body>
</html>