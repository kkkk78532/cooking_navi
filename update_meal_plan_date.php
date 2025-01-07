<?php
session_start();
include('navbar.php');

$data = json_decode(file_get_contents('php://input'), true);
$id = $data['id'];
$date = $data['date'];

if ($id && $date) {
    $stmt = $db->prepare("UPDATE meal_plans SET date = :date WHERE id = :id");
    $stmt->bindParam(':date', $date);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false]);
    }
}
