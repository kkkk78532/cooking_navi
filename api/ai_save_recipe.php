<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

// POSTメソッドかどうかを確認
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405); // メソッドが許可されていない
    echo json_encode(['message' => 'Only POST requests are allowed']);
    exit;
}

// 受け取ったJSONデータを取得
$inputJSON = file_get_contents('php://input');
$recipeData = json_decode($inputJSON, true);

// データのバリデーション
if (!isset($recipeData['title']) || !isset($recipeData['ingredients']) || !isset($recipeData['recipe_procedure'])) {
    http_response_code(400); // バッドリクエスト
    echo json_encode(['message' => 'Invalid recipe data']);
    exit;
}

// データベース接続設定
$host = 'localhost';
$dbname = 'cooking_navi'; // データベース名を更新
$user = 'root';
$pass = '';

$info = "mysql:host=$host;dbname=$dbname;charset=utf8";

try {
    // PDOを使用してデータベースに接続
    $pdo = new PDO($info, $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // トランザクション開始
    $pdo->beginTransaction();

    // レシピを保存
    $sql = 'INSERT INTO recipes (recipe_title, recipe_time, recipe_difficulty, recipe_ServingSize, recipe_introduction) VALUES (?, ?, ?, ?, ?)';
    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        $recipeData['recipe_title'],
        $recipeData['recipe_time'] ?? '',
        $recipeData['recipe_difficulty'] ?? '',
        $recipeData['recipe_ServingSize'] ?? ''
        $recipeData['recipe_introduction'] ?? ''
    ]);

    $recipeId = $pdo->lastInsertId();

    // 材料を保存
    $sql = 'INSERT INTO ingredients (recipe_id, ingredient_name, quantity) VALUES (?, ?, ?)'; // カラム名を更新
    $stmt = $pdo->prepare($sql);
    foreach ($recipeData['ingredients'] as $ingredient) {
        $stmt->execute([
            $recipeId,
            $ingredient['ingredient_name'],
            $ingredient['quantity']
        ]);
    }

    // 作り方を保存
    $sql = 'INSERT INTO recipe_procedure (recipe_id, step_numbers, recipe_description) VALUES (?, ?, ?)'; // カラム名を更新
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
    $pdo->rollBack(); // エラーが発生した場合はロールバック
    echo json_encode(['message' => 'Database error: ' . $e->getMessage()]);
}
?>
