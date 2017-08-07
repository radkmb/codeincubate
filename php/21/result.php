<?php

define('DB_NAME', 'camp');
define('DB_USER', 'rad_kmb');
define('DB_PASS', '');
define('DSN', 'mysql:host=localhost;dbname=camp');

$path_to_img = './images/';
$data = [];
$err_msg = [];
$drink_id = 0;
$drink_name = '';
$money = 0;
$change = 0;
$drink_price = 0;
$drink_stock = 0;
$drink_status = 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // 変数を各値に代入
  $drinkBuy = $_POST['drinkBuy'];
  $money = $_POST['money'];
  $drink_id = $_POST['drinkBuy'];

  if (isset($drinkBuy)) {
    // formの変数
    $money = input_validate($money);
    $drink_id = input_validate($drink_id);

    // 空であるか確認
    if (empty($money) && $money == '') {
      $err_msg[] = '金額を入力してください！';
    }

    if (preg_match('/-[1-9]{0,10}/', $money)) {
      $err_msg[] = '金額を0円以上で入力してください！';
    }
    $money = (is_numeric($money) ? (int)$money : 0);
  } else {
    $err_msg[] = '購入する商品を選択してください！';
  }
}

try {
  $dbh = new PDO(DSN, DB_USER, DB_PASS);
  $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

  if (count($err_msg) === 0 && $_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
      $sql = 'SELECT drink_name,img,price,stock,status
                FROM drink_master
          INNER JOIN drink_stock
                  ON drink_master.drink_id = drink_stock.drink_id
               WHERE drink_master.drink_id = ?';
      $stmt = $dbh->prepare($sql);
      $stmt->bindValue(1, $drink_id, PDO::PARAM_INT);
      $stmt->execute();
      // 配列
      $rows = $stmt->fetchAll();
      foreach ($rows as $row) {
        $drink_price = $row['price'];
        $drink_stock = $row['stock'];
        $drink_status = $row['status'];
        $data[] = $row;
      }
      if ($drink_stock === 0) {
        $err_msg[] = 'ご指定の商品が在庫切れです！';
      }
      if ($drink_status === 0) {
        $err_msg[] = 'ご指定の商品が非公開になっています！';
      }

      $drink_price = (is_numeric($drink_price) ? (int)$drink_price : 0);
      $change = $money - $drink_price;

      if (preg_match('/^[0-9]{0,10}$/', $change)) {
        try {
          $drink_stock = (!$drink_stock ? $drink_stock = 0 : $drink_stock -= 1);
          // ドリンクの状態を更新
          $sql = 'UPDATE drink_stock SET stock = ? WHERE drink_id = ' . $drink_id;
          $stmt = $dbh->prepare($sql);
          $stmt->bindValue(1, $drink_stock, PDO::PARAM_INT);
          $stmt->execute();
        } catch (PDOException $e) {
          throw $e;
        }

        try {
          // テーブル drink_history に挿入
          $sql = 'INSERT INTO drink_history (drink_id) VALUE (' .$drink_id. ')';
          $stmt = $dbh->prepare($sql);
          $stmt->execute();
        } catch (PDOException $e) {
          throw $e;
        }
      }
    } catch (PDOException $e) {
      throw $e;
    }
  }
} catch (PDOException $e) {
  $err_msg['db_connect'] = 'エラー：' . $e->getMessage();
}

function input_validate($input) {
  $input = trim($input);
  $input = stripslashes($input);
  $input = htmlspecialchars($input, ENT_QUOTES, 'UTF-8');
  return $input;
}
?>

<!DOCTYPE html>
<html>
<head lang="ja">
  <meta charset="utf-8">
  <title>購入結果</title>
  <style>
    .container {
      display: flex;
      flex-wrap: wrap;
      width: 600px;
      margin-top: 3rem;
      box-sizing: border-box;
    }
    .container *,
    .container *::before,
    .container *::after {
      box-sizing: inherit;
    }
    .container figure {
      width: 150px;
      margin: 0;
      text-align: center;
    }
    .container strong {
      color: #f00;
    }
    .container img {
      max-width: 130px;
    }
    .success {
      color: green;
    }
    .error {
      color: red;
    }
  </style>
</head>

<body>
<?php foreach ($err_msg as $value) { ?>
  <p class="error"><?php print $value; ?></p>
<?php } ?>
  <h1>購入結果</h1>

  <div class="result">
    <?php
    if (count($err_msg) === 0) {
      foreach ($data as $value) {
        if (preg_match('/^[0-9]{0,10}$/', $change)) { ?>
          <p><img src="<?php print $path_to_img.$value['img']; ?>" alt="<?php print $value['drink_name']; ?>"></p>
          <p>がしゃん！【<?php print $value['drink_name']; ?>】が買えました！</p>
          <p>お釣りは【<?php print $change; ?>円】です。</p>
        <?php } else { ?>
          <p>お金がたりません！</p>
          <?php
        }
      }
    } ?>
  </div>
  <p><a href="./">戻る</a></p>
</body>
</html>