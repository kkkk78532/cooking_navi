<?php
session_start();
include('../dbconnect.php'); // データベース接続

// リクエストからデータを受け取る
$data = json_decode(file_get_contents('php://input'), true);
$user_id = $data['user_id'];
$recipe_id = $data['recipe_id'];

// データベースに保存（仮に「calendar」テーブルに保存）
$query = "INSERT INTO meal_plans (user_id, recipe_id) VALUES (?, ?)";
$stmt = $pdo->prepare($query);
$stmt->execute([$user_id, $recipe_id]);

// レスポンスを返す
echo json_encode(['message' => 'カレンダーに追加されました']);
?>