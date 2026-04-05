<?php
include('../config/database.php');
include('../dashboard/header.php');
include('../dashboard/sidebar.php');

/* =========================
   BUILD QUERY WITH FILTER
========================= */

$sql = "
SELECT 
    mission.*,
    vehicule.marque,
    vehicule.matricule,
    employe.nom,
    employe.prenom
FROM mission
JOIN vehicule ON mission.id_vehicule = vehicule.id_vehicule
JOIN employe ON mission.id_employe = employe.id_employe
WHERE 1
";

$params = [];

/* FILTER */
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
<div class="table-container">

    <div class="header">
        <h2>📋 Missions List</h2>
        <a href="add_mission.php" class="btn-add">+ Nouvelle Mission</a>
    </div>

    <!-- ❌ ERROR MESSAGE -->
    <?php if(isset($_GET['error']) && $_GET['error']=='km_invalid'): ?>
        <div class="msg error">❌ Km retour must be greater than current vehicle kilometrage</div>
    <?php endif; ?>

    <!-- ✅ SUCCESS MESSAGE -->
    <?php if(isset($_GET['success']) && $_GET['success']=='finished'): ?>
        <div class="msg success">✅ Mission finished successfully</div>
    <?php endif; ?>

    <!-- 🔍 FILTER -->
    <form method="GET" style="margin-bottom:15px; display:flex; gap:10px;">
        <input type="date" name="date_start" value="<?= $_GET['date_start'] ?? '' ?>">
        <input type="date" name="date_end" value="<?= $_GET['date_end'] ?? '' ?>">
        <button type="submit" class="btn-add">Search</button>
        <a href="list_missions.php" class="btn-delete">Reset</a>
    </form>

    <table class="modern-table">
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

                <!-- NUM -->
                <td><?= $row['num_mission'] ?></td>

                <!-- DATES -->
                <td>
                    <?= $row['date_sortie'] ?><br>
                    → <?= $row['date_retour'] ?? '---' ?>
                </td>

                <!-- VEHICLE -->
                <td>
                    <?= $row['marque'] ?><br>
                    <small><?= $row['matricule'] ?></small>
                </td>

                <!-- DRIVER -->
                <td>
                    <?= $row['nom'] ?> <?= $row['prenom'] ?>
                </td>

                <!-- STATUS -->
                <td>
                    <?php if(empty($row['date_retour']) || $row['date_retour']=='0000-00-00'): ?>
                        <span class="badge active">En cours</span>
                    <?php else: ?>
                        <span class="badge done">Terminée</span>
                    <?php endif; ?>
                </td>

                <!-- ACTIONS -->
                <td>
    <!-- DETAILS BUTTON -->
    <a href="details_mission.php?id=<?= $row['num_mission'] ?>" class="btn-view">
        Details
    </a>

    <?php if(empty($row['date_retour']) || $row['date_retour']=='0000-00-00'): ?>
        <button onclick="finishMission(<?= $row['num_mission'] ?>)" class="btn-edit">
            Finish
        </button>
    <?php else: ?>
        <span style="color:gray;">Done</span>
    <?php endif; ?>

    <a href="delete_mission.php?id=<?= $row['num_mission'] ?>"
       class="btn-delete"
       onclick="return confirm('Supprimer cette mission ?')">
       supprimer
    </a>
</td>

            </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="6">Aucune mission trouvée</td>
            </tr>
        <?php endif; ?>
        </tbody>

    </table>

</div>
</div>

<!-- 🔥 FINISH POPUP -->
<script>
function finishMission(id) {
    let km = prompt("Enter Km retour:");

    if (km === null || km === "" || isNaN(km)) {
        alert("❌ Please enter a valid number");
        return;
    }

    window.location.href = "finish_mission.php?id=" + id + "&km_retour=" + km;
}
</script>

<?php include('../dashboard/footer.php'); ?>