<?php
// データベース接続ファイルを読み込む
require_once('../dbconnect.php');
$pdo = $db;

header('Content-Type: application/json');
// リクエストのJSONデータを取得
$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['date'], $data['recipe_id'], $data['meal_type'])) {
    echo json_encode(['success' => false, 'message' => '無効な入力です']);
    exit;
}

$date = $data['date'];
$recipe_id = (int)$data['recipe_id'];
$meal_type = $data['meal_type'];

try {
    // 同じ日のデータを削除
    $deleteSql = 'DELETE FROM meal_plans WHERE date = :date AND meal_type = :meal_type';
    $deleteStmt = $pdo->prepare($deleteSql);
    $deleteStmt->bindParam(':date', $date, PDO::PARAM_STR);
    $deleteStmt->bindParam(':meal_type', $meal_type, PDO::PARAM_STR);
    $deleteStmt->execute();

    // 新しいデータを追加
    $insertSql = 'INSERT INTO meal_plans (date, recipe_id, meal_type) VALUES (:date, :recipe_id, :meal_type)';
    $insertStmt = $pdo->prepare($insertSql);
    $insertStmt->bindParam(':date', $date, PDO::PARAM_STR);
    $insertStmt->bindParam(':recipe_id', $recipe_id, PDO::PARAM_INT);
    $insertStmt->bindParam(':meal_type', $meal_type, PDO::PARAM_STR);
    $insertStmt->execute();

    echo json_encode(['success' => true, 'message' => '保存しました']);
} catch (PDOException $e) {
    error_log('保存エラー: ' . $e->getMessage());
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}