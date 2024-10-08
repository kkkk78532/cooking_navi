<?php
include('navbar.php'); // ナビゲーションバーを読み込む
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>クッキングナビ - チキンカレー</title>
    <link rel="stylesheet" href="style.css"/>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
        }

        .recipe-title {
            color: #333333;
            font-size: 26px;
            text-align: center;
            margin-top: 20px;
        }

        .recipe-info {
            font-size: 20px;
            text-align: center;
            margin: 10px 0;
        }

        .recipe-introduction {
            padding: 10px;
            background-color: #DDDDDD;
            text-align: center;
            margin-top: 10px;
            font-size: 20px;
        }

        .recipe-ingredients, .recipe-instructions {
            margin: 20px;
        }

        .recipe-ingredients h2, .recipe-instructions h2 {
            color: #333333;
            font-size: 22px;
        }

        .recipe-ingredients ul {
            list-style: none;
            padding: 0;
        }

        .recipe-ingredients li {
            font-size: 20px;
            margin-bottom: 10px;
            border-bottom: 1px solid #ccc;
            padding: 5px 0;
        }

        .recipe-instructions div {
            background-color: #f9f9f9; 
            border-radius: 5px; 
            padding: 15px; 
            margin: 10px 0; 
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .recipe-instructions h3 {
            color: #ff5100; 
            font-size: 24px;
        }

        .recipe-instructions p {
            font-size: 20px;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <h1 class="recipe-title">チキンカレー</h1>
    <p class="recipe-info">調理時間: 30分　難易度: 中　人数: 4人分</p>
    <div class="recipe-introduction">
        <p>スパイスの香りが引き立つ、本格的なチキンカレーのレシピです。</p>
    </div>

    <!-- 材料情報 -->
    <div class="recipe-ingredients">
        <h2>材料</h2>
        <ul>
            <li>鶏もも肉 - 300g</li>
            <li>玉ねぎ - 2個</li>
            <li>カレーパウダー - 大さじ2</li>
            <li>にんじん - 1本</li>
            <li>じゃがいも - 2個</li>
        </ul>
    </div>

    <!-- 手順情報 -->
    <div class="recipe-instructions">
        <h2>手順</h2>
        <div>
            <h3>工程 1</h3>
            <p>鶏肉を一口大に切る。</p>
        </div>
        <div>
            <h3>工程 2</h3>
            <p>玉ねぎを薄切りにし、にんじんとじゃがいもを一口大に切る。</p>
        </div>
        <div>
            <h3>工程 3</h3>
            <p>フライパンに油を熱し、玉ねぎが透明になるまで炒める。</p>
        </div>
        <div>
            <h3>工程 4</h3>
            <p>鶏肉を加え、表面が白くなるまで炒める。</p>
        </div>
        <div>
            <h3>工程 5</h3>
            <p>カレーパウダーを加え、香りが立つまで炒める。</p>
        </div>
        <div>
            <h3>工程 6</h3>
            <p>にんじんとじゃがいもを加え、水を加えて煮込む。</p>
        </div>
        <div>
            <h3>工程 7</h3>
            <p>10〜15分煮込んで、具材が柔らかくなったら完成。</p>
        </div>
    </div>
</body>
</html>
