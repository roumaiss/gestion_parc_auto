<?php
session_start(); // if not already started

// 🔒 PROTECT PAGE (PUT IT HERE)
if ($_SESSION['role'] !== 'admin') {
    header("Location: ../dashboard/index.php");
    exit;
}
include('../config/database.php');
include('../dashboard/header.php');
include('../dashboard/sidebar.php');

$stmt = $pdo->query("SELECT * FROM wilaya");
$wilayas = $stmt->fetchAll();
?>

<div class="content">
<div class="table-container">

    <h2>📍 Wilayas</h2>

    <table class="styled-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nom Wilaya</th>
            </tr>
        </thead>

        <tbody>
            <?php foreach($wilayas as $w): ?>
            <tr>
                <td><?= $w['id_wilaya'] ?></td>
                <td><?= $w['nom_wilaya'] ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>

    </table>

</div>
</div>