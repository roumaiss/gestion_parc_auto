<?php
include('../config/database.php');

$id = $_GET['id'] ?? null;

if (!$id) {
    header("Location: list_incidents.php?error=1");
    exit;
}

try {
    $stmt = $pdo->prepare("DELETE FROM incident WHERE num_incident = ?");
    $stmt->execute([$id]);

    header("Location: list_incidents.php?success=deleted");
    exit;

} catch (PDOException $e) {
    header("Location: list_incidents.php?error=1");
    exit;
}