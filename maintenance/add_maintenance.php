<?php
include('../config/database.php');
session_start();

$id_vehicule = $_POST['id_vehicule'];
$type = $_POST['type'];
$date = $_POST['date'];
$cout = $_POST['cout'];
$kilometrage = $_POST['kilometrage'];
$created_by = $_SESSION['user_id'];

$stmt = $pdo->prepare("
INSERT INTO entretien 
(id_vehicule, type, date_entretien, cout, kilometrage, created_by)
VALUES (?, ?, ?, ?, ?, ?)
");

$stmt->execute([
    $id_vehicule,
    $type,
    $date,
    $cout,
    $kilometrage,
    $created_by
]);

header("Location: list_maintenance.php");
exit;