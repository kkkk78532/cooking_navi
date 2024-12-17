<?php
include('navbar.php'); // ナビゲーションバーを読み込む
include('dbconnect.php'); // データベース接続ファイルを読み込む
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>献立カレンダー</title>
  <link rel="stylesheet" href="style.css"/>
  <style>
    table {
      width: 100%;
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
    .calendar {
      margin: 20px auto;
    }
    .nav {
      text-align: center;
      margin: 20px;
    }
    .nav a {
      margin: 0 10px;
      text-decoration: none;
      color: #007BFF;
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

  <!-- 年月変更ナビゲーション -->
  <div class="nav">
    <a href="?year=<?= $prevYear ?>&month=<?= $prevMonth ?>">← 前月</a>
    <span><?= $year ?>年 <?= $month ?>月</span>
    <a href="?year=<?= $nextYear ?>&month=<?= $nextMonth ?>">次月 →</a>

    <div id="mealPlanSpace" class="bg-gray-200 p-4 rounded">
        <h2 class="text-xl font-bold">献立案</h2>
        <ul id="mealPlanList" class="space-y-2">
            <!-- AJAXで動的にリストを取得 -->
        </ul>
    </div>

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
            // 月の初日情報
            $firstDayOfMonth = "$year-$month-01";
            $firstWeekday = date('w', strtotime($firstDayOfMonth));
            $daysInMonth = date('t', strtotime($firstDayOfMonth));

            // カレンダー生成
            echo '<tr>';
            for ($i = 0; $i < $firstWeekday; $i++) {
                echo '<td></td>'; // 空白セル
            }

            for ($day = 1; $day <= $daysInMonth; $day++) {
                $currentDate = "$year-" . str_pad($month, 2, '0', STR_PAD_LEFT);

                // セル作成
                echo '<td ondragover="allowDrop(event)" ondrop="dropMealPlan(event, \'' . $currentDate . '\')">';
                echo "<strong>$day</strong>";
                if (isset($mockData[$currentDate])) {
                    foreach ($mockData[$currentDate] as $meal) {
                        echo "<p>$meal</p>";
                    }
                } else {
                    echo "<p>献立なし</p>";
                }
                echo '</td>';

                // 行の終了
                if (($day + $firstWeekday) % 7 == 0 || $day == $daysInMonth) {
                    if ($day < $daysInMonth) {
                        echo '<tr>';
                    }
                }
            }

            // 最後の行を埋める空白セル
            if (($daysInMonth + $firstWeekday) % 7 != 0) {
                for ($i = ($daysInMonth + $firstWeekday) % 7; $i < 7; $i++) {
                    echo '<td></td>'; // 空白セル
                }
                echo '</tr>'; // 最後の行を閉じる
            }
        ?>
    </tbody>

</body>

<script>
    // ページが読み込まれた後にリストを取得する
    document.addEventListener('DOMContentLoaded', function () {
        fetchMealPlans(); // ページが読み込まれたらリストを取得
    });

    function fetchMealPlans() {
        fetch('fetch_meal_plans.php')
            .then(response => response.json())
            .then(data => {
                const mealPlanList = document.getElementById('mealPlanList');
                mealPlanList.innerHTML = ''; // リストを初期化
                data.forEach(plan => {
                    const listItem = document.createElement('li');
                    listItem.textContent = plan.recipe_name; // レシピ名を表示
                    listItem.draggable = true;
                    listItem.dataset.id = plan.id; // 献立案のID
                    listItem.ondragstart = (e) => e.dataTransfer.setData('text/plain', plan.id);
                    mealPlanList.appendChild(listItem);
                });
            })
            .catch(error => console.error('Error fetching meal plans:', error));
    }

    function allowDrop(event) {
        event.preventDefault();
    }

    function dropMealPlan(event, date) {
        event.preventDefault();
        const planId = event.dataTransfer.getData('text/plain');
        fetch('update_meal_plan_date.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ id: planId, date: date })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('カレンダーに追加しました！');
                fetchMealPlans(); // 更新後に献立案を再取得
            } else {
                alert('追加に失敗しました。');
            }
        });
    }
</script>
</html>
