<?php
include('../config/database.php');

if (!isset($_GET['id']) || !isset($_GET['km_retour'])) {
    header("Location: list_missions.php");
    exit;
}

$num_mission = $_GET['id'];
$km_retour   = $_GET['km_retour'];

/* VALIDATION */
if (!is_numeric($km_retour)) {
    header("Location: list_missions.php?error=invalid_km");
    exit;
}

/* 🔥 GET VEHICLE + CURRENT KM */
$stmt = $pdo->prepare("
    SELECT m.id_vehicule, v.kilometrage 
    FROM mission m
    JOIN vehicule v ON m.id_vehicule = v.id_vehicule
    WHERE m.num_mission = ?
");
$stmt->execute([$num_mission]);
$data = $stmt->fetch();

if (!$data) {
    header("Location: list_missions.php");
    exit;
}

$id_vehicule = $data['id_vehicule'];
$km_actuel   = $data['kilometrage'];

/* 🔥 CORRECT VALIDATION (IMPORTANT) */
if ($km_retour <= $km_actuel) {
    header("Location: list_missions.php?error=km_invalid");
    exit;
}

/* UPDATE MISSION */
$stmt = $pdo->prepare("
    UPDATE mission 
    SET date_retour = CURDATE(),
        km_retour = ?
    WHERE num_mission = ?
");
$stmt->execute([$km_retour, $num_mission]);

/* UPDATE VEHICLE */
$stmt = $pdo->prepare("
    UPDATE vehicule 
    SET kilometrage = ?, etat = 'Disponible'
    WHERE id_vehicule = ?
");
$stmt->execute([$km_retour, $id_vehicule]);

header("Location: list_missions.php?success=finished");
exit;