<?php
require('dbconnect.php');
include('navbar.php'); // ナビゲーションバーを読み込む

// レシピ名がGETパラメータで渡された場合の処理
if(isset($_GET['recipe'])) {
    $recipeName = $_GET['recipe'];

    // レシピ情報を取得するクエリを実行
    $sql = "SELECT * FROM recipes WHERE recipe_title = :recipe_title";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':recipe_title', $recipeName);
    $stmt->execute();
    $recipe = $stmt->fetch();

    // 材料情報を取得
    $stmtIngredients = $db->prepare('SELECT ingredient_name, quantity, unit FROM ingredients WHERE recipe_id = :recipe_id');
    $stmtIngredients->bindParam(':recipe_id', $recipe['id']); // ここで適切なレシピ識別子を指定する
    $stmtIngredients->execute();
    $cook_ingredients = $stmtIngredients->fetchAll(PDO::FETCH_ASSOC);

    // 手順情報を取得
    $stmtProcedure = $db->prepare('SELECT step_numbers, recipe_description FROM recipe_procedure WHERE recipe_id = :recipe_id');
    $stmtProcedure->bindParam(':recipe_id', $recipe['id']); // ここで適切なレシピ識別子を指定する
    $stmtProcedure->execute();
    $cook_procedure = $stmtProcedure->fetchAll(PDO::FETCH_ASSOC);
    
} else {
    echo "レシピが指定されていません。";
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>クッキングナビ</title>
    <link rel="stylesheet" href="style.css"/>
    <style>
        .recipepicture {
            height: auto; /* アスペクト比を保持して高さを自動調整 */
            max-width: 50%; /* 最大幅を親要素に合わせる */
            display: block; /* ブロック要素として表示 */
            margin: 0 auto; /* 中央揃え */
        }
    </style>
</head>
<body>
    <?php
    // レシピ情報を表示
    if($recipe) {
        echo "<img src='{$recipe['recipe_picture']}' alt='料理の写真' class='recipepicture' style='margin-top: 20px;'>";
        echo "<h1 style='color: #333333;'>{$recipe['recipe_title']}</h1>";
        echo "<p style='font-size: 22px;'>調理時間: {$recipe['recipe_time']}分　難易度: {$recipe['recipe_difficulty']}　人数: {$recipe['recipe_ServingSize']}</p>";
        echo "<div style='padding: 5px; background-color: #DDDDDD; margin-top: 10px;'>";
        echo "<p style='font-size: 20px;'>{$recipe['recipe_introduction']}</p>";
        echo "</div>";
    } else {
        echo "指定されたレシピが見つかりません。";
    }
    ?>

    <!-- 材料情報を表示 -->
    <div class="recipe-ingredients">
        <h2 style='color: #333333;'>材料</h2>
        <ul>
        <?php foreach($cook_ingredients as $ingredient): ?>
            <li>
                <span style="font-size: 20px; margin-bottom: 20px;"><?php echo $ingredient['ingredient_name']; ?>:</span>
                <span style="float: right; font-size: 20px;"><?php echo $ingredient['quantity'] . ' ' . $ingredient['unit']; ?></span>
                <hr style="border: 0; border-top: 1px solid #ccc; margin: 5px 0;">
            </li>
        <?php endforeach; ?>
        </ul>
    </div>

    <!-- 手順情報を表示 -->
    <div class="recipe-instructions">
        <h2 style='color: #333333;'>手順</h2>
        <?php foreach($cook_procedure as $procedure): ?>
            <p>工程<?php echo $procedure['step_numbers']; ?>: <?php echo $procedure['recipe_description']; ?></p>
        <?php endforeach; ?>
    </div>

</body>
</html>
