<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>課題</title>
</head>
<body>
  <?php
  $num = 0; //合計の数が入る変数

  for ($i = 1; $i <= 100; $i++) {
    if ($i % 3 == 0){
      $num = $num + $i; //加算し続ける
    }
  }
  print '合計：'.$num;
  ?>
</body>
</html>
