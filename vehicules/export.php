<?php
require_once "../config/db.php";

$stmt = $pdo->query("SELECT matricule, marque, modele, kilometrage, date_achat, etat FROM vehicule ORDER BY id_vehicule DESC");
$rows = $stmt->fetchAll();

$headers = ['Matricule', 'Marque', 'Modèle', 'Kilométrage', 'Date achat', 'État'];
$title   = 'Liste des Véhicules';

require_once "../exports/excel_export.php";
exportExcel($title, $headers, $rows, 'vehicules_' . date('Y-m-d'));
