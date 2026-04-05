<?php
include('../config/database.php');
include('../dashboard/header.php');
include('../dashboard/sidebar.php');

$stmt = $pdo->query("
SELECT m.*, v.marque, v.matricule 
FROM entretien m
LEFT JOIN vehicule v ON m.id_vehicule = v.id_vehicule
ORDER BY m.num_entretien DESC
");
$maintenances = $stmt->fetchAll();
?>

<div class="content">

<div class="table-container">

    <div class="header">
        <h2>🔧 Maintenance</h2>
        <button class="btn-add" onclick="openModal()">+ Ajouter</button>
    </div>

    <table class="modern-table">
        <thead>
            <tr>
                <th>Véhicule</th>
                <th>Type</th>
                <th>Date</th>
                <th>Coût</th>
                <th>Kilométrage</th>
                <th>Actions</th>
            </tr>
        </thead>

        <tbody>
        <?php if(count($maintenances) > 0): ?>
            <?php foreach($maintenances as $m): ?>
            <tr>

                <td>
                    <?= $m['marque'] ?><br>
                    <small><?= $m['matricule'] ?></small>
                </td>

                <td><?= $m['type'] ?></td>

                <td><?= $m['date_entretien'] ?></td>

                <td><?= $m['cout'] ?> DZD</td>

                <td><?= $m['kilometrage'] ?> km</td>

                <td>
                    <a href="edit_maintenance.php?id=<?= $m['num_entretien'] ?>" class="btn-edit">
                        modifier
                    </a>

                    <a href="delete_maintenance.php?id=<?= $m['num_entretien'] ?>"
                       class="btn-delete"
                       onclick="return confirm('Delete this maintenance?')">
                        supprimer
                    </a>
                </td>

            </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="6">Aucun entretien trouvé</td>
            </tr>
        <?php endif; ?>
        </tbody>

    </table>

</div>
</div>

<!-- MODAL -->
<div id="modal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal()">&times;</span>

        <h3>Ajouter Entretien</h3>

        <form method="POST" action="add_maintenance.php">

            <label>Véhicule</label>
            <select name="id_vehicule" required>
                <?php
                $v = $pdo->query("SELECT * FROM vehicule")->fetchAll();
                foreach($v as $veh) {
                    echo "<option value='{$veh['id_vehicule']}'>{$veh['marque']} - {$veh['matricule']}</option>";
                }
                ?>
            </select>

            <label>Type d'entretien</label>
            <input type="text" name="type" required>

            <div class="form-grid">
                <div>
                    <label>Date</label>
                    <input type="date" name="date" required>
                </div>

                <div>
                    <label>Coût (DZD)</label>
                    <input type="number" name="cout">
                </div>
            </div>

            <label>Kilométrage</label>
            <input type="number" name="kilometrage">

            <!-- ❌ statut removed because not in DB -->

            <button type="submit" class="btn-add">Enregistrer</button>

        </form>
    </div>
</div>

<script>
function openModal() {
    document.getElementById("modal").style.display = "flex";
}
function closeModal() {
    document.getElementById("modal").style.display = "none";
}
</script>

<?php include('../dashboard/footer.php'); ?>