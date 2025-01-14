<?php
session_start();
require_once('../dbconnect.php'); // データベース接続

// リクエストからデータを受け取る
$data = json_decode(file_get_contents('php://input'), true);
$userId = $data['user_id'];
$recipeId = $data['recipe_id'];
$mealType = isset($data['meal_type']) ? $data['meal_type'] : '未設定'; // デフォルト値
$planDate = isset($data['plan_date']) && $data['plan_date'] !== '' ? $data['plan_date'] : NULL; // 空の場合はNULLを設定

// データベースに挿入
$sql = "INSERT INTO meal_plans (user_id, recipe_id, meal_type, plan_date) 
        VALUES (:user_id, :recipe_id, :meal_type, :plan_date)";
$stmt = $db->prepare($sql);
$stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
$stmt->bindParam(':recipe_id', $recipeId, PDO::PARAM_INT);
$stmt->bindParam(':meal_type', $mealType, PDO::PARAM_STR);
$stmt->bindParam(':plan_date', $planDate, PDO::PARAM_STR);

// ここで実行して、結果を返す
if ($stmt->execute()) {
    echo json_encode(['message' => 'カレンダーに追加されました']);
} else {
    echo json_encode(['message' => 'エラーが発生しました']);
}
?>
