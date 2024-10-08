<?php
require('dbconnect.php'); // データベース接続

// レシピIDがGETリクエストで渡されているか確認
if (isset($_GET['recipe_id'])) {
    $recipeId = $_GET['recipe_id'];

    // トランザクションを開始
    $db->beginTransaction();

    try {
        // まず関連するレコードを削除
        $sql1 = "DELETE FROM ingredients WHERE recipe_id = :recipe_id";
        $stmt1 = $db->prepare($sql1);
        $stmt1->bindParam(':recipe_id', $recipeId);
        $stmt1->execute();

        $sql2 = "DELETE FROM recipe_procedure WHERE recipe_id = :recipe_id";
        $stmt2 = $db->prepare($sql2);
        $stmt2->bindParam(':recipe_id', $recipeId);
        $stmt2->execute();

        // 最後にレシピを削除
        $sql3 = "DELETE FROM recipes WHERE id = :recipe_id";
        $stmt3 = $db->prepare($sql3);
        $stmt3->bindParam(':recipe_id', $recipeId);
        $stmt3->execute();

        // コミット
        $db->commit();
        header("Location: myrecipes.php"); // 正しいリダイレクト先を設定
        exit();
    } catch (Exception $e) {
        // エラーが発生した場合はロールバック
        $db->rollBack();
        echo "削除に失敗しました。エラー: " . $e->getMessage();
        exit(); // エラー時もexitを追加
    }
} else {
    echo "レシピIDが指定されていません。";
    exit(); // ID未指定の場合もexit
}
?>
