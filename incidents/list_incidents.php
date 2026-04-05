<?php
include('../config/database.php');
include('../dashboard/header.php');
include('../dashboard/sidebar.php');

$stmt = $pdo->query("
SELECT 
    i.*,
    m.objet_mission,
    v.marque,
    v.matricule
FROM incident i
LEFT JOIN mission m ON i.num_mission = m.num_mission
LEFT JOIN vehicule v ON m.id_vehicule = v.id_vehicule
ORDER BY i.num_incident DESC
");

$incidents = $stmt->fetchAll();
?>

<div class="page-actions">
    <a href="add_incident.php" class="home-btn">➕ Add Incident</a>
</div>

<div class="table-container">

    <h2>🚨 Incidents</h2>
<?php if(isset($_GET['success']) && $_GET['success']=='deleted'): ?>
    <div class="msg success">Incident supprimé</div>
<?php endif; ?>

<?php if(isset($_GET['success']) && $_GET['success']=='updated'): ?>
    <div class="msg success">Incident modifié</div>
<?php endif; ?>

<?php if(isset($_GET['error'])): ?>
    <div class="msg error">Erreur lors de l'opération</div>
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
    <tr>

        <td><?= $row['num_incident'] ?></td>

        <td>
            <?= htmlspecialchars($row['marque']) ?><br>
            <small><?= htmlspecialchars($row['matricule']) ?></small>
        </td>

        <td><?= htmlspecialchars($row['description']) ?></td>

        <td><?= $row['date_incident'] ?></td>

        <td>
            <?php if($row['gravite'] == 'grave'): ?>
                <span class="badge danger">Grave</span>
            <?php else: ?>
                <span class="badge success">Faible</span>
            <?php endif; ?>
        </td>

        <!-- 🔥 ACTIONS -->
        <td>
            <a href="edit_incident.php?id=<?= $row['num_incident'] ?>" class="btn-edit">
                modifier
            </a>

            <a href="delete_incident.php?id=<?= $row['num_incident'] ?>"
               class="btn-delete"
               onclick="return confirm('Supprimer cet incident ?')">
               supprimer
            </a>
        </td>

    </tr>
    <?php endforeach; ?>
<?php else: ?>
    <tr>
        <td colspan="6">Aucun incident trouvé</td>
    </tr>
<?php endif; ?>
</tbody>

    </table>

</div>

<?php include('../dashboard/footer.php'); ?>