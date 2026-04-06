<?php
require_once "../config/db.php";

$stmt = $pdo->query("SELECT nom, prenom, fonction FROM employe ORDER BY id_employe DESC");
$rows = $stmt->fetchAll();

$headers = ['Nom', 'Prénom', 'Fonction'];
$title   = 'Liste des Chauffeurs';

require_once "../exports/excel_export.php";
exportExcel($title, $headers, $rows, 'chauffeurs_' . date('Y-m-d'));
