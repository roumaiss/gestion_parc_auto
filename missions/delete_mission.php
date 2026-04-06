<?php
include('../config/database.php');

$id = $_GET['id'];

$stmt = $pdo->prepare("DELETE FROM mission WHERE num_mission = ?");
$stmt->execute([$id]);

header("Location: list_missions.php?success=deleted");
exit;