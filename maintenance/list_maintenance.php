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

<style>
.modal {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,0.5);
    z-index: 2000;
    align-items: center;
    justify-content: center;
}
.modal-content {
    background: var(--card-bg, #fff);
    border-radius: 12px;
    padding: 30px;
    width: 100%;
    max-width: 460px;
    position: relative;
    box-shadow: 0 10px 40px rgba(0,0,0,0.2);
}
.modal-content h3 { color: #1b5e42; margin-bottom: 20px; font-size: 18px; }
.close {
    position: absolute;
    top: 14px; right: 18px;
    font-size: 22px;
    cursor: pointer;
    color: #888;
    line-height: 1;
}
.close:hover { color: #333; }
</style>

<div class="content">

    <div class="page-header">
        <h2>Maintenance</h2>
        <button class="btn-add" onclick="openModal()">+ Ajouter</button>
    </div>

    <table>
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
                        <strong><?= htmlspecialchars($m['marque']) ?></strong><br>
                        <small><?= htmlspecialchars($m['matricule']) ?></small>
                    </td>
                    <td><?= htmlspecialchars($m['type']) ?></td>
                    <td><?= $m['date_entretien'] ?></td>
                    <td><?= number_format($m['cout'], 2) ?> DZD</td>
                    <td><?= number_format($m['kilometrage']) ?> km</td>
                    <td>
                        <a href="edit_maintenance.php?id=<?= $m['num_entretien'] ?>" class="btn-edit">✏ Modifier</a>
                        <a href="delete_maintenance.php?id=<?= $m['num_entretien'] ?>"
                           class="btn-delete"
                           onclick="return confirm('Supprimer cet entretien ?')">🗑 Supprimer</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="6" style="text-align:center;color:#888;">Aucun entretien trouvé</td></tr>
            <?php endif; ?>
        </tbody>
    </table>

</div>

<!-- MODAL -->
<div id="modal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal()">&times;</span>
        <h3>Ajouter un Entretien</h3>
        <form method="POST" action="add_maintenance.php">

            <div class="input-group">
                <label>Véhicule</label>
                <select name="id_vehicule" required>
                    <?php
                    $v = $pdo->query("SELECT * FROM vehicule")->fetchAll();
                    foreach($v as $veh) {
                        echo "<option value='{$veh['id_vehicule']}'>" . htmlspecialchars($veh['marque']) . " - " . htmlspecialchars($veh['matricule']) . "</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="input-group">
                <label>Type d'entretien</label>
                <input type="text" name="type" required>
            </div>

            <div class="input-group">
                <label>Date</label>
                <input type="date" name="date" required>
            </div>

            <div class="input-group">
                <label>Coût (DZD)</label>
                <input type="number" name="cout" min="0">
            </div>

            <div class="input-group">
                <label>Kilométrage</label>
                <input type="number" name="kilometrage" min="0">
            </div>

            <button type="submit" class="submit-btn">Enregistrer</button>

        </form>
    </div>
</div>

<script>
function openModal()  { document.getElementById("modal").style.display = "flex"; }
function closeModal() { document.getElementById("modal").style.display = "none"; }
window.addEventListener('click', function(e) {
    if (e.target === document.getElementById('modal')) closeModal();
});
</script>

<?php include('../dashboard/footer.php'); ?>
