<?php
session_start();
include('navbar.php'); // ナビゲーションバーを読み込む
include('dbconnect.php'); // データベース接続ファイルを読み込む
$pdo = $db;
?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>献立カレンダー</title>
  <link rel="stylesheet" href="style.css"/>
  <style>
    .container {
      display: flex;
      justify-content: space-between;
      padding: 20px;
    }
    .calendar {
      width: 70%;
      border-collapse: collapse;
    }
    th, td {
      border: 1px solid #ccc;
      text-align: center;
      padding: 10px;
      cursor: pointer;
    }
    th {
      background-color: #f4f4f4;
    }
    .nav {
      text-align: center;
      margin: 20px 0;
    }
    .nav a {
      margin: 0 10px;
      text-decoration: none;
      color: #007BFF;
    }
    .meal-plan-list {
      width: 25%;
      background-color: #f9f9f9;
      padding: 15px;
      border: 1px solid #ddd;
      border-radius: 5px;
    }
    .meal-plan-list h2 {
      margin-top: 0;
    }
    .meal-plan-list ul {
      list-style-type: none;
      padding: 0;
    }
    .meal-plan-list li {
      margin: 8px 0;
      padding: 8px;
      background-color: #fff;
      border: 1px solid #ddd;
      border-radius: 5px;
      cursor: pointer;
    }
    .selected {
      background: rgb(255, 212, 184);
    }
    .highlight {
      font-size: 1.5em;
      font-weight: bold;
      color: #333; /* 必要に応じて文字色も変更 */
    }
    .large-select {
      font-size: 1.5em;
      padding: 10px;
      height: 2.5em;
      width: auto; /* 必要に応じて変更 */
      border: 1px solid #ccc; /* 境界線を調整 */
      border-radius: 5px; /* 角を丸く */
    }
    .small-button {
        font-size: 0.8em;
        padding: 5px 10px;
        border: 1px solid #ccc;
        background-color: #f0f0f0;
        cursor: pointer;
    }
    .small-button:hover {
        background-color: #e0e0e0;
    }
  </style>
