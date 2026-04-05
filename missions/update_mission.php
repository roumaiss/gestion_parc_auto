<?php
include('../config/database.php');

$id = $_POST['id'];
$km_retour = $_POST['km_retour'];

// 1. get vehicle id
$stmt = $pdo->prepare("SELECT id_vehicule FROM mission WHERE num_mission = ?");
$stmt->execute([$id]);
$mission = $stmt->fetch();

if ($mission) {

    // 2. update mission
    $stmt = $pdo->prepare("
        UPDATE mission 
        SET km_retour = ?, date_retour = CURDATE() 
        WHERE num_mission = ?
    ");
    $stmt->execute([$km_retour, $id]);

    // 3. update vehicle
    $stmt = $pdo->prepare("
        UPDATE vehicule 
        SET etat = 'Disponible' 
        WHERE id_vehicule = ?
    ");
    $stmt->execute([$mission['id_vehicule']]);
}

header("Location: list_missions.php");
exit;