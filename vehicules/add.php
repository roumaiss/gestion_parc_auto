<?php
// HEADER + SIDEBAR
include "../dashboard/header.php";
include "../dashboard/sidebar.php";
?>

<?php
// DB connection
require_once "../config/db.php";

// Form handler
$success = "";
$error   = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $matricule = $_POST['matricule'];

if (!preg_match('/^[0-9\-]+$/', $matricule)) {
    header("Location: add_vehicle.php?error=invalid_matricule");
    exit;
}
    $marque      = $_POST["marque"];
    $modele      = $_POST["modele"];
    $kilometrage = $_POST["kilometrage"];
    $date_achat  = $_POST["date_achat"];
    $etat        = $_POST["etat"];
    $created_by  = $_SESSION["user_id"];

    $sql = $pdo->prepare("
        INSERT INTO vehicule
        (matricule, marque, modele, kilometrage, date_achat, etat, created_by)
        VALUES (?, ?, ?, ?, ?, ?, ?)
    ");

    if ($sql->execute([$matricule, $marque, $modele, $kilometrage, $date_achat, $etat, $created_by])) {
        $success = "Vehicle added successfully!";
    } else {
        $error = "❌ Error: Could not add vehicle.";
    }
}
?>

<div class="content">
<?php if(isset($_GET['error']) && $_GET['error'] == 'invalid_matricule'): ?>
    <div class="msg error" style="max-width:480px;margin:0 auto 15px;">
        Matricule must contain only numbers and "-" ❌
    </div>
<?php endif; ?>

<div class="form-container">
    <h2>Ajouter Vehicle 🚗</h2>
    
    <?php if (!empty($success)): ?>
        <div class="msg success"><?= $success ?></div>
    <?php endif; ?>

    <?php if (!empty($error)): ?>
        <div class="msg error"><?= $error ?></div>
    <?php endif; ?>

    <form method="POST">
        <div class="input-group">
            <label>Matricule</label>
           <input 
    type="text" 
    name="matricule"
    pattern="[0-9\-]+"
    oninput="this.value = this.value.replace(/[^0-9-]/g, '')"
    title="Only numbers and '-' allowed"
    required
>
        </div>

        <div class="input-group">
            <label>Marque</label>
            <input type="text" name="marque" required>
        </div>

        <div class="input-group">
            <label>Modèle</label>
            <input type="text" name="modele" required>
        </div>

        <div class="input-group">
            <label>Kilométrage</label>
            <input type="number" name="kilometrage" required>
        </div>

        <div class="input-group">
            <label>Date d'achat</label>
            <input type="date" name="date_achat" required>
        </div>

        <div class="input-group">
            <label>État</label>
            <select name="etat" required>
                <option value="Disponible">Disponible</option>
                <option value="En mission">En mission</option>
                <option value="Maintenance">Maintenance</option>
                <option value="En panne">En panne</option>
                <option value="Reserve">Reserve</option>
            </select>
        </div>

        <button type="submit" class="submit-btn">ajouter Vehicle</button>
    </form>
</div>
</div>

<?php include "../dashboard/footer.php"; ?>
