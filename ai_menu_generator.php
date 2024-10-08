<?php
include('navbar.php'); // ナビゲーションバーを読み込む
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>クッキングナビ - AI献立作成</title>
    <link rel="stylesheet" href="style.css"/>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
        }

        .container {
            width: 80%;
            margin: 20px auto;
        }

        .menu-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .menu-header h1 {
            color: #ff5100;
        }

        .menu-item {
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin-bottom: 20px;
        }

        .menu-item img {
            max-width: 100%;
            height: auto;
            border-radius: 10px;
            margin-bottom: 10px;
        }

        .menu-item h2 {
            color: #ff5100;
            font-size: 22px;
            margin-bottom: 10px;
        }

        .menu-item p {
            font-size: 16px;
            line-height: 1.6;
        }

        .ingredients, .instructions {
            margin-top: 20px;
        }

        .ingredients ul, .instructions ol {
            margin: 0;
            padding: 0;
            list-style: none;
        }

        .ingredients li, .instructions li {
            background-color: #f1f1f1;
            border: 1px solid #ddd;
            border-radius: 5px;
            margin-bottom: 10px;
            padding: 10px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            font-size: 18px;
            color: #333333;
            display: block;
            margin-bottom: 10px;
        }

        .form-group select, .form-group input {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .search-btn {
            background-color: #ff5100;
            color: white;
            font-size: 18px;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
            margin-top: 20px;
        }

        .search-btn:hover {
            background-color: #e04900;
        }

        .footer {
            text-align: center;
            padding: 10px 0;
            background-color: #333333;
            color: white;
            margin-top: 30px;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="menu-header">
        <h1>今週のおすすめ献立</h1>
        <p>AIが提案する1週間の献立です。お好みの条件を選んで献立を作成しましょう。</p>
    </div>

    <!-- AI検索機能のフォーム -->
    <div class="search-form">
        <div class="form-group">
            <label for="preference">お好みの食事タイプ</label>
            <select id="preference">
                <option value="normal">標準</option>
                <option value="vegetarian">ベジタリアン</option>
                <option value="low-carb">低炭水化物</option>
                <option value="high-protein">高タンパク</option>
            </select>
        </div>

        <div class="form-group">
            <label for="budget">1日の予算 (円)</label>
            <input type="number" id="budget" placeholder="例: 2000">
        </div>

        <!-- 新しい入力欄を追加 -->
        <div class="form-group">
            <label for="prep_time">作成時間 (分)</label>
            <input type="number" id="prep_time" placeholder="例: 30">
        </div>

        <div class="form-group">
            <label for="difficulty">難易度</label>
            <select id="difficulty">
                <option value="easy">簡単</option>
                <option value="medium">中級</option>
                <option value="hard">上級</option>
            </select>
        </div>

        <div class="form-group">
            <label for="servings">何人分</label>
            <input type="number" id="servings" placeholder="例: 2">
        </div>

        <button class="search-btn" onclick="alert('仮の検索機能です')">献立を作成する</button>
    </div>

    <!-- 月曜日のメニュー -->
    <div class="menu-item">
        <h2><a href="ai_recipe_display.php?recipe=chicken_curry">月曜日: チキンカレー</a></h2>
        <ul>
            <li>鶏もも肉 - 300g</li>
            <li>玉ねぎ - 2個</li>
            <li>カレーパウダー - 大さじ2</li>
            <li>にんじん - 1本</li>
            <li>じゃがいも - 2個</li>
        </ul>
    </div>

    <!-- 火曜日のメニュー -->
    <div class="menu-item">
        <h2><a href="ai_recipe_display.php?recipe=chicken_curry">火曜日: 豚の生姜焼き</a></h2>
        <ul>
            <li>豚ロース肉 - 200g</li>
            <li>生姜 - 1片</li>
            <li>醤油 - 大さじ2</li>
            <li>みりん - 大さじ1</li>
            <li>砂糖 - 小さじ1</li>
        </ul>
    </div>

    <!-- 水曜日のメニュー -->
    <div class="menu-item">
        <h2><a href="ai_recipe_display.php?recipe=chicken_curry">水曜日: トマトパスタ</a></h2>
        <ul>
            <li>スパゲッティ - 200g</li>
            <li>トマト缶 - 1缶</li>
            <li>にんにく - 2片</li>
            <li>オリーブオイル - 大さじ2</li>
            <li>バジル - 少々</li>
        </ul>
    </div>

    <!-- 木曜日のメニュー -->
    <div class="menu-item">
        <h2><a href="ai_recipe_display.php?recipe=chicken_curry">木曜日: 野菜炒め</a></h2>
        <ul>
            <li>キャベツ - 1/2玉</li>
            <li>にんじん - 1本</li>
            <li>もやし - 200g</li>
            <li>豚バラ肉 - 100g</li>
            <li>醤油 - 大さじ1</li>
        </ul>
    </div>

    <!-- 金曜日のメニュー -->
    <div class="menu-item">
        <h2><a href="ai_recipe_display.php?recipe=chicken_curry">金曜日: 鮭の塩焼き</a></h2>
        <ul>
            <li>鮭の切り身 - 2枚</li>
            <li>塩 - 適量</li>
            <li>レモン - 1/2個</li>
        </ul>
    </div>

    <!-- 土曜日のメニュー -->
    <div class="menu-item">
        <h2><a href="ai_recipe_display.php?recipe=chicken_curry">土曜日: 牛丼</a></h2>
        <ul>
            <li>牛肉 - 200g</li>
            <li>玉ねぎ - 1個</li>
            <li>醤油 - 大さじ2</li>
            <li>みりん - 大さじ1</li>
            <li>砂糖 - 小さじ1</li>
        </ul>
    </div>

    <!-- 日曜日のメニュー -->
    <div class="menu-item">
        <h2><a href="ai_recipe_display.php?recipe=chicken_curry">日曜日: 天ぷら</a></h2>
        <ul>
            <li>エビ - 4尾</li>
            <li>なす - 2本</li>
            <li>かぼちゃ - 1/4個</li>
            <li>天ぷら粉 - 100g</li>
        </ul>
    </div>

    <div class="footer">
        <p>© 2024 クッキングナビ</p>
    </div>
</div>
</body>
</html>
