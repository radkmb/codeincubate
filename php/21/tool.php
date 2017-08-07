<?php

define('DB_NAME', 'camp');
define('DB_USER', 'rad_kmb');
define('DB_PASS', '');
define('DSN', 'mysql:host=localhost;dbname=camp');

$path_to_img = './images/';
$data = [];
$scs_msg = [];
$err_msg = [];
$new_img_filename = '';
$drink_name = '';
$drink_price = 0;
$drink_stock = 0;
$drink_status = 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // 変数に各値を代入
  $drinkInfo = $_POST['drinkInfo'];
  $drinkStock = $_POST['drinkStock'];
  $statusId = $_POST['statusIdUpdate'];
  $statusVal = $_POST['statusValUpdate'];

  if (isset($drinkInfo)) {
    $drink_name = $_POST['drink_name'];
    $drink_price = $_POST['price'];
    $drink_stock = $_POST['stockInsert'];
    $drink_status = $_POST['statusInsert'];

    $drink_name = input_validate($drink_name);
    $drink_price = input_validate($drink_price);
    $drink_stock = input_validate($drink_stock);
    $drink_status = input_validate($drink_status);

    // 中身が空か確認
    $drink_name = (!empty($drink_name) ? $drink_name : $err_msg[] = '商品名を入力してください！');
    if (empty($drink_price) && $drink_price == '') {
      $err_msg[] = '商品の値段を入力してください！';
    }
    if (empty($drink_stock) && $drink_stock == '') {
      $err_msg[] = '商品の個数を入力してください！';
    }

    if (preg_match('/-[1-9]{0,10}/', $drink_price)) {
      $err_msg[] = '商品の値段を0円以上で入力してください！';
    }
    if (preg_match('/-[1-9]{0,10}/', $drink_stock)) {
      $err_msg[] = '商品の個数を0個以上で入力してください！';
    }

    if (is_uploaded_file($_FILES['new_img']['tmp_name']) === TRUE) {
      $extension = pathinfo($_FILES['new_img']['name'],PATHINFO_EXTENSION);
      if ($extension === 'jpg' || $extension === 'jpeg' || $extension === 'png') {
        $new_img_filename = sha1(uniqid(mt_rand(), true)) . '.' . $extension;
        if (is_file($path_to_img . $new_img_filename) !== TRUE) {
          if (move_uploaded_file($_FILES['new_img']['tmp_name'], $path_to_img . $new_img_filename) !== TRUE) {
            $err_msg[] = 'ファイルアップロードに失敗しました';
          }
        } else {
          $err_msg[] = 'ファイルのアップロードに失敗しました。';
        }
      } else {
        $err_msg[] = 'ファイル形式が異なります。画像ファイルはJPEGまたはPNGのみ利用可能です。';
      }
    } else {
      $err_msg[] = 'ファイルを選択してください。';
    }
    if (count($err_msg) === 0) {
      $scs_msg[] = 'アップロードに成功しました。';
    }
  }

  if (isset($drinkStock)) {
    $drink_stock = $_POST['stockUpdate'];
    $drink_stock = input_validate($drink_stock);
    if (preg_match('/-[1-9]{0,10}/', $drink_stock)) {
      $err_msg[] = '商品の個数を0個以上で入力してください！';
    }
    if (count($err_msg) === 0) {
      $scs_msg[] = '在庫の変更が完了しました。';
    }
  }
}

