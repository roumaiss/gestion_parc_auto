<?php
require_once "../config/db.php";

$stmt = $pdo->query("
    SELECT v.marque, v.matricule, m.type, m.date_entretien, m.cout, m.kilometrage
    FROM entretien m
    LEFT JOIN vehicule v ON m.id_vehicule = v.id_vehicule
    ORDER BY m.num_entretien DESC
");
$rows = $stmt->fetchAll();

$headers = ['Véhicule', 'Matricule', 'Type entretien', 'Date', 'Coût (DZD)', 'Kilométrage'];
$title   = 'Rapport Maintenance';

require_once "../exports/excel_export.php";
exportExcel($title, $headers, $rows, 'maintenance_' . date('Y-m-d'));
