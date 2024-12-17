<?php
require('dbconnect.php');
include('navbar.php'); // ナビゲーションバーを読み込む

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

    // レシピIDを取得
    if (isset($_GET['recipe_id'])) {
        $recipeId = $_GET['recipe_id'];

        // データベースからレシピ情報を取得
        $sql = "SELECT * FROM recipes WHERE id = :recipe_id AND user_id = :user_id";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':recipe_id', $recipeId);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        $recipe = $stmt->fetch();

        if ($recipe) {
            // レシピ情報をフォームに事前入力
            $recipeTitle = $recipe['recipe_title'];
            $recipePicture = $recipe['recipe_picture'];
            $recipeTime = $recipe['recipe_time'];
            $recipeDifficulty = $recipe['recipe_difficulty'];
            $recipeServingSize = $recipe['recipe_ServingSize'];
            $recipeIntroduction = $recipe['recipe_introduction'];

            // 材料と手順のデータを取得
            $sqlIngredients = "SELECT * FROM ingredients WHERE recipe_id = :recipe_id";
            $stmtIngredients = $db->prepare($sqlIngredients);
            $stmtIngredients->bindParam(':recipe_id', $recipeId);
            $stmtIngredients->execute();
            $ingredients = $stmtIngredients->fetchAll();

            $sqlInstructions = "SELECT * FROM recipe_procedure WHERE recipe_id = :recipe_id ORDER BY step_numbers";
            $stmtInstructions = $db->prepare($sqlInstructions);
            $stmtInstructions->bindParam(':recipe_id', $recipeId);
            $stmtInstructions->execute();
            $instructions = $stmtInstructions->fetchAll();
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>レシピ編集 - クッキングナビ</title>
    <link rel="stylesheet" href="style.css"/>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
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
            background-color: #ff5100;
            color: #fff;
            border: none;
            padding: 10px 15px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        button:hover {
            background-color: #e64a00;
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
            gap: 10px;
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
    <h1>レシピの編集</h1>
    <form action="recipe_update_do.php" method="post" enctype="multipart/form-data">
        <input type="hidden" name="recipeId" value="<?php echo htmlspecialchars($recipeId, ENT_QUOTES, 'UTF-8'); ?>">

        <label for="recipeTitle">レシピタイトル:</label>
        <input type="text" id="recipeTitle" name="recipeTitle" value="<?php echo htmlspecialchars($recipeTitle, ENT_QUOTES, 'UTF-8'); ?>" required>

        <label for="recipepicture">写真:</label>
        <?php if ($recipePicture): ?>
            <img src="<?php echo htmlspecialchars($recipePicture, ENT_QUOTES, 'UTF-8'); ?>" alt="レシピ写真" style="max-width: 200px;"><br>
        <?php endif; ?>
        <input type="file" id="recipepicture" name="recipepicture">

        <label>時間:</label>
        <input type="text" id="recipeTime" name="recipeTime" pattern="[0-9]*" value="<?php echo htmlspecialchars($recipeTime, ENT_QUOTES, 'UTF-8'); ?>" required> 分

        <label>難易度:</label>
        <input type="radio" id="recipeDifficulty_easy" name="recipeDifficulty" value="簡単" <?php echo ($recipe['recipe_difficulty'] == '簡単') ? 'checked' : ''; ?> required>
        <label for="recipeDifficulty_easy">簡単</label>
        <input type="radio" id="recipeDifficulty_medium" name="recipeDifficulty" value="中級" <?php echo ($recipe['recipe_difficulty'] == '中級') ? 'checked' : ''; ?>>
        <label for="recipeDifficulty_medium">中級</label>
        <input type="radio" id="recipeDifficulty_hard" name="recipeDifficulty" value="上級" <?php echo ($recipe['recipe_difficulty'] == '上級') ? 'checked' : ''; ?>>
        <label for="recipeDifficulty_hard">上級</label>

        <label for="recipeServingSize">何人分:</label>
        <input type="text" id="recipeServingSize" name="recipeServingSize" value="<?php echo htmlspecialchars($recipeServingSize, ENT_QUOTES, 'UTF-8'); ?>" required>

        <label for="recipeintroduction">レシピの説明:</label>
        <textarea rows="4" name="recipeintroduction" id="recipeintroduction" required><?php echo htmlspecialchars($recipeIntroduction, ENT_QUOTES, 'UTF-8'); ?></textarea>


        <label for="recipeIngredients">材料:</label>
        <div id="ingredientInputs" class="ingredient-inputs">
            <?php foreach ($ingredients as $ingredient) { ?>
                <div>
                    <input type="text" name="ingredient_names[]" value="<?php echo htmlspecialchars($ingredient['ingredient_name'], ENT_QUOTES, 'UTF-8'); ?>" placeholder="材料" required>
                    <input type="text" name="ingredient_quantities[]" value="<?php echo htmlspecialchars($ingredient['quantity'], ENT_QUOTES, 'UTF-8'); ?>" placeholder="量" required>
                    <input type="text" name="ingredient_units[]" value="<?php echo htmlspecialchars($ingredient['unit'], ENT_QUOTES, 'UTF-8'); ?>" placeholder="単位">
                    <button type="button" class="removeIngredient">削除</button>
                </div>
            <?php } ?>
        </div>
        <button type="button" id="addIngredient">材料を追加</button>

        <label for="recipeInstructions">作り方:</label>
        <div id="instructionInputs" class="instruction-inputs">
            <?php foreach ($instructions as $instruction) { ?>
                <div class="instruction">
                    <h3>工程<?php echo htmlspecialchars($instruction['step_numbers'], ENT_QUOTES, 'UTF-8'); ?></h3>
                    <textarea rows="4" cols="50" name="recipe_description[]" placeholder="説明を入力してください" required><?php echo htmlspecialchars($instruction['recipe_description'], ENT_QUOTES, 'UTF-8'); ?></textarea>
                    <input type="hidden" name="steps[]" value="<?php echo htmlspecialchars($instruction['step_numbers'], ENT_QUOTES, 'UTF-8'); ?>">
                    <button type="button" class="removeInstruction">削除</button>
                    <button type="button" class="moveUp">上に移動</button>
                    <button type="button" class="moveDown">下に移動</button>
                </div>
            <?php } ?>
        </div>
        <button type="button" id="addInstruction">工程を追加</button>

        <div class="button-container">
            <input type="submit" value="更新">
        </div>
    </form>

    <script>
        $(document).ready(function() {
            function handleIngredients() {
                $('#addIngredient').click(function() {
                    $('#ingredientInputs').append(`
                        <div>
                            <input type="text" name="ingredient_names[]" placeholder="材料" required>
                            <input type="text" name="ingredient_quantities[]" placeholder="量" required>
                            <input type="text" name="ingredient_units[]" placeholder="単位">
                            <button type="button" class="removeIngredient">削除</button>
                        </div>
                    `);
                });

                $(document).on('click', '.removeIngredient', function() {
                    $(this).parent().remove();
                });
            }

            function handleInstructions() {
                $('#addInstruction').click(function() {
                    var instructionIndex = $('#instructionInputs > .instruction').length + 1; // 新しいインデックス
                    $('#instructionInputs').append(`
                        <div class="instruction">
                            <h3>工程${instructionIndex}</h3>
                            <textarea rows="4" cols="50" name="recipe_description[]" placeholder="説明を入力してください" required></textarea>
                            <input type="hidden" name="steps[]" value="${instructionIndex}">
                            <button type="button" class="removeInstruction">削除</button>
                            <button type="button" class="moveUp">上に移動</button>
                            <button type="button" class="moveDown">下に移動</button>
                        </div>
                    `);
                });

                $(document).on('click', '.removeInstruction', function() {
                    $(this).parent().remove();
                    updateStepNumbers();
                });

                $(document).on('click', '.moveUp', function() {
                    var instruction = $(this).closest('.instruction');
                    if (instruction.prev().length > 0) {
                        instruction.insertBefore(instruction.prev());
                        updateStepNumbers();
                    }
                });

                $(document).on('click', '.moveDown', function() {
                    var instruction = $(this).closest('.instruction');
                    if (instruction.next().length > 0) {
                        instruction.insertAfter(instruction.next());
                        updateStepNumbers();
                    }
                });

                function updateStepNumbers() {
                    $('#instructionInputs > .instruction').each(function(index) {
                        $(this).find('h3').text('工程' + (index + 1));
                        $(this).find('input[name="steps[]"]').val(index + 1);
                    });
                }
            }

            handleIngredients();
            handleInstructions();
        });
    </script>
</body>
</html>
<?php
        } else {
            echo "レシピが見つかりません。";
        }
    } else {
        echo "レシピIDが指定されていません。";
    }
} else {
    echo "ログインしてください。";
}
?>
