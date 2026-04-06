<?php
include('../config/database.php');

$id = $_GET['id'];

// Check if used in carburant
$check = $pdo->prepare("SELECT COUNT(*) FROM carburant WHERE id_employe = ?");
$check->execute([$id]);

if ($check->fetchColumn() > 0) {
    echo "<script>alert('❌ Cannot delete: employee used in carburant'); window.location='list_chauffeurs.php';</script>";
    exit;
}

// Delete
$stmt = $pdo->prepare("DELETE FROM employe WHERE id_employe = ?");
$stmt->execute([$id]);

header("Location: list_chauffeurs.php?success=deleted");
exit;