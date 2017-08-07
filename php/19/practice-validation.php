<?php

$USER_ID = '';
$AGE = 0;
$EMAIL = '';
$TEL = 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (isset($_POST['userid'])) {
    $USER_ID = $_POST['userid'];
    $USER_ID = htmlspecialchars($USER_ID, ENT_QUOTES);
  }
  if (isset($_POST['age'])) {
    $AGE = $_POST['age'];
    $AGE = htmlspecialchars($AGE, ENT_QUOTES);
  }
  if (isset($_POST['email'])) {
    $EMAIL = $_POST['email'];
    $EMAIL = htmlspecialchars($EMAIL, ENT_QUOTES);
  }
  if (isset($_POST['tel'])) {
    $TEL = $_POST['tel'];
    $TEL = htmlspecialchars($TEL, ENT_QUOTES);
  }
  $USER_ID_REGEX = '/[0-9a-z]{6,8}/i';
  $AGE_REGEX = '/\d{1,3}/';
  $EMAIL_REGEX = '/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/iD';
  $TEL_REGEX = '/\d{2,4}-\d{2,4}-\d{2,4}/';
}


?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="utf-8">
  <title>practice-validation.php</title>
</head>
<body>
  <?php if (preg_match($USER_ID_REGEX, $USER_ID)): ?>
    <p><?php $USER_ID ?> :ユーザIDは正しい形式で入力されています。</p>
  <?php else: ?>
    <p><?php $USER_ID ?> :ユーザIDは正しくない形式で入力されています。</p>
  <?php endif; ?>
  <?php if (preg_match($AGE_REGEX, $AGE)): ?>
    <p><?php $AGE_ID ?> :正しい年齢の形式です。</p>
  <?php else: ?>
    <p><?php $AGE_ID ?> :正しくない年齢の形式です。</p>
  <?php endif; ?>
  <?php if (preg_match($EMAIL_REGEX, $EMAIL)): ?>
    <p><?php $EMAIL ?> :正しいメールアドレスの形式です。</p>
  <?php else: ?>
    <p><?php $EMAIL ?> :正しくないメールアドレスの形式です。</p>
  <?php endif; ?>
  <?php if (preg_match($TEL_REGEX, $TEL)): ?>
    <p><?php $TEL ?> :正しい電話番号の形式です。</p>
  <?php else: ?>
    <p><?php $TEL ?> :正しくない電話番号の形式です。</p>
  <?php endif; ?>
</body>
</html>
