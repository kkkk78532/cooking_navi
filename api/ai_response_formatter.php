<?php
// JSONデータ取得
$inputData = json_decode(file_get_contents('php://input'), true);

// データの整形処理を実施
$formattedData = formatData($inputData);

// 整形されたデータを返す
header('Content-Type: application/json');
echo json_encode($formattedData);

function formatAIResponse($response) {
    // JSON形式のレスポンスをデコード
    $data = json_decode($response, true);
    
    // エラーチェック
    if (isset($data['error'])) {
        return ['success' => false, 'message' => $data['error']['message']];
    }

    // レシピデータを整形
    $formattedRecipe = [];
    if (isset($data['recipes']) && is_array($data['recipes'])) {
        foreach ($data['recipes'] as $recipe) {
            $formattedRecipe[] = [
                'title' => $recipe['recipe_title'] ?? 'タイトル未設定',
                'ingredients' => $recipe['ingredients'] ?? [],
                'instructions' => $recipe['recipe_procedure'] ?? '指示なし',
                'prep_time' => $recipe['recipe_time'] ?? '時間未設定',
                'servings' => $recipe['recipe_ServingSize'] ?? '不明',
            ];
        }
    }

    return ['success' => true, 'recipes' => $formattedRecipe];
}

// 例としてAPIからのレスポンスを受け取る
$response = file_get_contents('URL_OF_ai_create_recipe.php'); // 適切なAPIエンドポイントに変更
$formattedResponse = formatAIResponse($response);

// 整形したレスポンスを利用
if ($formattedResponse['success']) {
    // 整形されたレシピデータを表示する処理をここに追加
    foreach ($formattedResponse['recipes'] as $recipe) {
        echo "タイトル: " . $recipe['title'] . "\n";
        echo "材料: " . implode(', ', $recipe['ingredients']) . "\n";
        echo "作り方: " . $recipe['instructions'] . "\n";
        echo "準備時間: " . $recipe['prep_time'] . "\n";
        echo "分量: " . $recipe['servings'] . "\n";
        echo "---------------------\n";
    }
} else {
    echo "エラー: " . $formattedResponse['message'];
}
?>
