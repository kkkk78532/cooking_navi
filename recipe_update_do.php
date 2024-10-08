<?php
require('dbconnect.php');
session_start();

if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $recipeId = $_POST['recipeId'];
        $recipeTitle = $_POST['recipeTitle'];
        $recipeDifficulty = $_POST['recipeDifficulty'];
        $recipeTime = $_POST['recipeTime'];
        $recipeServingSize = $_POST['recipeServingSize'];
        $recipeIntroduction = $_POST['recipeintroduction']; // 統一

        // 画像の処理
        $recipePicture = null;

        // 既存のレシピから画像パスを取得
        $sqlExistingPicture = "SELECT recipe_picture FROM recipes WHERE id = :recipe_id";
        $stmtExistingPicture = $db->prepare($sqlExistingPicture);
        $stmtExistingPicture->bindParam(':recipe_id', $recipeId);
        $stmtExistingPicture->execute();
        $existingPicture = $stmtExistingPicture->fetchColumn();

        if (isset($_FILES['recipepicture']) && $_FILES['recipepicture']['error'] == 0) {
            // 新しい画像のアップロード処理
            $targetDir = "recipe_images/";
            $targetFile = $targetDir . basename($_FILES['recipepicture']['name']);
            if (move_uploaded_file($_FILES['recipepicture']['tmp_name'], $targetFile)) {
                $recipePicture = $targetFile; // 新しい画像のパスを設定
            } else {
                echo "画像のアップロードに失敗しました。";
                exit();
            }
        } else {
            // 新しい画像がアップロードされなかった場合は、既存の画像を使用
            $recipePicture = $existingPicture;
        }

        // データベースの更新
        $sql = "UPDATE recipes SET recipe_title = :title, recipe_difficulty = :difficulty, recipe_time = :time, recipe_ServingSize = :serving_size, recipe_introduction = :introduction, recipe_picture = :picture WHERE id = :recipe_id AND username = :username";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':title', $recipeTitle);
        $stmt->bindParam(':difficulty', $recipeDifficulty);
        $stmt->bindParam(':time', $recipeTime);
        $stmt->bindParam(':serving_size', $recipeServingSize);
        $stmt->bindParam(':introduction', $recipeIntroduction); // 統一
        $stmt->bindParam(':recipe_id', $recipeId);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':picture', $recipePicture);

        if ($stmt->execute()) {
            // 材料の更新処理
            // 既存の材料を削除
            $sqlDeleteIngredients = "DELETE FROM ingredients WHERE recipe_id = :recipe_id";
            $stmtDeleteIngredients = $db->prepare($sqlDeleteIngredients);
            $stmtDeleteIngredients->bindParam(':recipe_id', $recipeId);
            $stmtDeleteIngredients->execute();

            // 新しい材料を追加
            if (!empty($_POST['ingredient_names']) && !empty($_POST['ingredient_quantities']) && !empty($_POST['ingredient_units'])) {
                for ($i = 0; $i < count($_POST['ingredient_names']); $i++) {
                    $ingredientName = $_POST['ingredient_names'][$i];
                    $ingredientQuantity = $_POST['ingredient_quantities'][$i];
                    $ingredientUnit = $_POST['ingredient_units'][$i];

                    if (!empty($ingredientName)) { // 材料名が空でない場合のみ追加
                        $sqlInsertIngredient = "INSERT INTO ingredients (recipe_id, ingredient_name, quantity, unit) VALUES (:recipe_id, :name, :quantity, :unit)";
                        $stmtInsertIngredient = $db->prepare($sqlInsertIngredient);
                        $stmtInsertIngredient->bindParam(':recipe_id', $recipeId);
                        $stmtInsertIngredient->bindParam(':name', $ingredientName);
                        $stmtInsertIngredient->bindParam(':quantity', $ingredientQuantity);
                        $stmtInsertIngredient->bindParam(':unit', $ingredientUnit);
                        $stmtInsertIngredient->execute();
                    }
                }
            }

            // 手順の更新処理
            // 既存の手順を削除
            $sqlDeleteInstructions = "DELETE FROM recipe_procedure WHERE recipe_id = :recipe_id";
            $stmtDeleteInstructions = $db->prepare($sqlDeleteInstructions);
            $stmtDeleteInstructions->bindParam(':recipe_id', $recipeId);
            $stmtDeleteInstructions->execute();

            // 新しい手順を追加
            if (!empty($_POST['recipe_description'])) {
                for ($j = 0; $j < count($_POST['recipe_description']); $j++) {
                    $recipeDescription = $_POST['recipe_description'][$j];
                    $stepNumber = $j + 1; // 手順番号は1から始まる

                    if (!empty($recipeDescription)) { // 説明が空でない場合のみ追加
                        $sqlInsertInstruction = "INSERT INTO recipe_procedure (recipe_id, step_numbers, recipe_description) VALUES (:recipe_id, :step_number, :description)";
                        $stmtInsertInstruction = $db->prepare($sqlInsertInstruction);
                        $stmtInsertInstruction->bindParam(':recipe_id', $recipeId);
                        $stmtInsertInstruction->bindParam(':step_number', $stepNumber);
                        $stmtInsertInstruction->bindParam(':description', $recipeDescription);
                        $stmtInsertInstruction->execute();
                    }
                }
            }

            // 成功メッセージとリダイレクト
            header("Location: myrecipes.php");
            exit(); // スクリプトの終了
        } else {
            echo "レシピの更新に失敗しました。";
        }
    } else {
        echo "不正なリクエストです。";
    }
} else {
    echo "ログインしてください。";
}
?>
