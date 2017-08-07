<?php
$filename = 'challenge_log.txt';
$log = date('Y年m月d日 H:i:s') . "\t";

if ($_SERVER['REQUEST_METHOD'] === 'POST'){
  $comment = $_POST['comment'] . "\n";
  if (($fp = fopen($filename, 'a')) !== FALSE) {
    if (fwrite($fp, $log . $comment) === FALSE) {
      print 'ファイル書き込み失敗:  ' . $filename;
    }
    fclose($fp);
  }
}

$data = [];

if (is_readable($filename) === TRUE) {
  if (($fp = fopen($filename, 'r')) !== FALSE) {
    while (($tmp = fgets($fp)) !== FALSE) {
      $data[] = htmlspecialchars($tmp, ENT_QUOTES, 'UTF-8');
    }
    fclose($fp);
  }
} else {
  $data[] = 'ファイルがありません';
}
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
</head>
<body>
  <h1>課題</h1>
  <form method="post">
    <input type="text" name="comment">
    <input type="submit" name="submit" value="送信">
  </form>
  <p>発言一覧</p>
  <?php 
  foreach ($data as $read) { 
  ?>
  <p><?php 
  print $read; 
  ?></p>
  <?php 
  } 
  ?>
</body>
</html>
