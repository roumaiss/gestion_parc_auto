<?php
include('../config/database.php');
include('../dashboard/header.php');
include('../dashboard/sidebar.php');

$stmt = $pdo->query("
    SELECT i.*, m.objet_mission, v.marque, v.matricule
    FROM incident i
    LEFT JOIN mission m ON i.num_mission = m.num_mission
    LEFT JOIN vehicule v ON m.id_vehicule = v.id_vehicule
    ORDER BY i.num_incident DESC
");
$incidents = $stmt->fetchAll();
?>

<div class="content">

    <div class="page-header">
        <h2>Liste des Incidents</h2>
    </div>

    <?php if(isset($_GET['success']) && $_GET['success'] == 'deleted'): ?>
        <div class="msg success">✅ Incident supprimé.</div>
    <?php endif; ?>
    <?php if(isset($_GET['success']) && $_GET['success'] == 'updated'): ?>
        <div class="msg success">✅ Incident modifié.</div>
    <?php endif; ?>
    <?php if(isset($_GET['error'])): ?>
        <div class="msg error">❌ Erreur lors de l'opération.</div>
    <?php endif; ?>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Véhicule</th>
                <th>Description</th>
                <th>Date</th>
                <th>Gravité</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if(count($incidents) > 0): ?>
                <?php foreach($incidents as $row): ?>
                <?php
                    $badgeClass = match(strtolower($row['gravite'])) {
                        'grave'  => 'danger',
                        'moyenne'=> 'warning',
                        default  => 'done'
                    };
                ?>
                <tr>
                    <td><?= $row['num_incident'] ?></td>
                    <td>
                        <strong><?= htmlspecialchars($row['marque'] ?? '—') ?></strong><br>
                        <small><?= htmlspecialchars($row['matricule'] ?? '') ?></small>
                    </td>
                    <td><?= htmlspecialchars($row['description']) ?></td>
                    <td><?= $row['date_incident'] ?></td>
                    <td><span class="badge <?= $badgeClass ?>"><?= htmlspecialchars($row['gravite']) ?></span></td>
                    <td>
                        <a href="edit_incident.php?id=<?= $row['num_incident'] ?>" class="btn-edit">✏ Modifier</a>
                        <?php $msg = "Supprimer l'incident #" . $row['num_incident'] . " du " . $row['date_incident'] . " ?"; ?>
                        <a href="#" class="btn-delete"
                           onclick="openDeleteModal('delete_incident.php?id=<?= $row['num_incident'] ?>', '<?= $msg ?>')">🗑 Supprimer</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="6" style="text-align:center;color:#888;">Aucun incident trouvé</td></tr>
            <?php endif; ?>
        </tbody>
    </table>

</div>

<?php include('../dashboard/footer.php'); ?>
