<?php
include('../config/database.php');
session_start();

/* GET DATA */
$id_vehicule = $_POST['id_vehicule'];
$id_employe  = $_POST['id_employe'];
$date_sortie = $_POST['date_sortie'];
$date_retour = !empty($_POST['date_retour']) ? $_POST['date_retour'] : null;
$objet       = $_POST['objet_mission'];
$created_by  = $_SESSION['user_id'];

/* 🔥 NEW FIELDS */
$id_wilaya_depart  = $_POST['id_wilaya_depart'];
$id_wilaya_arrivee = $_POST['id_wilaya_arrivee'];

/* ✅ AUTO KM DEPART FROM VEHICLE */
$stmt = $pdo->prepare("SELECT kilometrage FROM vehicule WHERE id_vehicule=?");
$stmt->execute([$id_vehicule]);
$v = $stmt->fetch();

$km_depart = $v['kilometrage'];

/* INSERT MISSION */
$sql = "INSERT INTO mission 
(date_sortie, date_retour, objet_mission, id_vehicule, id_employe, created_by,
 km_depart, id_wilaya_depart, id_wilaya_arrivee)
VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $pdo->prepare($sql);
$stmt->execute([
    $date_sortie,
    $date_retour,
    $objet,
    $id_vehicule,
    $id_employe,
    $created_by,
    $km_depart,
    $id_wilaya_depart,
    $id_wilaya_arrivee
]);

/* 🔥 UPDATE VEHICLE STATUS */
$update = $pdo->prepare("UPDATE vehicule SET etat='En mission' WHERE id_vehicule=?");
$update->execute([$id_vehicule]);

header("Location: list_missions.php?success=created");
exit;