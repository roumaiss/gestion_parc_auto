<?php
require_once "../config/db.php";

$id = intval($_GET['id'] ?? 0);
if (!$id) {
    header("Location: list_chauffeurs.php?error=invalid");
    exit;
}

$check = $pdo->prepare("SELECT COUNT(*) FROM carburant WHERE id_employe = ?");
$check->execute([$id]);

if ($check->fetchColumn() > 0) {
    header("Location: list_chauffeurs.php?error=used");
    exit;
}

$pdo->prepare("DELETE FROM employe WHERE id_employe = ?")->execute([$id]);

header("Location: list_chauffeurs.php?success=deleted");
exit;
