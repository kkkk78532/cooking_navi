<?php
include('navbar.php'); // ナビゲーションバーを読み込む
?>
<!-- 新規登録ページ -->
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<title>新規登録 - クッキングナビ</title>
<link rel="stylesheet" href="style.css"/>
</head>
<body>
  <h1>新規登録</h1>
  <form action="signup_do.php" method="post" enctype="multipart/form-data">
    <label for="username">ユーザー名:</label>
    <input type="text" id="username" name="username"><br><br>
    <label for="email">メールアドレス:</label>
    <input type="email" id="email" name="email"><br><br>
    <label for="password">パスワード:</label>
    <input type="password" id="password" name="password"><br><br>
    <input type="submit" value="登録">
  </form>
</body>
</html>
