<?php
include('navbar.php'); // ナビゲーションバーを読み込む
?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>ログイン - クッキングナビ</title>
  <link rel="stylesheet" href="style.css"/>
</head>
<body>
  <h1>ログイン</h1>
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

</body>
</html>
