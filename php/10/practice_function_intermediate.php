<?php
$value = 55.5555;
$floor = floor($value);
$ceil = ceil($value);
$round = round($value);
// 小数第二位で四捨五入
$round_hundredth =  round($value, 2);
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>関数</title>
</head>
<body>
  <?php
  print '元の値：'.$value."<br>";
  print $floor."<br>";
  print $ceil."<br>";
  print $round."<br>";
  print $round_hundredth."<br>";
  ?>
</body>
</html>