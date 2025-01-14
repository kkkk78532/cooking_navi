<?php
require_once '../dbconnect.php';

$pdo = $db;
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// トランザクション開始
$pdo->beginTransaction();

// レシピを保存
// TODO: WHERE user_id
$sql = 'SELECT * FROM recipes';
$stmt = $pdo->prepare($sql);
$stmt->execute();

$recipes = $stmt->fetchAll(PDO::FETCH_ASSOC);

$json = json_encode($recipes);
echo $json;