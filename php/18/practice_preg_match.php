<?php
// 半角数字であるどうかのチェックする検索するパターンを記述
$subject1 = 15000;
$pattern1 = '/\d*/';  // 左に検索するパターンを記述してください
if (preg_match($pattern1, $subject1) ) {
  print $subject1.'は、半角数字です。';
} else {
  print $subject1.'は、半角数字ではありません。';
}
print "<br>";

// 半角英字であるどうかのチェックする検索するパターンを記述
$subject2 = "Atom";
$pattern2 = '/[a-z]*/i';  // 左に検索するパターンを記述してください
if (preg_match($pattern2, $subject2) ) {
  print $subject2.'は、半角英字です。';
} else {
  print $subject2.'は、半角英字ではありません。';
}
print "<br>";

// 郵便番号のチェック
$subject3 = "160-0023";
$pattern3 = '/^\d{3}-\d{4}$/';  // 左に検索するパターンを記述してください
if (preg_match($pattern3, $subject3) ) {
  print $subject3.'は、郵便番号の形式です。';
} else {
  print $subject3.'は、郵便番号の形式ではありません。';
}
print "<br>";
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>practice</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css" integrity="sha384-rwoIResjU2yc3z8GV/NPeZWAv56rSmLldC3R/AZzGRnGxQQKnKkoFVhFQhNUwEyJ" crossorigin="anonymous">
  <!--<script src="https://code.jquery.com/jquery-3.1.1.slim.min.js" integrity="sha384-A7FZj7v+d/sdmMqp/nOQwliLvUsJfDHW+k9Omg/a/EheAdgtzNs3hpfag6Ed950n" crossorigin="anonymous"></script>-->
  <!--<script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js" integrity="sha384-DztdAPBWPRXSA/3eYEEUWrWCy7G5KFbe8fFjk5JAIxUYHKkDx6Qin1DkWx51bBrb" crossorigin="anonymous"></script>-->
  <!--<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/js/bootstrap.min.js" integrity="sha384-vBWWzlZJ8ea9aCX4pEW3rVHjgjt7zpkNpZk+02D9phzyeVkE+jo0ieGizqPLForn" crossorigin="anonymous"></script></head>-->
<body>

</body>
</html>
