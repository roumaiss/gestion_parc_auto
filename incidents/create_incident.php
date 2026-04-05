<?php
include('../config/database.php');
session_start();

$num_mission = $_POST['num_mission'];
$description = $_POST['description'];
$date = $_POST['date_incident'];
$gravite = $_POST['gravite'];
$created_by = $_SESSION['user_id'];

$stmt = $pdo->prepare("
INSERT INTO incident (description, date_incident, gravite, num_mission, created_by)
VALUES (?, ?, ?, ?, ?)
");

$stmt->execute([$description, $date, $gravite, $num_mission, $created_by]);

header("Location: list_incidents.php");
exit;