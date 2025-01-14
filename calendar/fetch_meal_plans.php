<?php
require_once('../dbconnect.php'); // データベース接続

try {
    // meal_plansテーブルからデータを取得
    $sql = "SELECT mp.id, r.recipe_name, mp.plan_date 
            FROM meal_plans mp
            JOIN recipes r ON mp.recipe_id = r.id
            ORDER BY mp.plan_date ASC";
    $stmt = $db->prepare($sql);
    $stmt->execute();
    $mealPlans = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // JSONで返す
    echo json_encode($mealPlans);
} catch (PDOException $e) {
    echo json_encode(['error' => 'データの取得に失敗しました: ' . $e->getMessage()]);
}
?>