</head>
<body>
  <h1>献立カレンダー</h1>

  <?php
    // 表示する年と月を決定
    $year = isset($_GET['year']) ? (int)$_GET['year'] : date('Y');
    $month = isset($_GET['month']) ? (int)$_GET['month'] : date('m');

    // 月をまたぐナビゲーションの計算
    $prevMonth = $month - 1;
    $prevYear = $year;
    if ($prevMonth < 1) {
        $prevMonth = 12;
        $prevYear--;
    }

    $nextMonth = $month + 1;
    $nextYear = $year;
    if ($nextMonth > 12) {
        $nextMonth = 1;
        $nextYear++;
    }

    // 保存済み献立を取得
    $sql = 'SELECT date, recipe_id, recipes.recipe_title FROM meal_plans JOIN recipes ON meal_plans.recipe_id = recipes.id WHERE MONTH(date) = :month AND YEAR(date) = :year';
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':month', $month, PDO::PARAM_INT);
    $stmt->bindParam(':year', $year, PDO::PARAM_INT);
    $stmt->execute();
    $mealPlans = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $plansByDate = [];
    foreach ($mealPlans as $plan) {
        $plansByDate[$plan['date']] = $plan['recipe_title'];
    }
  ?>

  <div class="nav">
    <a href="?year=<?= $prevYear ?>&month=<?= $prevMonth ?>">&larr; 前月</a>
    <span id="year"><?= $year ?></span>年
    <span id="month"><?= $month ?></span>月
    <a href="?year=<?= $nextYear ?>&month=<?= $nextMonth ?>">次月 &rarr;</a>
  </div>

  <div class="container">
    <table class="calendar">
      <thead>
        <tr>
          <th>日</th>
          <th>月</th>
          <th>火</th>
          <th>水</th>
          <th>木</th>
          <th>金</th>
          <th>土</th>
        </tr>
      </thead>
      <tbody>
        <?php
          $firstDayOfMonth = "$year-$month-01";
          $firstWeekday = date('w', strtotime($firstDayOfMonth));
          $daysInMonth = date('t', strtotime($firstDayOfMonth));

          $calendar = [];
          // 前の月の空白を埋める
          for ($i = 0; $i < $firstWeekday; $i++) {
              $calendar[] = '';
          }
          // 当月の日付を埋める
          for ($day = 1; $day <= $daysInMonth; $day++) {
              $calendar[] = $day;
          }
          // 次の月の空白を埋める
          while (count($calendar) % 7 !== 0) {
              $calendar[] = '';
          }
        ?>


        <?php
        // getRecipesForDay.php をインクルード（データ取得の部分を利用）
        include 'api/getRecipesForDay.php';

        // レシピ情報を取得してplansByDateに格納
        $plansByDate = [];
        foreach ($calendar as $day) {
            $currentDate = sprintf('%04d-%02d-%02d', $year, $month, $day);
            $recipesForDay = getRecipesForDay($pdo, $currentDate); // データベースからレシピを取得
            $plansByDate[$currentDate] = $recipesForDay;
        }

        foreach (array_chunk($calendar, 7) as $week): ?>
          <tr>
            <?php foreach ($week as $day): ?>
              <?php 
                $currentDate = $day ? sprintf('%04d-%02d-%02d', $year, $month, $day) : null;
                // 現在の日付に対するレシピ
                $recipesForDay = $currentDate && isset($plansByDate[$currentDate]) ? $plansByDate[$currentDate] : null;
              ?>
              <td onclick="selectDay(this, <?= $day ?>)" title="朝: <?= $recipesForDay['morning'] ?? '' ?>, 昼: <?= $recipesForDay['afternoon'] ?? '' ?>, 晩: <?= $recipesForDay['evening'] ?? '' ?>">
                <?php if ($day): ?>
                  <?= $day ?>
                  <?php if ($recipesForDay): ?>
                    <div style="font-size: 0.8em; color: gray;">
                      <div>朝: <?= htmlspecialchars($recipesForDay['morning'] ?? '') ?></div>
                      <div>昼: <?= htmlspecialchars($recipesForDay['afternoon'] ?? '') ?></div>
                      <div>晩: <?= htmlspecialchars($recipesForDay['evening'] ?? '') ?></div>
                    </div>
                  <?php endif; ?>
                <?php endif; ?>
              </td>
            <?php endforeach; ?>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>

    <div class="meal-plan-list">
      <h2>献立案一覧</h2>
        <!-- 時間帯選択用のドロップダウン -->
        <label for="mealType" id="mealTypeLabel" class="highlight" style="display:none;">時間帯：</label>
        <select id="mealType" name="meal_type" class="large-select" style="display:none;">
          <option value="morning">朝</option>
          <option value="afternoon">昼</option>
          <option value="evening">晩</option>
        </select>

      <ul id="mealPlanList">
        <li>献立がありません</li>
      </ul>
    </div>
  </div>

  <script>
    let selectedDate = null;
    let selectedMealType = null;
    let selectedRecipeId = null;

    // 日付が選択された時の処理
    function selectDay(target, day) {
      if (!day) return;

      document.querySelectorAll('.calendar td').forEach(td => td.classList.remove('selected'));
      target.classList.add('selected');

      const year = document.getElementById('year').innerText;
      const month = document.getElementById('month').innerText.padStart(2, '0');
      selectedDate = `${year}-${month}-${String(day).padStart(2, '0')}`;
      console.log('Selected date:', selectedDate);

      // 食事の種類を表示
      const mealTypeLabel = document.getElementById('mealTypeLabel');
      const mealTypeSelect = document.getElementById('mealType');

      mealTypeLabel.style.display = 'inline';  // ラベルを表示
      mealTypeSelect.style.display = 'inline';  // ドロップダウンを表示
    }

    // 食事の種類が選択された時の処理
    document.getElementById('mealType').addEventListener('change', function () {
      selectedMealType = this.value;
      console.log('Selected meal type:', selectedMealType);

      // ここで保存を促すなどの処理を行うことができます
      if (selectedDate && selectedMealType) {
        console.log(`保存: 日付: ${selectedDate}, 食事の種類: ${selectedMealType}`);
        // 必要に応じて保存処理を呼び出す
      }
    });

    function selectRecipe(recipe_id) {
      selectedRecipeId = recipe_id;
      console.log('Selected recipe ID:', selectedRecipeId);

      if (selectedDate && selectedRecipeId) {
        saveToDatabase(selectedDate, selectedRecipeId);
      } else {
        alert('日付と献立を選択してください');
      }
    }

    function saveToDatabase(date, recipe_id) {
      const mealType = document.getElementById('mealType').value;
      fetch('api/save_plan.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({ date, recipe_id, meal_type: mealType }),
      })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            alert('保存しました');
            location.reload();
          } else {
            alert('保存に失敗しました');
          }
        })
        .catch(error => {
          console.error('APIエラー:', error);
          alert('APIエラー');
        });
    }

    document.addEventListener('DOMContentLoaded', function () {
      fetch('api/recipes.php')
        .then(response => response.json())
        .then(data => {
          const mealPlanList = document.getElementById('mealPlanList');
          mealPlanList.innerHTML = ''; // リストを初期化
          if (data.length === 0) {
            mealPlanList.innerHTML = '<li>献立がありません</li>';
          } else {
            data.forEach(recipe => {
              const listItem = document.createElement('li');
              listItem.textContent = recipe.recipe_title;
              listItem.onclick = () => selectRecipe(recipe.id);
              mealPlanList.appendChild(listItem);
            });
          }
        })
        .catch(error => {
          console.error('献立案の取得中にエラー:', error);
          document.getElementById('mealPlanList').innerHTML = '<li>データを取得できませんでした</li>';
        });
    });

    function deleteRecipe(date, mealType) {
      if (confirm(`${mealType}のレシピを削除しますか？`)) {
        const formData = new FormData();
        formData.append('date', date);
        formData.append('meal_type', mealType);

        fetch('deleteRecipe.php', {
          method: 'POST',
          body: formData
        })
        .then(response => response.json())
        .then(data => {
          if (data.status === 'success') {
            alert('レシピが削除されました');
            location.reload(); // ページをリロードして変更を反映
          } else {
            alert('削除に失敗しました');
          }
        })
        .catch(error => {
          console.error('削除処理中にエラー:', error);
          alert('削除に失敗しました');
        });
      }
    }

  </script>
</body>
</html>