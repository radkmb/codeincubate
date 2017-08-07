<?php
$rand1 = mt_rand(0, 2);
$rand2 = mt_rand(0, 2);
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
    </head>
    <body>
       <?php
        print 'rand1: '.$rand1."<br>";
        print 'rand2: '.$rand2."<br>";
        
        if ($rand1 > $rand2) {
            print 'rand1の方が大きい値です。';
        } elseif ($rand1 < $rand2) {
            print 'rand2の方が大きい値です。';
        } else {
            print '2つは同じ値です。';
        }
       ?>
    </body>
</html>