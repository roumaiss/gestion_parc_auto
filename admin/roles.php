<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../dashboard/index.php");
    exit;
}

include('../config/database.php');
include('../dashboard/header.php');
include('../dashboard/sidebar.php');
?>

<div class="content">

    <div class="page-header">
        <h2>Système de Rôles</h2>
    </div>

    <table>
        <thead>
            <tr>
                <th>Rôle</th>
                <th>Description</th>
                <th>Accès</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><span class="badge admin">Admin</span></td>
                <td>Accès complet au système</td>
                <td>Tous les modules + Administration (utilisateurs, wilayas, rôles)</td>
            </tr>
            <tr>
                <td><span class="badge user">User</span></td>
                <td>Accès limité</td>
                <td>Missions, Véhicules, Chauffeurs, Maintenance, Carburant, Incidents</td>
            </tr>
        </tbody>
    </table>

</div>

<?php include('../dashboard/footer.php'); ?>
