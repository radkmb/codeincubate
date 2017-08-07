<?php

define('DB_NAME', 'camp');
define('DB_USER', 'rad_kmb');
define('DB_PASS', '');
define('DSN', 'mysql:host=localhost;dbname=camp');

$path_to_img = './images/';
$data = [];
$err_msg = [];
$drink_stock = 0;

try {
  $dbh = new PDO(DSN, DB_USER, DB_PASS);
  $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

  if (count($err_msg) === 0) {
    try {
      // drink_master から選択
      $sql = 'SELECT drink_master.drink_id,img,drink_name,price,stock
                from drink_master
          INNER JOIN drink_stock
                  ON drink_master.drink_id = drink_stock.drink_id
               WHERE status = 1';
      $stmt = $dbh->prepare($sql);
      $stmt->execute();
      $rows = $stmt->fetchAll();
      foreach ($rows as $row) {
        $data[] = $row;
        $drink_stock = $row['stock'];
      }
      if (preg_match('/-[0-9]{0,10}/', $drink_stock) || empty($drink_stock)) {
        $err_msg[] = '売り切れ';
      }
    } catch (PDOException $e) {
      throw $e;
    }
  }
} catch (PDOException $e) {
  $err_msg['db_connect'] = 'エラー：' . $e->getMessage();
}
?>

<!-- ここからhtml -->
<!DOCTYPE html>
<html>
<head lang="ja">
  <meta charset="utf-8">
  <title>自動販売機</title>
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
    <p><?php print $value; ?></p>
  <?php } ?>

  <h1>自動販売機</h1>
  <form action="result.php" method="post" enctype="multipart/form-data">
    <div>
      <label for="money">金額</label>
      <input type="number" name="money" id="money">円
      <input type="submit" value="購入">
    </div>

    <div class="container">
      <?php if (count($err_msg) === 0) { ?>
        <?php foreach($data as $value) { ?>
          <figure>
            <img src="<?php print $path_to_img.$value['img']; ?>" alt="<?php print $value['drink_name']; ?>">
            <figcaption>
              <h2><?php print $value['drink_name']; ?></h2>
              <p><?php print $value['price']; ?>円</p>

              <p>
                <?php if ($value['stock']) { ?>
                  <input type="radio" name="drinkBuy" value="<?php print $value['drink_id']; ?>">
                <?php } else { ?>
                  <strong>売り切れ</strong>
                <?php } ?>
              </p>

            </figcaption>
          </figure>
        <?php } ?>
      <?php } ?>
    </div>
  </form>
</body>
</html>