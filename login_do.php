<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>login</title>
</head>
<body>
    <header>
        <h1>ログイン確認画面</h1>
    </header>

    <main>
        <?php
            // セッションの開始
            session_start();

            // データベース接続
            require('dbconnect.php');

            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $email = $_POST['email'];
                $password = $_POST['password'];

                // パスワードをハッシュ化
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

                // ログイン情報の照合
                $statement = $db->prepare('SELECT * FROM users WHERE email = :email');
                $statement->execute(array(':email' => $email));
                $user = $statement->fetch();

                if ($user && password_verify($password, $user['password'])) {
                    // ログイン成功時の処理
                    // セッション変数にユーザー情報を保存
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['username'] = $user['username'];

                    // ログイン成功時にトップ画面にリダイレクト
                    header('Location: home.php');
                } else {
                    // ログイン失敗時の処理
                    echo 'ログインに失敗しました。メールアドレスとパスワードが正しくありません。';
                    echo '<br><a href="login.php"><button type="button">ログインページに戻る</button></a>';
                }
            }
        ?>
    </main>
</body>
</html>