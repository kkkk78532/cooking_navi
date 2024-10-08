<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>signup</title>
</head>
<body>
    <main>
        <pre>
        <?php
            require('dbconnect.php');

            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $username = $_POST['username'];
                $email = $_POST['email'];
                $password = $_POST['password'];
            
                // パスワードをハッシュ化
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            
                // ユーザーをデータベースに登録
                $statement = $db->prepare('INSERT INTO users (username, email, password) VALUES (:username, :email, :password)');
                $statement->execute(array(':username' => $username, ':email' => $email, ':password' => $hashedPassword));
            
                echo '新規登録が完了しました！';
            }
        ?>
        </pre>

        <div id="countdown">5</div>
        <p id="countdown-message">このページは残り<span id="countdown-time"></span>秒で移動します</p>

        <script>
            var timeLeft = 5;
            var countdown = document.getElementById("countdown");
            var countdownMessage = document.getElementById("countdown-time");
            var timer = setInterval(function() {
                timeLeft--;
                countdown.textContent = timeLeft;
                countdownMessage.textContent = timeLeft;
                if (timeLeft <= 0) {
                    clearInterval(timer);
                    window.location.href = "login.php";
                }
            }, 1000); // 1秒毎の更新
        </script>
    </main>
</body>
</html>