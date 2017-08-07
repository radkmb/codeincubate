<?php
$host = 'localhost';
$username = 'rad_kmb';
$password = '';
$dbname = 'camp';
$charset = 'utf8';

$dsn = 'mysql:host='.$host.';dbname='.$dbname.';charset='.$charset;

$path_to_img = './image/';
$data = [];
$err_msg = [];
$new_img_filename = '';
$drink_name = '';
$drink_price = 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $drink_name = (htmlspecialchars($_REQUEST['drink_name'], ENT_QUOTES));
  $drink_price = (htmlspecialchars($_REQUEST['price'], ENT_QUOTES));
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
}

try {
  $pdo = new PDO($dsn, $username, $password);
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
  
  if (count($err_msg) === 0 && $_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
      $sql = 'INSERT INTO test_drink_master (img,drink_name,price) VALUE(?,?,?)';
      $stmt = $pdo->prepare($sql);
      $stmt->bindValue(1, $new_img_filename, PDO::PARAM_STR);
      $stmt->bindValue(2, $drink_name, PDO::PARAM_STR);
      $stmt->bindValue(3, $drink_price, PDO::PARAM_INT);
      $stmt->execute();
    } catch (PDOException $e) {
      throw $e;
    }
  }
  try {
    $sql = 'SELECT img,drink_name,price FROM test_drink_master';
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
  <title>画像アップロード</title>
  <style>
    table {
      width: 660px;
      border-collapse: collapse;
    }
    table, tr, th, td {
      border: solid 1px;
      padding: 10px;
      text-align: center;
    }
    table img {
      max-width: 400px;
      height: auto;
    }
  </style>
</head>
<body>
<?php foreach ($err_msg as $value) { ?>
  <p><?php print $value; ?></p>
<?php } ?>
  <h1>商品情報管理ページ</h1>
  <form method="post" enctype="multipart/form-data">
    <div><label for="drink_name">ドリンクの名前：</label><input type="text" name="drink_name" id="drink_name" placeholder="drink name"></div>
    <div><label for="price">ドリンクの値段：</label><input type="text" name="price" id="price" placeholder="100">円</div>
    <div><input type="file" name="new_img"></div>
    <div><input type="submit" value="アップロード"></div>
  </form>
  <table>
    <tr>
      <th>画像</th>
      <th>商品名</th>
      <th>価格</th>
    </tr>
  <?php foreach ($data as $value)  { ?>
    <tr>
      <td><img src="<?php print $path_to_img . $value['img']; ?>"></td>
      <td><?php print $value['drink_name']; ?></td>
      <td><?php print $value['price']; ?></td>
    <tr>
  <?php } ?>
  </table>
</body>
</html>
