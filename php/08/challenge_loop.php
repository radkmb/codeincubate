<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
    </head>
    <body>
        <?php
        for ($i = 1; $i <= 100; $i++) {
            if ($i % 3 == 0 && $i % 5 == 0) {
                print 'FizzBuzz<br>';
                
            } elseif ($i % 3 == 0) {
                print 'Fizz<br>';
                
            } elseif ($i % 5 == 0) {
                print 'Buzz<br>';
                
            } else {
                print $i.'<br>';
            }
        }
        ?>
    </body>
</html>