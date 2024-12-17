<?php
session_start();

if (isset($_SESSION['user_id']) && isset($_SESSION['username'])) {
    $loggedInUser = $_SESSION['username'];
    $loginDisplay = '<p class="login" style="float: right; color: #00f7ff;">ようこそ、'.$loggedInUser.' さん！</p>';
    $loginDisplay2 = '<a class="signup" href="logout.php" id="logout-link" style="float: right; cursor: pointer; color: #ff5100; text-decoration: underline;">ログアウト</a>';

    // 両方の表示を結合
    $loginDisplayCombined = $loginDisplay2 . $loginDisplay;
} else {
    $loginDisplayCombined = '<a class="login-link" href="login.php">ログイン</a>
                             <a class="signup-link" href="signup.php">新規登録</a>';
}
?>

<div class="navbar">
    <a class="menu" href="home.php">ホーム</a>
    <!-- <a class="menu" href="recipe.php">おすすめレシピ</a> -->
    <a class="menu" href="post-recipe.php">自作レシピ投稿</a>
    <a class="menu" href="myrecipes.php">投稿したレシピ</a>
    <a class="menu" href="ai_menu_generator.php">AIレシピ作成</a>
    <a class="menu" href="calendar.php">献立カレンダー</a>
    <?php echo $loginDisplayCombined; ?>
</div>
