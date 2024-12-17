<?php
require('dbconnect.php');
session_start(); // セッションの開始

if(!isset($_SESSION['user_id'])) {
    echo "ログインしていません。<br>";
    echo '<a href="signup.php">新規登録が完了していない方はこちら！</a><br>';
    echo '<a href="login.php">ログインはこちら！</a>';
    exit();
}


$user_id = $_SESSION['user_id'];

// フォームから他のデータを取得
$recipeTitle = $_POST["recipeTitle"];
$recipeDifficulty = $_POST["recipeDifficulty"];
$recipeTime = $_POST["recipeTime"];
$recipeServingSize = $_POST["recipeServingSize"];
$recipeintroduction = $_POST["recipeintroduction"];

// 必須フィールドのチェック
if(empty($recipeTitle) || empty($recipeDifficulty) || empty($recipeTime) || empty($recipeServingSize)) {
    echo "必要な情報が不足しています。";
    exit();
}

try {
    // レシピ情報をデータベースに登録
    $sql = "INSERT INTO recipes (recipe_title, recipe_difficulty, recipe_time, recipe_ServingSize, recipe_introduction, user_id) 
            VALUES (:recipe_title, :recipe_difficulty, :recipe_time, :recipe_ServingSize, :recipe_introduction, :user_id)";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':recipe_title', $recipeTitle);
    $stmt->bindParam(':recipe_difficulty', $recipeDifficulty);
    $stmt->bindParam(':recipe_time', $recipeTime);
    $stmt->bindParam(':recipe_ServingSize', $recipeServingSize);
    $stmt->bindParam(':recipe_introduction', $recipeintroduction);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();

    // レシピIDを取得
    $recipeId = $db->lastInsertId();

    // 材料情報をデータベースに登録
    $ingredient_names = $_POST["ingredient_names"];
    $ingredient_quantities = $_POST["ingredient_quantities"];
    $ingredient_units = isset($_POST['ingredient_units']) ? $_POST['ingredient_units'] : [];

    // 材料の長さチェック
    if(count($ingredient_names) !== count($ingredient_quantities)) {
        echo "材料情報が正しくありません。";
        exit();
    }

    // 単位が空の場合は空文字列に
    foreach ($ingredient_units as $index => $unit) {
        if (empty($unit)) {
            $ingredient_units[$index] = '';
        }
    }

    // 材料をループで保存
    $length = count($ingredient_names);
    for ($i = 0; $i < $length; $i++) {
        $sql = "INSERT INTO ingredients (recipe_id, ingredient_name, quantity) VALUES (:recipe_id, :ingredient_name, :quantity)";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':recipe_id', $recipeId);
        $stmt->bindParam(':ingredient_name', $ingredient_names[$i]);
        $stmt->bindParam(':quantity', $ingredient_quantities[$i]);
        $stmt->execute();
    }

    // 手順情報をデータベースに登録
    $steps = $_POST["steps"];
    $recipe_description = $_POST["recipe_description"];
    
    // 手順の長さチェック
    if(count($steps) !== count($recipe_description)) {
        echo "手順情報が正しくありません。";
        exit();
    }

    for ($i = 0; $i < count($steps); $i++) {
        $sql = "INSERT INTO recipe_procedure (recipe_id, step_numbers, recipe_description) VALUES (:recipe_id, :step_numbers, :recipe_description)";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':recipe_id', $recipeId);
        $stmt->bindParam(':step_numbers', $steps[$i]);
        $stmt->bindParam(':recipe_description', $recipe_description[$i]);
        $stmt->execute();
    }

    // レシピ画像のアップロード処理
    if(isset($_FILES['recipepicture']) && $_FILES['recipepicture']['error'] == UPLOAD_ERR_OK) {
        $upload_directory = "recipe_images/";
        $target_file = $upload_directory . basename($_FILES['recipepicture']['name']);

        $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];
        $file_extension = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        if(!in_array($file_extension, $allowed_extensions)) {
            echo "許可されていないファイル形式です。";
            exit();
        }

        if ($_FILES['recipepicture']['size'] > 2000000) {
            echo "ファイルサイズが大きすぎます。";
            exit();
        }

        if(move_uploaded_file($_FILES['recipepicture']['tmp_name'], $target_file)) {
            $recipe_picture_path = $target_file;
            $statement = $db->prepare('UPDATE recipes SET recipe_picture = :recipe_picture WHERE id = :recipe_id');
            $statement->execute(array(':recipe_picture' => $recipe_picture_path, ':recipe_id' => $recipeId));
        } else {
            echo '画像のアップロードに失敗しました。';
        }
    }

    // 成功時のリダイレクト
    header("Location: myrecipes.php");
    exit();

} catch (PDOException $e) {
    // エラーハンドリング
    echo "データベースエラー: " . $e->getMessage();
}
?>
