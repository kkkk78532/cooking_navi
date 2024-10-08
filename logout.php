<?php
// セッションの開始
session_start();

// セッションを破棄してログアウトする
$_SESSION = array(); // セッション変数を空にする
session_destroy(); // セッションを破棄する

// トップページにリダイレクトする
header("Location: home.php"); // リダイレクト先のページを指定
exit();
?>