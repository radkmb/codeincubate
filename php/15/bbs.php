<?php
$name = '';
$comment = '';
$date = '';

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
  if (isset($name)) {
    $name = ($_POST['name']);
    $name = htmlspecialchars($name, ENT_QUOTES, 'UTF-8');
    $name_count = mb_strlen($name);
  }
  if (isset($comment)) {
    $comment = ($_POST['comment']);
    $name = htmlspecialchars($name, ENT_QUOTES, 'UTF-8');
    $comment_count = mb_strlen($comment);
  }
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
  $log = date('Y-m-d H:i:s');
  }
}

$host = 'localhost';
$username = 'rad_kmb';
$password = '';
$dbname = 'camp';
$charset = 'utf8';

$dsn = 'mysql:host='.$host.';dbname='.$dbname.';charset='.$charset;

try {
  $pdo = new PDO($dsn, $username, $password);
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
} catch (PDOException $e) {
  echo '接続できませんでした。理由：'.$e->getMessage();
}

try {
  $sql = "INSERT INTO test_post(user_name, user_comment, create_date) VALUES (:user_name, :user_comment, :create_date)";
  $stmt = $pdo->prepare($sql);
  $stmt->bindParam(':user_name', $name);
  $stmt->bindParam(':user_comment', $comment);
  $stmt->bindParam(':create_date', $log);
  $stmt->execute();
  echo '投稿しました。';
} catch (PDOException $e) {
  echo '投稿できませんでした。理由：'.$e->getMessage();
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
  
    <?php if (!empty($errorMessageView)): ?>
    <p><?php print_r($errorMessageView); ?></p>
    <?php endif; ?>
  
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
    <?php foreach ($stmt as $read) { ?>
    <li><?php echo $read; ?></li>
    <?php } ?>
  </ul>
</body>
</html>
