<?php
include('navbar.php'); // ナビゲーションバーを読み込む
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>自作レシピ投稿 - クッキングナビ</title>
    <link rel="stylesheet" href="style.css"/>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        /* body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            color: #333;
            margin: 0;
            padding: 20px;
        } */

        h1 {
            text-align: center;
            margin-bottom: 30px;
            color: #ff5100;
        }

        form {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        input[type="text"], input[type="file"], textarea {
            width: calc(100% - 22px);
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
            transition: border-color 0.3s;
        }

        input[type="text"]:focus, textarea:focus {
            border-color: #ff5100;
            outline: none;
        }

        input[type="radio"] {
            margin-right: 10px;
        }

        .ingredient-inputs, .instruction-inputs {
            margin-bottom: 15px;
        }

        button {
            background-color: #ff5100; /* 投稿ボタンのスタイル */
            color: #fff;
            border: none;
            padding: 10px 15px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #e64a00; /* 投稿ボタンのホバー時のスタイル */
        }

        .removeIngredient, .removeInstruction {
            background-color: #ff0000;
        }

        .removeIngredient:hover, .removeInstruction:hover {
            background-color: #e60000;
        }

        .button-container {
            display: flex;
            flex-direction: column;
            gap: 10px; /* ボタンの間隔を追加 */
        }

        input[type="submit"] {
            background-color: #28a745;
            color: white;
            border: none;
            padding: 10px;
            cursor: pointer;
            border-radius: 5px;
        }

        input[type="submit"]:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>
    <h1>自作レシピの投稿</h1>
    <form action="post_recipe_do.php" method="post" enctype="multipart/form-data">
        <label for="recipeTitle">レシピタイトル:</label>
        <input type="text" id="recipeTitle" name="recipeTitle" required>

        <label for="recipepicture">写真:</label>
        <input type="file" id="recipepicture" name="recipepicture" required>

        <label>時間:</label>
        <input type="text" id="recipeTime" name="recipeTime" pattern="[0-9]*" placeholder="半角数字で入力　例: 30" required> 分

        <label>難易度:</label>
        <input type="radio" id="recipeDifficulty_easy" name="recipeDifficulty" value="簡単" required>
        <label for="recipeDifficulty_easy">簡単</label>
        <input type="radio" id="recipeDifficulty_medium" name="recipeDifficulty" value="中級">
        <label for="recipeDifficulty_medium">中級</label>
        <input type="radio" id="recipeDifficulty_hard" name="recipeDifficulty" value="上級">
        <label for="recipeDifficulty_hard">上級</label>

        <label for="recipeServingSize">何人分:</label>
        <input type="text" id="recipeServingSize" name="recipeServingSize" placeholder="例: 4人分" required>

        <label for="recipeintroduction">レシピの説明:</label>
        <textarea rows="4" name="recipeintroduction" id="recipeintroduction" placeholder="料理の説明を入力してください" required></textarea>

        <label for="recipeIngredients">材料:</label>
        <div id="ingredientInputs" class="ingredient-inputs">
            <div>
                <input type="text" name="ingredient_names[]" placeholder="材料" required>
                <input type="text" name="ingredient_quantities[]" placeholder="量　(例：100g、大さじ1、少々)" required>
                <button type="button" class="removeIngredient">削除</button>
            </div>
        </div>
        <button type="button" id="addIngredient">材料を追加</button>

        <label for="recipeInstructions">作り方:</label>
        <div id="instructionInputs" class="instruction-inputs"></div>
        <button type="button" id="addInstruction">工程を追加</button>

        <div class="button-container">
            <input type="submit" value="投稿">
        </div>
    </form>

    <script>
        $(document).ready(function() {
            function handleIngredients() {
                var ingredientIndex = 1;

                $('#addIngredient').click(function() {
                    ingredientIndex++;
                    $('#ingredientInputs').append(`
                        <div>
                            <input type="text" name="ingredient_names[]" placeholder="材料" required>
                            <input type="text" name="ingredient_quantities[]" placeholder="量　(例：100g、大さじ1、少々)" required>
                            <button type="button" class="removeIngredient">削除</button>
                        </div>
                    `);
                });

                $(document).on('click', '.removeIngredient', function() {
                    $(this).parent().remove();
                });
            }

            // 作り方の処理をまとめた関数
            function handleInstructions() {
                var instructionIndex = 0; // 最初の工程のインデックス

                $('#addInstruction').click(function() {
                    instructionIndex++;
                    var stepLabel = '工程' + instructionIndex;
                    var newInstruction = `
                        <div class="instruction">
                            <h3>${stepLabel}</h3>
                            <textarea rows="4" cols="50" name="recipe_description[]" placeholder="説明を入力してください"></textarea>
                            <input type="hidden" name="steps[]" value="${instructionIndex}">
                            <button type="button" class="removeInstruction">削除</button>
                            <button type="button" class="moveUp">上に移動</button>
                            <button type="button" class="moveDown">下に移動</button>
                        </div>
                    `;
                    $('#instructionInputs').append(newInstruction);
                });

                // 工程を削除
                $(document).on('click', '.removeInstruction', function() {
                    $(this).parent().remove();
                    updateStepNumbers();  // 削除後に手順番号を更新
                });

                // 上に移動
                $(document).on('click', '.moveUp', function() {
                    var instruction = $(this).closest('.instruction');
                    if (instruction.prev().length > 0) {  // 最初の手順でない場合
                        instruction.insertBefore(instruction.prev());
                        updateStepNumbers();  // 移動後に手順番号を更新
                    }
                });

                // 下に移動
                $(document).on('click', '.moveDown', function() {
                    var instruction = $(this).closest('.instruction');
                    if (instruction.next().length > 0) {  // 最後の手順でない場合
                        instruction.insertAfter(instruction.next());
                        updateStepNumbers();  // 移動後に手順番号を更新
                    }
                });

                // 手順番号を更新
                function updateStepNumbers() {
                    instructionIndex = 0; // インデックスをリセット
                    $('#instructionInputs > .instruction').each(function() {
                        instructionIndex++;
                        $(this).find('h3').text('工程' + instructionIndex);
                        // hiddenフィールドも更新
                        $(this).find('input[name="steps[]"]').val(instructionIndex);
                    });
                }
            }


            handleIngredients();
            handleInstructions();
        });
    </script>
</body>
</html>
