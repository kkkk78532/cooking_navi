<?php
session_start();
require_once '../env.php';
require_once '../dbconnect.php'; // データベース接続ファイルを読み込む

// CORSヘッダーを設定（ワイルドカードで全てのドメインを許可）
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Credentials: true");

// POSTメソッドかどうかを確認
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['message' => 'Only POST requests are allowed']);
    exit;
}

// 受け取ったJSONデータを取得
$inputJSON = file_get_contents('php://input');
$recipeData = json_decode($inputJSON, true);

header('Content-Type: application/json');
// データのバリデーション
if (!isset($recipeData['recipe_title']) || !isset($recipeData['ingredients']) || !isset($recipeData['recipe_procedure'])) {
    http_response_code(400);
    echo json_encode(['message' => 'Invalid recipe data']);
    exit;
}

// ユーザーがログインしているか確認
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['message' => 'You are not logged in. Please log in first.']);
    echo '<br>';
    echo '<a href="signup.php">New registration</a><br>';
    echo '<a href="login.php">Login</a>';
    exit();
}

// セッションからuser_idを取得
$recipe['user_id'] = $_SESSION['user_id'];

try {
    // PDOでデータベース接続
    $pdo = $db;
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // トランザクション開始
    $pdo->beginTransaction();

    // レシピを保存
    $sql = 'INSERT INTO recipes (recipe_title, user_id, recipe_time, recipe_difficulty, recipe_ServingSize, recipe_introduction) VALUES (?, ?, ?, ?, ?, ?)';
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        $recipeData['recipe_title'],
        $recipe['user_id'],
        $recipeData['recipe_time'],
        $recipeData['recipe_difficulty'],
        $recipeData['recipe_ServingSize'],
        $recipeData['recipe_introduction'],
    ]);

    $recipeId = $pdo->lastInsertId(); // 保存したレシピID

    // 材料を保存
    $sql = 'INSERT INTO ingredients (recipe_id, ingredient_name, quantity) VALUES (?, ?, ?)';
    $stmt = $pdo->prepare($sql);
    foreach ($recipeData['ingredients'] as $ingredient) {
        $stmt->execute([
            $recipeId,
            $ingredient['ingredient_name'],
            $ingredient['quantity']
        ]);
    }

    // 作り方を保存
    $sql = 'INSERT INTO recipe_procedure (recipe_id, step_numbers, recipe_description) VALUES (?, ?, ?)';
    $stmt = $pdo->prepare($sql);
    foreach ($recipeData['recipe_procedure'] as $step) {
        $stmt->execute([
            $recipeId,
            $step['step_numbers'],
            $step['recipe_description']
        ]);
    }

    // コミット
    $pdo->commit();
    echo json_encode(['message' => 'Recipe saved successfully']);
} catch (PDOException $e) {
    $pdo->rollBack();
    echo json_encode(['message' => 'Database error occurred.']);
}
?>
