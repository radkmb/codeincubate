<?php
$my_name = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (isset($_POST['my_name']) === TRUE) {
    $my_name = htmlspecialchars($_POST['my_name'], ENT_QUOTES, 'UTF-8');
  }
}
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
</head>
<body>
  <?php
  if(!empty($my_name)) {
    print $my_name.'さん、ようこそ。';
  } else {
    print '名前を入力してください。';
  }
  ?>
</body>
</html>