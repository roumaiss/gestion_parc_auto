<?php
include('../config/database.php');
session_start();

$id_employe = $_POST['id_employe'];
$date = $_POST['date_delivrance'];
$reference = $_POST['reference'];
$montant = $_POST['montant'];
$created_by = $_SESSION['user_id'];

// 1️⃣ insert carburant
$stmt = $pdo->prepare("
INSERT INTO carburant (date_delivrance, id_employe, created_by)
VALUES (?, ?, ?)
");
$stmt->execute([$date, $id_employe, $created_by]);

$num_carburant = $pdo->lastInsertId();

// 2️⃣ insert detail
$stmt2 = $pdo->prepare("
INSERT INTO carburant_detail (reference, montant, num_carburant, created_by)
VALUES (?, ?, ?, ?)
");

$stmt2->execute([$reference, $montant, $num_carburant, $created_by]);

header("Location: list_carburant.php");
exit;