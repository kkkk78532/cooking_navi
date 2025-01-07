<?php
session_start();
include('navbar.php'); // ナビゲーションバーを読み込む
?>
<!-- 新規登録ページ -->
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<title>新規登録 - クッキングナビ</title>
<link rel="stylesheet" href="style.css"/>
<style>
  .demo-button {
    margin: 10px;
    float: right; /* ボタンを右端に配置 */
    padding: 5px 10px;
    background-color: #f0ad4e;
    color: white;
    border: none;
    cursor: pointer;
  }
  .demo-button:hover {
    background-color: #ec971f;
  }
</style>
</head>
<body>
  <h1>新規登録</h1>
  <!-- デモボタン -->
  <button type="button" class="demo-button" onclick="fillDemoData()">デモ</button>
  
  <form action="signup_do.php" method="post" enctype="multipart/form-data">
    <label for="username">ユーザー名:</label>
    <input type="text" id="username" name="username"><br><br>
    <label for="email">メールアドレス:</label>
    <input type="email" id="email" name="email"><br><br>
    <label for="password">パスワード:</label>
    <input type="password" id="password" name="password"><br><br>
    <input type="submit" value="登録">
  </form>

<script>
function fillDemoData() {
  document.getElementById('username').value = 'YSEテスト';
  document.getElementById('email').value = 'test@example.com';
  document.getElementById('password').value = 'test';
}
</script>
</body>
</html>
