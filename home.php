<?php
session_start();
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
            
            // 画像が登録されている場合のみ画像を表示
            if (!empty($recipe['recipe_picture'])) {
                echo '<img src="' . htmlspecialchars($recipe['recipe_picture']) . '" class="recipe-image">';
            }
            
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
