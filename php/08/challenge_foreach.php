<?php
$class = ['ガリ勉' => '鈴木', '委員長' => '佐藤', 'セレブ' => '斎藤', 'メガネ' => '伊藤', '女神' => '杉内'];
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
    </head>
    <body>
        <?php
        foreach ($class as $nickname => $name) {
            print $name.'さんのアダ名は'.$nickname.'です。<br>';
        }
        ?>
    </body>
</html>