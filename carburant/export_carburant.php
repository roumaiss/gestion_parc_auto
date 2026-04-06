<?php
require_once "../config/db.php";

$stmt = $pdo->query("
    SELECT e.nom, e.prenom, cd.reference, cd.montant, c.date_delivrance
    FROM carburant c
    JOIN carburant_detail cd ON c.num_carburant = cd.num_carburant
    LEFT JOIN employe e ON c.id_employe = e.id_employe
    ORDER BY cd.num_detail DESC
");
$rows = $stmt->fetchAll();

$headers = ['Nom', 'Prénom', 'Type carburant', 'Montant (DZD)', 'Date'];
$title   = 'Liste Carburant';

require_once "../exports/excel_export.php";
exportExcel($title, $headers, $rows, 'carburant_' . date('Y-m-d'));
