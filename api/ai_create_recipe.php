<?php
// env.php を読み込み
require_once '../env.php';

// JSONデータ取得
$posts = json_decode(file_get_contents('php://input'), true);

// テストデータまたはGemini APIを使用
$data = testData(); // テストデータを使用

// $data = createByAI($posts);

header('Content-Type: application/json');
echo $data;

/**
 * Gemini API処理
 */
function createByAI($conditions)
{
    // プロンプト作成
    $prompt = "次の条件でレシピを生成してください。" . PHP_EOL;
    $prompt .= "ジャンル: {$conditions['genre']}" . PHP_EOL;
    $prompt .= "キーワード: {$conditions['keywords']}" . PHP_EOL;
    $prompt .= "以下のJSON形式で出力してください:" . PHP_EOL;
    $prompt .= template();

    // データ作成
    $data = [
        'contents' => [
            [
                'parts' => [
                    ['text' => $prompt],
                ]
            ]
        ]
    ];

    // Google APIキーで Gemini APIのURL生成
    $uri = "https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key=" . GEMINI_API_KEY;

    // Gemini AIにリクエスト
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $uri);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json'
    ]);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        echo json_encode(['error' => curl_error($ch)]);
    } else {
        $response_data = json_decode($response, true);
        if (isset($response_data['candidates'][0]['content']['parts'][0]['text'])) {
            $text = $response_data['candidates'][0]['content']['parts'][0]['text'];
            $json = str_replace(['```json', '```'], '', $text); // JSON部分のみ抽出
        }
    }
    curl_close($ch);
    return $json;
}

/**
 * AIの結果フォーマット
 */
function template()
{
    $template = '
    {
        "recipes": [
            {
                "recipe_title": "xxxxx",
                "recipe_time": "30",
                "recipe_difficulty": "上級",
                "recipe_ServingSize": "1人前",
                "recipe_introduction": "xxxxxxxxxx"
            }
        ],
        "keywords": "キーワード1,キーワード2",
        "ingredients": [
            {
                "ingredient_name": "xxxxxx",
                "quantity": "100g"
            },
            {
                "ingredient_name": "xxxxxx",
                "quantity": "おおさじ1"
            },
        ],
        "recipe_procedure": [
            {
                "step_numbers": 1,
                "recipe_description": "xxxxxxxx"
            },
            {
                "step_numbers": 2,
                "recipe_description": "xxxxxxxx"
            }
        ]
    }';
    return $template;
}

/**
 * テストデータ
 */
function testData()
{
    $data = '
    {
        "recipes": [
            {
                "recipe_title": "チキンカレー",
                "recipe_time": "30分",
                "recipe_difficulty": "中級",
                "recipe_ServingSize": "4人前",
                "recipe_introduction": "簡単で美味しいチキンカレーのレシピです。"
            }
        ],
        "keywords": "チキン,カレー,スパイス,簡単",
        "ingredients": [
            {
                "ingredient_name": "鶏肉",
                "quantity": "300g"
            },
            {
                "ingredient_name": "玉ねぎ",
                "quantity": "1個"
            }
        ],
        "recipe_procedure": [
            {
                "step_numbers": 1,
                "recipe_description": "鶏肉を一口大に切り、玉ねぎをみじん切りにする。"
            },
            {
                "step_numbers": 2,
                "recipe_description": "鍋に油を熱し、玉ねぎを炒めて透明になるまで加熱する。"
            }
        ]
    }';
    return $data;
}
?>