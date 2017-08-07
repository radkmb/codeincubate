<?php

$HOST = 'localhost';
$DB_USER = 'dbuser';
$DB_PASS = 'dbuser';
$DB_NAME = 'camp';

$dsn = 'mysql:host=' . $HOST . ';dbname=' . $DB_NAME;

$path_to_img = './images/';
$data = [];
$err_msg = [];
$new_img_filename = '';
$drink_name = '';
$drink_price = 0;
$drink_stock = 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (isset($_POST['drinkInfo'])) {
    $drink_name = trim(htmlspecialchars($_REQUEST['drink_name'], ENT_QUOTES));
    $drink_price = trim(htmlspecialchars($_REQUEST['price'], ENT_QUOTES));
    $drink_stock = trim(htmlspecialchars($_REQUEST['stockInsert'], ENT_QUOTES));
    if (!$drink_name || !$drink_price) {
      if (!$drink_name && !$drink_price) {
        $err_msg[] = 'ドリンク名と1円以上の値段を入力してください！';
      } else if (!$drink_name) {
        $err_msg[] = 'ドリンク名を入力してください！';
      } else if (!$drink_price) {
        $err_msg[] = '1円以上の値段を入力してください！';
      }
    }
    if (is_uploaded_file($_FILES['new_img']['tmp_name']) === TRUE) {
      $extension = pathinfo($_FILES['new_img']['name'], PATHINFO_EXTENSION);
      if ($extension === 'jpg' || $extension === 'jpeg' || $extension === 'png' || $extension === 'jpg') {
        $new_img_filename = sha1(uniqid(mt_rand(), true)) . '.' . $extension;
        if (is_file($path_to_img . $new_img_filename) !== TRUE) {
          if (move_uploaded_file($_FILES['new_img']['tmp_name'], $path_to_img . $new_img_filename) !== TRUE) {
            $err_msg[] = 'ファイルアップロードに失敗しました';
          }
        } else {
          $err_msg[] = 'ファイルアップロードに失敗しました。再度お試しください。';
        }
      } else {
        $err_msg[] = 'ファイル形式が異なります。画像ファイルはJPEGまたはPNGのみ利用可能です。';
      }
    } else {
      $err_msg[] = 'ファイルを選択してください';
    }
  } elseif (isset($_POST['drinkStock'])) {
    $drink_stock = trim(htmlspecialchars($_REQUEST['stockUpdate'], ENT_QUOTES));
  }
}

try {
  $pdo = new PDO($dsn, $DB_USER, $DB_PASS);
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
  if (count($err_msg) === 0 && $_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['drinkInfo'])) {
      // Begin transaction
      $pdo->beginTransaction();
      try {
        // Insert into table test_drink_master
        $sql = 'INSERT INTO test_drink_master (img,drink_name,price) VALUE (?,?,?)';
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(1, $new_img_filename, PDO::PARAM_STR);
        $stmt->bindValue(2, $drink_name, PDO::PARAM_STR);
        $stmt->bindValue(3, $drink_price, PDO::PARAM_INT);
        $stmt->execute();
        // Insert into table test_drink_stock
        $drink_id = $pdo->lastInsertId('id');
        $sql = 'INSERT INTO test_drink_stock (drink_id,stock) VALUE (?,?)';
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(1, $drink_id, PDO::PARAM_INT);
        $stmt->bindValue(2, $drink_stock, PDO::PARAM_INT);
        $stmt->execute();
        // Commit
        $pdo->commit();
      } catch (PDOException $e) {
        // Rollback
        $pdo->rollback();
        throw $e;
      }
    } elseif (isset($_POST['drinkStock'])) {
      // Update table test_drink_stock
      $drink_id = $_POST['drinkStock'];
      $sql = 'UPDATE test_drink_stock SET stock = ? WHERE drink_id = ?';
      $stmt = $pdo->prepare($sql);
      $stmt->bindValue(1, $drink_stock, PDO::PARAM_INT);
      $stmt->bindValue(2, $drink_id, PDO::PARAM_INT);
      $stmt->execute();
    }
  }
  try {
    // Select from inner-joined table
    $sql = 'SELECT test_drink_master.drink_id,img,drink_name,price,stock FROM test_drink_master INNER JOIN test_drink_stock ON test_drink_master.drink_id = test_drink_stock.drink_id;';
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $rows = $stmt->fetchAll();
    foreach ($rows as $row) {
      $data[] = $row;
    }
  } catch (PDOException $e) {
    throw $e;
  }
} catch (PDOException $e) {
  $err_msg['db_connect'] = 'DBエラー：' . $e->getMessage();
}

?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="utf-8">
  <title>商品情報管理ページ</title>
  <style>
    table {
      width: 880px;
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
  </style>
</head>
<body>
<?php foreach ($err_msg as $value): ?>
  <p><?php print $value; ?></p>
<?php endforeach; ?>
  <h1>商品情報管理ページ</h1>
  <form method="post" enctype="multipart/form-data">
    <div><label for="drink_name">ドリンクの名前：</label><input type="text" name="drink_name" id="drink_name" placeholder="drink name"></div>
    <div><label for="price">ドリンクの値段：</label><input type="text" name="price" id="price" placeholder="100">円</div>
    <div><label for="stock">ドリンクの個数：</label><input type="text" name="stockInsert" id="stock" placeholder="100">個</div>
    <div><input type="file" name="new_img"></div>
    <input type="hidden" name="drinkInfo">
    <div><input type="submit" value="アップロード"></div>
  </form>
  <hr>
  <table>
    <tr>
      <th>画像</th>
      <th>商品名</th>
      <th>価格</th>
      <th>在庫数</th>
    </tr>
  <?php foreach ($data as $value): ?>
    <tr>
      <td><img src="<?php print $path_to_img . $value['img']; ?>"></td>
      <td><?php print $value['drink_name']; ?></td>
      <td><?php print $value['price']; ?></td>
      <td>
        <form method="post" enctype="multipart/form-data">
          <input type="text" name="stockUpdate" placeholder="100" value="<?php print $value['stock']; ?>">個
          <input type="hidden" name="drinkStock" value="<?php print $value['drink_id']; ?>">
          <input type="submit" value="変更">
        </form>
      </td>
    <tr>
  <?php endforeach; ?>
  </table>
</body>
</html>
