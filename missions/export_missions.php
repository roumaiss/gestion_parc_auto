<?php
require_once "../config/db.php";

$stmt = $pdo->query("
    SELECT m.num_mission, v.marque, v.matricule, e.nom, e.prenom,
           m.date_sortie, m.date_retour, m.objet_mission,
           CASE WHEN m.date_retour IS NULL OR m.date_retour='0000-00-00' THEN 'En cours' ELSE 'Terminée' END as statut
    FROM mission m
    JOIN vehicule v ON m.id_vehicule = v.id_vehicule
    JOIN employe  e ON m.id_employe  = e.id_employe
    ORDER BY m.num_mission DESC
");
$rows = $stmt->fetchAll();

$headers = ['N° Mission', 'Véhicule', 'Matricule', 'Nom chauffeur', 'Prénom chauffeur', 'Date départ', 'Date retour', 'Objet', 'Statut'];
$title   = 'Liste des Missions';

require_once "../exports/excel_export.php";
exportExcel($title, $headers, $rows, 'missions_' . date('Y-m-d'));
