<?php
include('../config/database.php');

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: list_carburant.php?error=1");
    exit;
}

$id = $_GET['id'];

try {
    $stmt = $pdo->prepare("DELETE FROM carburant_detail WHERE num_detail = ?");
    $stmt->execute([$id]);

    header("Location: list_carburant.php?success=deleted");
    exit;

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}