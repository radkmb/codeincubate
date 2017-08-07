<?php

// 初期化
$height = '';
$weight = '';
$bmi    = '';
$err_msg = [];  // エラーメッセージ用の配列

// リクエストメソッドを取得する
$request_method = $_SERVER['REQUEST_METHOD'];

// 「BMI計算」ボタンをクリックした（POSTされた）場合に処理する
if ($request_method === 'POST') {

  // POSTデータを取得する
  $height = get_post_data('height');
  $weight = get_post_data('weight');

  // 身長の値が数値かどうかをチェックする
  if (is_numeric($height) === FALSE) {
    $err_msg[] = '身長は数値を入力してください';
  }

  // 体重の値が数値かどうかをチェックする
  if (is_numeric($weight) === FALSE) {
    $err_msg[] = '体重は数値を入力してください';
  }

  // エラーがない場合にBMIを算出する
  if (count($err_msg) === 0) {
    // BMIを算出する
    $bmi = calc_bmi($height, $weight);
  }
}

/**
* BMIを計算する
* @param mixed $height 身長(cm)
* @param mixed $weight 体重(kg)
* @return float 計算したBMIの値を返す
*/
function calc_bmi($height, $weight) {
  //以下に処理を記述してください
  $bmi = ($weight / ($height * $height)) * (100 * 100);
  $bmi = round($bmi);
  return $bmi;
}

/**
* POSTデータを取得する
* @param str $key 配列キー
* @return str POSTの値
*/
function get_post_data($key) {
  $str = '';
  if (isset($_POST[$key]) === TRUE) {
    $str = $_POST[$key];
  }
  return $str;
}
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>BMI計算</title>
</head>
<body>
  <h1>BMI計算</h1>
  <form method="post">
    身長(cm) : <input type="text" name="height" value="<?php print $height ?>">
    体重(kg) : <input type="text" name="weight" value="<?php print $weight ?>">
    <input type="submit" value="BMIを計算する">
  </form>
<?php if (count($err_msg) > 0) { ?>
<?php   foreach ($err_msg as $value) { ?>
  <p><?php print $value; ?></p>
<?php   } ?>
<?php } ?>
<?php if ($request_method === 'POST' && count($err_msg) === 0) { ?>
  <p>あなたのBMIは<?php print $bmi; ?>です</p>
<?php } ?>
</body>
</html>
