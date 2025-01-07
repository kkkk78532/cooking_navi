<?php
session_start();
include('navbar.php'); // ナビゲーションバーを読み込む
?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>ログイン - クッキングナビ</title>
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
  <h1>ログイン</h1>
  
  <!-- デモボタンを追加 -->
  <button type="button" class="demo-button" onclick="fillDemoData()">デモ</button>
  
  <form action="login_do.php" method="POST">
    <dl>
        <dt>メールアドレス</dt>
        <dd>
          <input type="text" name="email" size="35" maxlength="255" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email'], ENT_QUOTES) : ''; ?>">
          <?php if(isset($error['login']) && $error['login'] == 'blank'): ?>
            <p class="error">* メールアドレスとパスワードをご記入ください</p>
          <?php endif; ?>
          <?php if(isset($error['login']) && $error['login'] == 'failed'): ?>
            <p class="error">* ログインに失敗しました。正しくご記入ください。</p>
          <?php endif; ?>
        </dd>
        <dt>パスワード</dt>
        <dd>
          <input type="password" name="password" size="35" maxlength="255" value="<?php echo isset($_POST['password']) ? htmlspecialchars($_POST['password'], ENT_QUOTES) : ''; ?>">
        </dd>
        <dt>ログイン情報の記録</dt>
        <dd>
          <input id="save" type="checkbox" name="save" value="on">
          <label for="save">次回からは自動的にログインする</label>
        </dd>
      </dl>
    <div><input type="submit" value="ログインする"></div>
  </form>

  <script>
  function fillDemoData() {
    // デモ用のメールアドレスとパスワードを入力欄にセット
    document.getElementsByName('email')[0].value = 'yse@yse-c.net';
    document.getElementsByName('password')[0].value = 'test1';
  }
  </script>

</body>
</html>
