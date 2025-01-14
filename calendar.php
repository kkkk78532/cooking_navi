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

        <?php foreach (array_chunk($calendar, 7) as $week): ?>
          <tr>
            <?php foreach ($week as $day): ?>
              <?php 
                $currentDate = $day ? sprintf('%04d-%02d-%02d', $year, $month, $day) : null;
                $recipeTitle = $currentDate && isset($plansByDate[$currentDate]) ? $plansByDate[$currentDate] : null;
              ?>
              <td onclick="selectDay(this, <?= $day ?>)" title="<?= $recipeTitle ?: '' ?>">
                <?php if ($day): ?>
                  <?= $day ?>
                  <?php if ($recipeTitle): ?>
                    <div style="font-size: 0.8em; color: gray;">(<?= htmlspecialchars($recipeTitle) ?>)</div>
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
      <ul id="mealPlanList">
        <li>献立がありません</li>
      </ul>
    </div>
  </div>

  <script>
    let selectedDate = null;
    let selectedRecipeId = null;

    function selectDay(target, day) {
      if (!day) return;

      document.querySelectorAll('.calendar td').forEach(td => td.classList.remove('selected'));
      target.classList.add('selected');

      const year = document.getElementById('year').innerText;
      const month = document.getElementById('month').innerText.padStart(2, '0');
      selectedDate = `${year}-${month}-${String(day).padStart(2, '0')}`;
      console.log('Selected date:', selectedDate);
    }

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
      fetch('api/save_plan.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({ date, recipe_id }),
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
  </script>
</body>
</html>