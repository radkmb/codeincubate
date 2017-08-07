<?php
$filename  = 'tokyo.csv';
$fp = fopen($filename, 'r');
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
</head>
<body>
  <h1>以下にファイルから読み込んだ住所データを表示</h1>
  <table>
    <caption>住所データ</caption>
    <tbody>
      <tr>
        <td>郵便番号</td>
        <td>都道府県</td>
        <td>市区町村</td>
        <td>町域</td>
      </tr>
      <?php 
      while (($data = fgetcsv($fp, 10000, ",")) !== false) { 
      ?>
        <tr>
          <td><?php print $data[2] ?></td>
          <td><?php print $data[6] ?></td>
          <td><?php print $data[7] ?></td>
          <td><?php print $data[8] ?></td>
        <tr>
      <?php 
      } fclose($fp); 
      ?>
    </tbody>
  </table>
</body>
</html>