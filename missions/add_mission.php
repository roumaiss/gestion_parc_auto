<?php
include('../config/database.php');
include('../dashboard/header.php');
include('../dashboard/sidebar.php');
?>

<div class="content">
<div class="form-container">

    <div class="header">
        <h2>Nouvelle Mission</h2>
    </div>

    <!-- ✅ ERROR MESSAGES -->
    <?php if(isset($_GET['error']) && $_GET['error']=='date'): ?>
        <div class="msg error">Date sortie doit être inférieure à date retour ❌</div>
    <?php endif; ?>

    <?php if(isset($_GET['error']) && $_GET['error']=='km'): ?>
        <div class="msg error">Km départ doit être inférieur à Km retour ❌</div>
    <?php endif; ?>

    <form method="POST" action="create_mission.php" class="modern-form" id="missionForm">

        <div class="form-grid">

            <!-- VEHICULE -->
            <div>
                <label>Véhicule:</label>
                <select name="id_vehicule" required>
                    <?php
                    $stmt = $pdo->query("SELECT * FROM vehicule WHERE etat='Disponible'");
                    foreach($stmt->fetchAll() as $v) {
                        echo "<option value='{$v['id_vehicule']}'>{$v['marque']} - {$v['matricule']}</option>";
                    }
                    ?>
                </select>
            </div>

            <!-- CHAUFFEUR -->
            <div>
                <label>Chauffeur:</label>
                <select name="id_employe" required>
                    <?php
                    $stmt = $pdo->query("SELECT * FROM employe");
                    foreach($stmt->fetchAll() as $d) {
                        echo "<option value='{$d['id_employe']}'>{$d['nom']} {$d['prenom']}</option>";
                    }
                    ?>
                </select>
            </div>

            <!-- DATE SORTIE -->
            <div>
                <label>Date sortie:</label>
                <input type="date" name="date_sortie" required>
            </div>

            <!-- DATE RETOUR -->
            <div>
                <label>Date retour:</label>
                <input type="date" name="date_retour">
            </div>

           

            <!-- WILAYA DEPART -->
            <div>
                <label>Wilaya départ:</label>
                <select name="id_wilaya_depart">
                    <?php
                    $stmt = $pdo->query("SELECT * FROM wilaya");
                    foreach($stmt->fetchAll() as $w) {
                        echo "<option value='{$w['id_wilaya']}'>{$w['nom_wilaya']}</option>";
                    }
                    ?>
                </select>
            </div>

            <!-- WILAYA ARRIVEE -->
            <div>
                <label>Wilaya arrivée:</label>
                <select name="id_wilaya_arrivee">
                    <?php
                    $stmt = $pdo->query("SELECT * FROM wilaya");
                    foreach($stmt->fetchAll() as $w) {
                        echo "<option value='{$w['id_wilaya']}'>{$w['nom_wilaya']}</option>";
                    }
                    ?>
                </select>
            </div>

        </div>

        <!-- OBJET -->
        <label>Objet:</label>
        <textarea name="objet_mission"></textarea>

        <button type="submit" class="btn-add">Créer Mission</button>

    </form>

</div>
</div>

<!-- ✅ FRONTEND VALIDATION -->
<script>
document.getElementById("missionForm").addEventListener("submit", function(e) {

    let dateSortie = document.querySelector("input[name='date_sortie']").value;
    let dateRetour = document.querySelector("input[name='date_retour']").value;

    let kmDepart = document.querySelector("input[name='km_depart']").value;
    let kmRetour = document.querySelector("input[name='km_retour']").value;

    // DATE VALIDATION
    if (dateRetour && dateSortie > dateRetour) {
        alert("❌ Date sortie doit être inférieure à date retour");
        e.preventDefault();
        return;
    }

    // KM VALIDATION
    if (kmRetour && parseFloat(kmDepart) > parseFloat(kmRetour)) {
        alert("❌ Km départ doit être inférieur à Km retour");
        e.preventDefault();
        return;
    }

});
</script>

<?php include('../dashboard/footer.php'); ?>