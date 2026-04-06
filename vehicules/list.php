<?php
include "../dashboard/header.php";
include "../dashboard/sidebar.php";
require_once "../config/db.php";

$stmt = $pdo->query("SELECT * FROM vehicule ORDER BY id_vehicule DESC");
$vehicules = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="content">

    <div class="page-header">
        <h2>Liste des Véhicules</h2>
    </div>
    <div class="export-bar">
        <a href="export.php" class="btn-export green"><i class="fas fa-file-excel"></i> Exporter Excel</a>
        <button onclick="printTable()" class="btn-export"><i class="fas fa-file-pdf"></i> Imprimer / PDF</button>
    </div>

    <?php if (isset($_GET['deleted'])): ?>
        <div class="msg success">✅ Véhicule supprimé avec succès.</div>
    <?php endif; ?>
    <?php if (isset($_GET['error']) && $_GET['error'] == 'used'): ?>
        <div class="msg error">❌ Impossible de supprimer ce véhicule (déjà utilisé dans des missions).</div>
    <?php endif; ?>
    <?php if (isset($_GET['error']) && $_GET['error'] == 'delete_failed'): ?>
        <div class="msg error">❌ Erreur lors de la suppression du véhicule.</div>
    <?php endif; ?>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Matricule</th>
                <th>Marque</th>
                <th>Modèle</th>
                <th>Kilométrage</th>
                <th>Date achat</th>
                <th>État</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($vehicules as $v): ?>
            <?php
                $badgeClass = match($v['etat']) {
                    'Disponible' => 'done',
                    'En mission' => 'active',
                    'Maintenance' => 'warning',
                    'En panne'   => 'danger',
                    default      => 'warning'
                };
            ?>
            <tr>
                <td><?= $v['id_vehicule'] ?></td>
                <td><strong><?= htmlspecialchars($v['matricule']) ?></strong></td>
                <td><?= htmlspecialchars($v['marque']) ?></td>
                <td><?= htmlspecialchars($v['modele']) ?></td>
                <td><?= number_format($v['kilometrage']) ?> km</td>
                <td><?= $v['date_achat'] ?></td>
                <td><span class="badge <?= $badgeClass ?>"><?= $v['etat'] ?></span></td>
                <td>
                    <a href="edit.php?id=<?= $v['id_vehicule'] ?>" class="btn-edit">✏ Modifier</a>
                    <?php $msg = "Supprimer le véhicule " . htmlspecialchars($v['marque'].' '.$v['matricule'], ENT_QUOTES) . " ?"; ?>
                    <a href="#" class="btn-delete"
                       onclick="openDeleteModal('delete.php?id=<?= $v['id_vehicule'] ?>', '<?= $msg ?>')">🗑 Supprimer</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

</div>

<?php include "../dashboard/footer.php"; ?>
