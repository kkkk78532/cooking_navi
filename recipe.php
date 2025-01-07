<?php
session_start();
require('dbconnect.php');
include('navbar.php'); // ナビゲーションバーを読み込む

if(isset($_GET['recipe'])) {
    $recipeName = $_GET['recipe'];

    // レシピ名に基づいて該当するレシピ情報を取得
    $sql = "SELECT * FROM recipes WHERE recipe_title = :recipe_title";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':recipe_title', $recipeName);
    $stmt->execute();
    $recipe = $stmt->fetch();

    // 取得したレシピ情報を表示
    echo "<h1>{$recipe['recipe_title']}</h1>";
    // 他のレシピ情報も表示する
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>おすすめレシピ - クッキングナビ</title>
    <link rel="stylesheet" href="style.css"/>
</head>
<style>
    h1 {
            text-align: center;
            margin-bottom: 30px;
            color: #ff5100;
        }
</style>
<body>
    <h1>おすすめレシピ</h1>
    <!-- <a href="recipe_display.php?recipe=唐揚げ">唐揚げのレシピ詳細を見る</a>
    <a href="recipe_display.php?recipe=チャーハン">チャーハンのレシピ詳細を見る</a>
    <a href="recipe_display.php?recipe=麻婆豆腐">麻婆豆腐のレシピ詳細を見る</a>
    <a href="recipe_display.php?recipe=ラーメン">ラーメンのレシピ詳細を見る</a> -->

    <!-- レビューや評価機能 -->
    <h2>レビューと評価</h2>
    <!-- レビューや評価のフォームや表示を追加 -->

</body>
</html>
