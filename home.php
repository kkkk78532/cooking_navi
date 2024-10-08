<?php
include('navbar.php'); // ナビゲーションバーを読み込む
include('dbconnect.php'); // データベース接続ファイルを読み込む

// 検索キーワードが送信されたかどうかをチェック
$searchKeyword = isset($_GET['search']) ? $_GET['search'] : '';

// SQLクエリを準備。検索キーワードがある場合は絞り込み
if ($searchKeyword) {
    $stmt = $db->prepare("SELECT * FROM recipes WHERE recipe_title LIKE :searchKeyword OR recipe_introduction LIKE :searchKeyword");
    $stmt->bindValue(':searchKeyword', '%' . $searchKeyword . '%');
} else {
    // 検索キーワードがない場合は全てのレシピを取得
    $stmt = $db->prepare("SELECT * FROM recipes");
}

$stmt->execute();
$recipes = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>クッキングナビ - ホーム</title>
    <link rel="stylesheet" href="style.css"/>
    <style>
        /* スタイル調整 */
        h1 {
            text-align: center;
            margin-bottom: 30px;
            color: #ff5100;
        }

        .search-form {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .search-form input[type="text"] {
            width: calc(100% - 22px);
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .search-form input[type="submit"] {
            background-color: #ff5100;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
        }

        .recipe-container {
            display: flex;
            border: 3px solid #ccc;
            border-radius: 5px;
            padding: 10px;
            margin-bottom: 20px;
        }

        .recipe-image {
            max-width: 30%;
            height: auto;
            margin-right: 20px;
        }

        .recipe-description {
            flex: 1;
        }

        .recipe-container:hover {
            background-color: #f9f9f9;
        }
    </style>
</head>
<body>
    <h1>レシピ検索</h1>

    <!-- 検索フォーム -->
    <form class="search-form" action="" method="GET">
        <label for="search">検索キーワード:</label>
        <input type="text" id="search" name="search" placeholder="キーワードを入力" value="<?php echo htmlspecialchars($searchKeyword); ?>">
        <input type="submit" value="検索">
    </form>

    <h1>レシピ一覧</h1>

    <!-- レシピ表示エリア -->
    <?php
    if ($recipes) {
        foreach ($recipes as $recipe) {
            echo '<a href="recipe_display.php?recipe=' . urlencode($recipe['recipe_title']) . '">';
            echo '<div class="recipe-container">';
            echo '<img src="' . htmlspecialchars($recipe['recipe_picture']) . '" alt="料理写真" class="recipe-image">';
            echo '<div class="recipe-description">';
            echo '<h2>' . htmlspecialchars($recipe['recipe_title']) . '</h2>';
            echo '<p>' . htmlspecialchars($recipe['recipe_introduction']) . '</p>';
            echo '</div>';
            echo '</div>';
            echo '</a>';
        }
    } else {
        echo '該当するレシピが見つかりませんでした。';
    }
    ?>
</body>
</html>