try {
  $dbh = new PDO(DSN, DB_USER, DB_PASS);
  $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
  if (count($err_msg) === 0 && $_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($drinkInfo)) {
      // トランザクション開始
      $dbh->beginTransaction();
      try {
        // テーブル drink_master にデータを挿入
        $sql = 'INSERT INTO drink_master (img,drink_name,price,status) VALUE (?,?,?,?)';
        $stmt = $dbh->prepare($sql);
        $stmt->bindValue(1, $new_img_filename, PDO::PARAM_STR);
        $stmt->bindValue(2, $drink_name, PDO::PARAM_STR);
        $stmt->bindValue(3, $drink_price, PDO::PARAM_INT);
        $stmt->bindValue(4, $drink_status, PDO::PARAM_INT);
        $stmt->execute();
        // テーブル drink_stock にデータを挿入
        $drink_id = $dbh->lastInsertId('id');
        $sql = 'INSERT INTO drink_stock (drink_id,stock) VALUE (?,?)';
        $stmt = $dbh->prepare($sql);
        $stmt->bindValue(1, $drink_id, PDO::PARAM_INT);
        $stmt->bindValue(2, $drink_stock, PDO::PARAM_INT);
        $stmt->execute();
        // コミット
        $dbh->commit();
      } catch (PDOException $e) {
        // ロールバック
        $dbh->rollback();
        throw $e;
      }
    }

    if (isset($drinkStock)) {
      $drink_id = $drinkStock;
      // テーブル drink_stock を更新する
      try {
        $sql = 'UPDATE drink_stock SET stock = ? WHERE drink_id = ?';
        $stmt = $dbh->prepare($sql);
        $stmt->bindValue(1, $drink_stock, PDO::PARAM_INT);
        $stmt->bindValue(2, $drink_id, PDO::PARAM_INT);
        $stmt->execute();
      } catch (PDOException $e) {
        throw $e;
      }
    }

    if (isset($statusId)) {
      $drink_id = $statusId;
      $statusVal = (is_numeric($statusVal) ? (int)$statusVal : 0);
      $statusVal ? $drink_status = 0 : $drink_status = 1 ;
      try {
        $sql = 'UPDATE drink_master SET status = ? WHERE drink_id = ?';
        $stmt = $dbh->prepare($sql);
        $stmt->bindValue(1, $drink_status, PDO::PARAM_INT);
        $stmt->bindValue(2, $drink_id, PDO::PARAM_INT);
        $stmt->execute();
      } catch (PDOException $e) {
        throw $e;
      }
    }
  }

  try {
    $sql = 'SELECT drink_master.drink_id,img,drink_name,price,status,stock
              FROM drink_master
        INNER JOIN drink_stock
                ON drink_master.drink_id = drink_stock.drink_id';
    $stmt = $dbh->prepare($sql);
    $stmt->execute();
    $rows = $stmt->fetchAll();
    foreach ($rows as $row) {
      $data[] = $row;
    }
  } catch (PDOException $e) {
    throw $e;
  }
} catch (PDOException $e) {
  $err_msg['db_connect'] = 'エラー：' . $e->getMessage();
}

function input_validate($input) {
  $input = trim($input);
  $input = stripslashes($input);
  // $input = htmlspecialchars($input, ENT_QUOTES, 'UTF-8');
  return $input;
}
?>

<!-- ここからhtml -->
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="utf-8">
  <title>商品情報管理ページ</title>
  <style>
    table {
      width: 960px;
      border-collapse: collapse;
    }
    table, tr, th, td {
      border: solid 1px;
      padding: 10px;
      text-align: center;
    }
    th, td {
      min-width: 80px;
    }
    table img {
      max-width: 400px;
      height: auto;
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
  <?php foreach ($scs_msg as $value) { ?>
    <p class="success"><?php print $value; ?></p>
  <?php } ?>
  <h1>商品情報管理ページ</h1>

  <form method="post" enctype="multipart/form-data">
    <div><label for="drink_name">商品の名前：</label><input type="text" name="drink_name" id="drink_name" placeholder="drink name"></div>
    <div><label for="price">商品の値段：</label><input type="number" name="price" id="price" placeholder="100">円</div>
    <div><label for="stock">商品の個数：</label><input type="number" name="stockInsert" id="stock" placeholder="100">個</div>
    <div>
      <select name="statusInsert">
        <option value="0">非公開</option>
        <option value="1" selected>公開</option>
      </select>
    </div>
    <div><input type="file" name="new_img"></div>
    <input type="hidden" name="drinkInfo">
    <div><input type="submit" value="商品追加"></div>
  </form>
  <hr>
  <table>

    <tr>
      <th>画像</th>
      <th>商品名</th>
      <th>価格</th>
      <th>在庫数</th>
      <th>ステータス</th>
    </tr>

    <?php foreach ($data as $value) { ?>
      <tr>
        <td><img src="<?php print $path_to_img . $value['img']; ?>" alt="<?php print $value['drink_name']; ?>"></td>
        <td><?php print $value['drink_name']; ?></td>
        <td><?php print $value['price']; ?>円</td>

        <td>
          <form method="post" enctype="multipart/form-data">
            <input type="number" name="stockUpdate" placeholder="100" value="<?php print $value['stock']; ?>">個
            <input type="hidden" name="drinkStock" value="<?php print $value['drink_id']; ?>">
            <input type="submit" value="変更">
          </form>
        </td>

        <td>
          <form method="post" enctype="multipart/form-data">
            <input type="hidden" name="statusIdUpdate" value="<?php print $value['drink_id']; ?>">
            <input type="hidden" name="statusValUpdate" value="<?php print $value['status']; ?>">
            <?php if ($value['status']) { ?>
              <input type="submit" value="公開 → 非公開" class="is-show">
            <?php } else { ?>
              <input type="submit" value="非公開 → 公開" class="is-hide">
            <?php } ?>
          </form>
        </td>
      </tr>
    <?php } ?>
  </table>
</body>
</html>