<?php

$result = '';
  
  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['choice'])) {
      $janken = array(
        'グー',
        'チョキ',
        'パー'
        );
        
        $myChoice = htmlspecialchars($_POST['choice'], ENT_QUOTES, 'UTF-8');
        $pcChoice = $janken[mt_rand(0, 2)];
        
        if ($myChoice === 'グー' && $pcChoice === 'チョキ') {
          $result = 'Win!!';
        } elseif ($myChoice === 'グー' && $pcChoice === 'グー') {
          $result = 'draw';
        } elseif ($myChoice === 'グー' && $pcChoice === 'パー') {
          $result = 'lose...';
        } elseif ($myChoice === 'チョキ' && $pcChoice === 'グー') {
          $result = 'lose...';
        } elseif ($myChoice === 'チョキ' && $pcChoice === 'チョキ') {
          $result = 'draw';
        } elseif ($myChoice === 'チョキ' && $pcChoice === 'パー') {
          $result = 'Win!!';
        } elseif ($myChoice === 'パー' && $pcChoice === 'グー') {
          $result = 'Win!!';
        } elseif ($myChoice === 'パー' && $pcChoice === 'チョキ') {
          $result = 'lose...';
        } elseif ($myChoice === 'パー' && $pcChoice === 'パー') {
          $result = 'draw';
        }
    }
  }
?>

<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="UTF-8">
  </head>
  <body>
    <h1>じゃんけん勝負</h1>
    <?php 
    if (!empty($result)) : 
    ?>
    <p>
      自分：<?php print $myChoice; ?><br>
      相手：<?php print $pcChoice; ?>
    </p>
    <p>
    結果：
    <?php
    print $result; 
    ?>
    </p>
    <hr>
    <?php 
    endif; 
    ?>
    
    <form method="post" action="#">
      <input type="radio" name="choice" value="グー"><label for="choice">グー</label>
      <input type="radio" name="choice" value="チョキ"><label for="choice">チョキ</label>
      <input type="radio" name="choice" value="パー"><label for="choice">パー</label>
      <br>
      <button type="submit">勝負！</button>
    </form>
  </body>
</html>