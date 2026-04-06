<?php
include "../dashboard/header.php";
include "../dashboard/sidebar.php";
include "../config/database.php";

$stmt = $pdo->query("SELECT * FROM employe ORDER BY id_employe DESC");
$employees = $stmt->fetchAll();
?>

<div class="content">

    <div class="page-header">
        <h2>Liste des Chauffeurs</h2>
    </div>
    <div class="export-bar">
        <a href="export_chauffeurs.php" class="btn-export green"><i class="fas fa-file-excel"></i> Exporter Excel</a>
        <button onclick="printTable()" class="btn-export"><i class="fas fa-file-pdf"></i> Imprimer / PDF</button>
    </div>

    <?php if(isset($_GET['success'])): ?>
        <?php if($_GET['success'] == 'deleted'): ?>
            <div class="msg success">✅ Chauffeur supprimé avec succès.</div>
        <?php elseif($_GET['success'] == 'updated'): ?>
            <div class="msg success">✅ Chauffeur modifié avec succès.</div>
        <?php endif; ?>
    <?php endif; ?>
    <?php if(isset($_GET['error'])): ?>
        <div class="msg error">❌ Impossible de supprimer : chauffeur utilisé dans d'autres enregistrements.</div>
    <?php endif; ?>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nom</th>
                <th>Prénom</th>
                <th>Fonction</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($employees as $e): ?>
            <tr>
                <td><?= $e['id_employe'] ?></td>
                <td><?= htmlspecialchars($e['nom']) ?></td>
                <td><?= htmlspecialchars($e['prenom']) ?></td>
                <td><span class="badge <?= $e['fonction'] === 'Chauffeur' ? 'done' : 'warning' ?>"><?= htmlspecialchars($e['fonction']) ?></span></td>
                <td>
                    <a href="edit_chauffeur.php?id=<?= $e['id_employe'] ?>" class="btn-edit">✏ Modifier</a>
                    <?php $msg = "Supprimer " . htmlspecialchars($e['nom'].' '.$e['prenom'], ENT_QUOTES) . " ?"; ?>
                    <a href="#" class="btn-delete"
                       onclick="openDeleteModal('delete_chauffeur.php?id=<?= $e['id_employe'] ?>', '<?= $msg ?>')">🗑 Supprimer</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

</div>

<?php include "../dashboard/footer.php"; ?>
