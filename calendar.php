<?php
session_start();
include('navbar.php'); // ナビゲーションバーを読み込む
include('dbconnect.php'); // データベース接続ファイルを読み込む
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
  ?>

  <div class="nav">
    <a href="?year=<?= $prevYear ?>&month=<?= $prevMonth ?>">&larr; 前月</a>
    <span><?= $year ?>年 <?= $month ?>月</span>
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

          echo '<tr>';
          for ($i = 0; $i < $firstWeekday; $i++) {
              echo '<td></td>';
          }

          for ($day = 1; $day <= $daysInMonth; $day++) {
              echo '<td>' . $day . '</td>';
              if (($day + $firstWeekday) % 7 == 0) {
                  echo '</tr><tr>';
              }
          }

          $remainingCells = (7 - (($daysInMonth + $firstWeekday) % 7)) % 7;
          for ($i = 0; $i < $remainingCells; $i++) {
              echo '<td></td>';
          }
          echo '</tr>';
        ?>
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
    document.addEventListener('DOMContentLoaded', function () {
        fetch('calendar/fetch_meal_plans.php')
            .then(response => response.json())
            .then(data => {
                const mealPlanList = document.getElementById('mealPlanList');
                mealPlanList.innerHTML = ''; // リストを初期化
                if (data.length === 0) {
                    mealPlanList.innerHTML = '<li>献立がありません</li>';
                } else {
                    data.forEach(plan => {
                        const listItem = document.createElement('li');
                        listItem.textContent = plan.recipe_name;
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
