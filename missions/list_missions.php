<?php
include('../config/database.php');
include('../dashboard/header.php');
include('../dashboard/sidebar.php');

$sql = "
    SELECT mission.*, vehicule.marque, vehicule.matricule, employe.nom, employe.prenom
    FROM mission
    JOIN vehicule ON mission.id_vehicule = vehicule.id_vehicule
    JOIN employe  ON mission.id_employe  = employe.id_employe
    WHERE 1
";
$params = [];

if (!empty($_GET['date_start'])) {
    $sql .= " AND mission.date_sortie >= ?";
    $params[] = $_GET['date_start'];
}
if (!empty($_GET['date_end'])) {
    $sql .= " AND mission.date_sortie <= ?";
    $params[] = $_GET['date_end'];
}

$sql .= " ORDER BY mission.num_mission DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$missions = $stmt->fetchAll();
?>

<div class="content">

    <div class="page-header">
        <h2>Liste des Missions</h2>
        <a href="add_mission.php" class="btn-add">+ Nouvelle Mission</a>
    </div>

    <?php if(isset($_GET['success'])): ?>
        <?php if($_GET['success'] == 'finished'): ?>
            <div class="msg success">✅ Mission terminée avec succès.</div>
        <?php elseif($_GET['success'] == 'deleted'): ?>
            <div class="msg success">✅ Mission supprimée avec succès.</div>
        <?php elseif($_GET['success'] == 'added' || $_GET['success'] == 'created'): ?>
            <div class="msg success">✅ Mission créée avec succès.</div>
        <?php endif; ?>
    <?php endif; ?>
    <?php if(isset($_GET['error']) && $_GET['error'] == 'km_invalid'): ?>
        <div class="msg error">❌ Km retour doit être supérieur au kilométrage actuel du véhicule.</div>
    <?php endif; ?>

    <!-- FILTER -->
    <form method="GET" style="display:flex;gap:10px;margin-bottom:16px;align-items:center;">
        <input type="date" name="date_start" value="<?= htmlspecialchars($_GET['date_start'] ?? '') ?>" style="padding:8px 10px;border:2px solid #dcdcdc;border-radius:8px;font-size:14px;">
        <input type="date" name="date_end"   value="<?= htmlspecialchars($_GET['date_end']   ?? '') ?>" style="padding:8px 10px;border:2px solid #dcdcdc;border-radius:8px;font-size:14px;">
        <button type="submit" class="btn-add">Rechercher</button>
        <a href="list_missions.php" class="btn-delete">Réinitialiser</a>
    </form>

    <table>
        <thead>
            <tr>
                <th>N° Mission</th>
                <th>Dates</th>
                <th>Véhicule</th>
                <th>Chauffeur</th>
                <th>Statut</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if(count($missions) > 0): ?>
                <?php foreach($missions as $row): ?>
                <tr>
                    <td><strong><?= $row['num_mission'] ?></strong></td>
                    <td>
                        <?= $row['date_sortie'] ?><br>
                        <small>→ <?= $row['date_retour'] ?: '---' ?></small>
                    </td>
                    <td>
                        <strong><?= htmlspecialchars($row['marque']) ?></strong><br>
                        <small><?= htmlspecialchars($row['matricule']) ?></small>
                    </td>
                    <td><?= htmlspecialchars($row['nom']) ?> <?= htmlspecialchars($row['prenom']) ?></td>
                    <td>
                        <?php if(empty($row['date_retour']) || $row['date_retour'] == '0000-00-00'): ?>
                            <span class="badge active">En cours</span>
                        <?php else: ?>
                            <span class="badge done">Terminée</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <a href="details_mission.php?id=<?= $row['num_mission'] ?>" class="btn-view">Détails</a>
                        <?php if(empty($row['date_retour']) || $row['date_retour'] == '0000-00-00'): ?>
                            <button onclick="finishMission(<?= $row['num_mission'] ?>)" class="btn-edit">Terminer</button>
                        <?php endif; ?>
                        <?php $msg = "Supprimer la mission #" . $row['num_mission'] . " (" . htmlspecialchars($row['marque'], ENT_QUOTES) . ") ?"; ?>
                        <a href="#" class="btn-delete"
                           onclick="openDeleteModal('delete_mission.php?id=<?= $row['num_mission'] ?>', '<?= $msg ?>')">🗑 Supprimer</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="6" style="text-align:center;color:#888;">Aucune mission trouvée</td></tr>
            <?php endif; ?>
        </tbody>
    </table>

</div>

<script>
function finishMission(id) {
    let km = prompt("Entrer le Km retour :");
    if (km === null || km === "" || isNaN(km)) {
        alert("❌ Veuillez entrer un nombre valide.");
        return;
    }
    window.location.href = "finish_mission.php?id=" + id + "&km_retour=" + km;
}
</script>

<?php include('../dashboard/footer.php'); ?>
