<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>課題</title>
    </head>
    <body>
        <?php
        $host = 'localhost';
        $username = 'rad_kmb';
        $password = '';
        $dbname = 'camp';
        $charset = 'utf8';
        
        $dsn = 'mysql:dbname='.$dbname.';host='.$host.';charset='.$charset;
        
        try {
            $dbh = new PDO($dsn, $username, $password);
            $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
            
            $sql = 'SELECT * FROM products';
            $stmt = $dbh->prepare($sql);
            $stmt->execute();
            $rows = $stmt->fetchAll();
            
            var_dump($rows);
            
        } catch (PDOException $e) {
            echo '接続できませんでした。理由：'.$e->getMessage();
        }
        ?>
    </body>
</html>