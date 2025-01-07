<?php
session_start();
include('navbar.php');

$data = json_decode(file_get_contents('php://input'), true);
$recipe_id = $data['recipe_id'];

if ($recipe_id) {
    $stmt = $db->prepare("INSERT INTO meal_plans (recipe_id) VALUES (:recipe_id)");
    $stmt->bindParam(':recipe_id', $recipe_id, PDO::PARAM_INT);
    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false]);
    }
}
?>
