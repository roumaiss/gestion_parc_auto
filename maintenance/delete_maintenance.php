<?php
include('../config/database.php');

$id = $_GET['id'];

$stmt = $pdo->prepare("DELETE FROM entretien WHERE num_entretien = ?");
$stmt->execute([$id]);

header("Location: list_maintenance.php?success=deleted");
exit;