<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../dashboard/index.php");
    exit;
}

include('../config/database.php');
include('../dashboard/header.php');
include('../dashboard/sidebar.php');

$stmt = $pdo->query("SELECT * FROM wilaya ORDER BY id_wilaya ASC");
$wilayas = $stmt->fetchAll();
?>

<div class="content">

    <div class="page-header">
        <h2>Liste des Wilayas</h2>
    </div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nom Wilaya</th>
            </tr>
        </thead>
        <tbody>
            <?php if(count($wilayas) > 0): ?>
                <?php foreach($wilayas as $w): ?>
                <tr>
                    <td><?= $w['id_wilaya'] ?></td>
                    <td><?= htmlspecialchars($w['nom_wilaya']) ?></td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="2" style="text-align:center;color:#888;">Aucune wilaya trouvée</td></tr>
            <?php endif; ?>
        </tbody>
    </table>

</div>

<?php include('../dashboard/footer.php'); ?>
