<?php
require_once "../config/db.php";

$stmt = $pdo->query("
    SELECT i.num_incident, v.marque, v.matricule, i.description, i.date_incident, i.gravite
    FROM incident i
    LEFT JOIN mission m ON i.num_mission = m.num_mission
    LEFT JOIN vehicule v ON m.id_vehicule = v.id_vehicule
    ORDER BY i.num_incident DESC
");
$rows = $stmt->fetchAll();

$headers = ['N° Incident', 'Véhicule', 'Matricule', 'Description', 'Date', 'Gravité'];
$title   = 'Liste des Incidents';

require_once "../exports/excel_export.php";
exportExcel($title, $headers, $rows, 'incidents_' . date('Y-m-d'));
