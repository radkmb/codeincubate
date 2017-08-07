<?php
$my_name = '';
$gender = '';
$mail = '';

//送信ボタンがクリックされたときの処理
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  
  if (isset($_POST['my_name']) === TRUE) {
    $my_name = htmlspecialchars($_POST['my_name'], ENT_QUOTES, 'UTF-8');
  }

  if (isset($_POST['gender']) === TRUE) {
    $gender = htmlspecialchars($_POST['gender'], ENT_QUOTES, 'UTF-8');
  }

  if (isset($_POST['mail']) === TRUE) {
    $mail = htmlspecialchars($_POST['mail'], ENT_QUOTES, 'UTF-8');
  }
}
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>課題</title>
</head>
<body>
  <h2>課題</h2>
  <hr>
  <p>ここに入力したお名前を表示：<?php print $my_name; ?></p>
  <p>ここに選択した性別を表示：<?php print $gender; ?></p>
  <p>ここにメールを受け取るかを表示：<?php print $mail; ?></p>
  <hr>
  <form method="post">
      <p>お名前: <input id="my_name" type="text" name="my_name" value=""></p>
      <p>性別: <input type="radio" name="gender" value="男">男
      <input type="radio" name="gender" value="女">女</p>
      <p><input type="checkbox" name="mail" value="OK">お知らせメールを受け取る</p>
      <input type="submit" name="submit" value="送信">
  </form>
</body>
</html>
