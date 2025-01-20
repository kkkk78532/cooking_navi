<?php
// データベース接続の設定
include '../dbconnect.php'; // 必要に応じて接続を調整してください

if (isset($_POST['date']) && isset($_POST['meal_type'])) {
    $date = $_POST['date'];
    $meal_type = $_POST['meal_type'];

    // SQLクエリ：指定された日付と食事タイプに対するレシピを削除
    $sql = "DELETE FROM meal_plans WHERE date = :date AND meal_type = :meal_type";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':date', $date);
    $stmt->bindParam(':meal_type', $meal_type);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid parameters']);
}
?>
