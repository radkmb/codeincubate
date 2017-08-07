<?php
$filename = './bbs.txt';
$name = '';
$comment = '';

$errorMessage = [
  '20_100over' => '名前は20文字以内で入力してください。ひとことは100文字以内で入力してください。',
  '20over' => '名前は20文字以内で入力してください。',
  '100over' => 'ひとことは100文字以内で入力してください。',
  'noNameComment' => '名前とひとことを入力してください。',
  'noName' => '名前を入力してください。',
  'noComment' => 'ひとことを入力してください。'
];

$errorMessageView = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name = ($_POST['name']);
  $comment = ($_POST['comment']);
  $name_count = mb_strlen($name);
  $comment_count = mb_strlen($comment);

if ($name_count > 20 || $comment_count > 100) {
  if ($name_count > 20 && ($comment_count > 100)) {
    $errorMessageView = $errorMessage['20_100over'];
  } elseif ($name_count > 20) {
    $errorMessageView = $errorMessage['20over'];
  } elseif ($comment_count > 100) {
    $errorMessageView = $errorMessage['100over'];
  }
} elseif ($name === '' || $comment === '') {
  if ($name === '' && $comment === '') {
    $errorMessageView = $errorMessage['noNameComment'];
  } elseif ($name === '') {
    $errorMessageView = $errorMessage['noName'];
  } elseif ($comment === '') {
    $errorMessageView = $errorMessage['noComment'];
  }
} else {
  $date = date('Y-m-d H:i:s')."\n";
  $show = $name.":"."\t".$comment."\t".$date;
  if (($fp = fopen($filename, 'a')) !== FALSE) {
    if (fwrite($fp, $show) === FALSE) {
      print '書き込みに失敗しました。'.$filename;
    }
    fclose($fp);
  }
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
<html lang="ja">
<head>
  <meta charset="utf-8">
  <title>ひとこと掲示板</title>
</head>
<body>
  <h1>ひとこと掲示板</h1>
  
  <p>
    <?php
    if (empty($errorMessageView)) {
      print "";
    } else {
      print_r($errorMessageView);
    }?>
  </p>
  
  <style type="text/css">
    p {
      color: red;
    }
  </style>
  
  <form method="post" action="#">
    名前：<input type="text" name="name">
    ひとこと：<input type="text" name="comment">
    <input type="submit" value="送信">
  </form>
  <ul>
    <?php foreach ($data as $read) { ?>
    <li><?php print $read; ?></li>
    <?php } ?>
  </ul>
</body>
</html>
