<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>課題</title>
  </head>
  <body>
    <?php
    class Cat {
      public $name;
      public $height;
      public $weight;
      
      function show() {
        print "{$this->name}の身長は{$this->height}cm、体重は{$this->weight}kgです。<br>";
      }
    }
    
    $toraneko = new Cat();
    $toraneko->name = 'たま';
    $toraneko->height = 80;
    $toraneko->weight = 30;
    $toraneko->show();
    ?>
  </body>
</html>