<?php
require('dbconnect.php');
include('navbar.php'); // ナビゲーションバーを読み込む

// ログイン状態を確認して表示を切り替え
if (isset($_SESSION['user_id']) && isset($_SESSION['username'])) {
    $loggedInUser = $_SESSION['username'];
    $loginDisplay = '<p class="login" style="float: right; color: #00f7ff;">ようこそ、'.$loggedInUser.' さん！</p>';
    $loginDisplay2 = '<a class="signup" href="logout.php" id="logout-link" style="float: right; cursor: pointer; color: #ff5100; text-decoration: underline;">ログアウト</a>';

    // 両方の表示を結合
    $loginDisplayCombined = $loginDisplay2 . $loginDisplay;

    // ユーザーが投稿したレシピ情報を取得
    $sql = "SELECT * FROM recipes WHERE username = :username";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':username', $loggedInUser);
    $stmt->execute();
    $userRecipes = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    $loginDisplay = '<a class="login-link" href="login.php">ログイン</a>
                     <a class="signup-link" href="signup.php">新規登録</a>';
    $loginDisplayCombined = $loginDisplay;
    $userRecipes = []; // ログインしていない場合は空の配列
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>自作レシピ投稿 - クッキングナビ</title>
    <link rel="stylesheet" href="style.css"/>
    <style>
        .header-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }
        h1 {
            flex-grow: 1; /* h1をフレックスの中央に配置する */
            text-align: center; /* h1のテキストを中央揃え */
            margin-bottom: 30px;
            color: #ff5100;
        }
        .recipe-container {
            display: flex;
            align-items: flex-start;
            border: 2px solid #ccc;
            border-radius: 5px;
            padding: 10px;
            margin-bottom: 10px;
            transition: background-color 0.3s;
            justify-content: space-between;
        }
        .recipe-image {
            max-width: 30%;
            height: auto;
            margin-left: 20px;
        }
        .recipe-description {
            flex: 1;
        }
        .recipe-container:hover {
            background-color: #f9f9f9;
        }
        .button-container {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            margin-left: 10px;
        }
        .edit-button, .delete-button {
            padding: 5px 10px;
            width: 80px;
            height: 30px;
            background-color: #ff5100;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 5px;
        }
        .new-post-button {
            position: fixed;
            bottom: 20px;
            right: 20px;
            padding: 10px 20px;
            background-color: #00bfff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-align: center;
            width: 200px;
        }
        .new-post-button:hover {
            background-color: #007acc;
        }
    </style>
</head>
<body>
    <div class="header-container">
        <h1>自作レシピ一覧</h1>
        <button class="new-post-button" onclick="location.href='post-recipe.php'">レシピを投稿する</button>
    </div>

    <?php if (!empty($userRecipes)): ?>
        <?php foreach ($userRecipes as $recipe): ?>
            <div class="recipe-container">
                <div class="recipe-description">
                    <a href="recipe_update.php?recipe_id=<?php echo $recipe['id']; ?>" style="text-decoration: none; color: black;">
                        <h2><?php echo htmlspecialchars($recipe['recipe_title'], ENT_QUOTES, 'UTF-8'); ?></h2>
                        <p><?php echo htmlspecialchars($recipe['recipe_introduction'], ENT_QUOTES, 'UTF-8'); ?></p>
                    </a>
                </div>
                <img src="<?php echo htmlspecialchars($recipe['recipe_picture'], ENT_QUOTES, 'UTF-8'); ?>" alt="料理写真" class="recipe-image">
                <div class="button-container">
                    <button class="edit-button" onclick="location.href='recipe_update.php?recipe_id=<?php echo $recipe['id']; ?>'">編集</button>
                    <button class="delete-button" onclick="deleteRecipe(<?php echo $recipe['id']; ?>)">削除</button>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>まだレシピを投稿していません。</p>
    <?php endif; ?>

    <script>
        function deleteRecipe(recipeId) {
            if (confirm("本当にこのレシピを削除しますか？")) {
                window.location.href = "recipe_delete.php?recipe_id=" + recipeId;
            }
        }
    </script>

</body>
</html>

