<?php
$rand = mt_rand(1, 6);
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
    </head>
    <body>
        <?php
            print $rand.'<br>';
        
            if ($rand % 2 == 0) {
                print '偶数';
            } else {
                print '奇数';
            }
        ?>
    </body>
</html>