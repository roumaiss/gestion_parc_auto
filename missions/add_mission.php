<?php
include('../config/database.php');
include('../dashboard/header.php');
include('../dashboard/sidebar.php');
?>

<div class="content">
<div class="form-container">

    <h2>Nouvelle Mission</h2>

    <?php if(isset($_GET['error']) && $_GET['error']=='date'): ?>
        <div class="msg error">Date sortie doit être inférieure à date retour ❌</div>
    <?php endif; ?>
    <?php if(isset($_GET['error']) && $_GET['error']=='km'): ?>
        <div class="msg error">Km départ doit être inférieur à Km retour ❌</div>
    <?php endif; ?>

    <form method="POST" action="create_mission.php" id="missionForm">

        <div class="input-group">
            <label>Véhicule</label>
            <select name="id_vehicule" required>
                <?php
                $stmt = $pdo->query("SELECT * FROM vehicule WHERE etat='Disponible'");
                foreach($stmt->fetchAll() as $v) {
                    echo "<option value='{$v['id_vehicule']}'>" . htmlspecialchars($v['marque']) . " - " . htmlspecialchars($v['matricule']) . "</option>";
                }
                ?>
            </select>
        </div>

        <div class="input-group">
            <label>Chauffeur</label>
            <select name="id_employe" required>
                <?php
                $stmt = $pdo->query("SELECT * FROM employe");
                foreach($stmt->fetchAll() as $d) {
                    echo "<option value='{$d['id_employe']}'>" . htmlspecialchars($d['nom']) . " " . htmlspecialchars($d['prenom']) . "</option>";
                }
                ?>
            </select>
        </div>

        <div class="input-group">
            <label>Date sortie</label>
            <input type="date" name="date_sortie" required>
        </div>

        <div class="input-group">
            <label>Date retour</label>
            <input type="date" name="date_retour">
        </div>

        <div class="input-group">
            <label>Wilaya départ</label>
            <select name="id_wilaya_depart">
                <?php
                $stmt = $pdo->query("SELECT * FROM wilaya");
                foreach($stmt->fetchAll() as $w) {
                    echo "<option value='{$w['id_wilaya']}'>" . htmlspecialchars($w['nom_wilaya']) . "</option>";
                }
                ?>
            </select>
        </div>

        <div class="input-group">
            <label>Wilaya arrivée</label>
            <select name="id_wilaya_arrivee">
                <?php
                $stmt = $pdo->query("SELECT * FROM wilaya");
                foreach($stmt->fetchAll() as $w) {
                    echo "<option value='{$w['id_wilaya']}'>" . htmlspecialchars($w['nom_wilaya']) . "</option>";
                }
                ?>
            </select>
        </div>

        <div class="input-group">
            <label>Objet</label>
            <textarea name="objet_mission" rows="3"></textarea>
        </div>

        <button type="submit" class="submit-btn">Créer Mission</button>

    </form>

</div>
</div>

<script>
document.getElementById("missionForm").addEventListener("submit", function(e) {
    let dateSortie = document.querySelector("input[name='date_sortie']").value;
    let dateRetour = document.querySelector("input[name='date_retour']").value;

    if (dateRetour && dateSortie > dateRetour) {
        alert("❌ Date sortie doit être inférieure à date retour");
        e.preventDefault();
    }
});
</script>

<?php include('../dashboard/footer.php'); ?>
