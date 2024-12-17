<?php
include('navbar.php'); // ナビゲーションバーを読み込む
include('dbconnect.php'); // データベース接続ファイルを読み込む


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


try {
    // PDOでデータベース接続
    $pdo = new PDO($info, $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // トランザクション開始
    $pdo->beginTransaction();

    // レシピを保存
    $recipe = $recipeData;
    $sql = 'INSERT INTO recipes (recipe_title, recipe_time, recipe_difficulty, recipe_ServingSize, recipe_introduction) VALUES (?, ?, ?, ?, ?)';
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        $recipe['recipe_title'],
        $recipe['recipe_time'] ?? '',
        $recipe['recipe_difficulty'] ?? '',
        $recipe['recipe_ServingSize'] ?? '',
        $recipe['recipe_introduction'] ?? ''
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

    // meal_plansにデータを保存
    if (isset($recipeData['plan_date']) && isset($recipeData['user_id']) && isset($recipeData['meal_type'])) {
        $sql = 'INSERT INTO meal_plans (user_id, recipe_id, plan_date, meal_type) VALUES (?, ?, ?, ?)';
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $recipeData['user_id'], // ユーザーID
            $recipeId,               // レシピID（先ほど保存したレシピID）
            $recipeData['plan_date'],// 計画日
            $recipeData['meal_type'] // 食事の種類（朝食、昼食、夕食など）
        ]);
    }

    // コミット
    $pdo->commit();
    echo json_encode(['message' => 'Recipe saved and added to meal plan successfully']);
} catch (PDOException $e) {
    $pdo->rollBack();
    echo json_encode(['message' => 'Database error occurred.']);
}
?>
