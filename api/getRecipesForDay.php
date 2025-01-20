<?php
// getRecipesForDay.php

function getRecipesForDay($pdo, $currentDate) {
    // SQLクエリ（指定された日付に対応するレシピを取得）
    $sql = "SELECT meal_type, r.recipe_title 
            FROM meal_plans mp
            JOIN recipes r ON mp.recipe_id = r.id
            WHERE mp.date = :currentDate";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':currentDate', $currentDate);
    $stmt->execute();

    // 結果を取得
    $recipesForDay = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $recipesForDay[$row['meal_type']] = $row['recipe_title'];
    }
    
    return $recipesForDay;
}
?>
