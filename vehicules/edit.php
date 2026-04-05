<?php
// HEADER + SIDEBAR
include "../dashboard/header.php";
include "../dashboard/sidebar.php";

// DB
require_once "../config/db.php";

// Validate ID
if (!isset($_GET["id"]) || empty($_GET["id"])) {
    echo "<h2 style='margin-left:260px'>Vehicle ID missing.</h2>";
    include "../dashboard/footer.php";
    exit;
}

$id = intval($_GET["id"]);

// Fetch the vehicle
$stmt = $pdo->prepare("SELECT * FROM vehicule WHERE id_vehicule = ?");
$stmt->execute([$id]);
$vehicule = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$vehicule) {
    echo "<h2 style='margin-left:260px'>Vehicle not found.</h2>";
    include "../dashboard/footer.php";
    exit;
}

// Update handler
$success = "";
$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $matricule   = $_POST["matricule"];
    $marque      = $_POST["marque"];
    $modele      = $_POST["modele"];
    $kilometrage = $_POST["kilometrage"];
    $date_achat  = $_POST["date_achat"];
    $etat        = $_POST["etat"];

    $sql = $pdo->prepare("
        UPDATE vehicule SET 
            matricule = ?, 
            marque = ?, 
            modele = ?, 
            kilometrage = ?, 
            date_achat = ?, 
            etat = ?
        WHERE id_vehicule = ?
    ");

    if ($sql->execute([$matricule, $marque, $modele, $kilometrage, $date_achat, $etat, $id])) {
        $success = "Vehicle updated successfully!";
    } else {
        $error = "❌ Error updating vehicle.";
    }
}
?>

<link rel="stylesheet" href="../assets/forms.css">

<div class="page-actions">
    <a href="list.php" class="home-btn">⬅ Back to List</a>
</div>

<div class="form-container" style="margin-top:20px;">
    <h2>Edit Vehicle ✏️</h2>

    <?php if (!empty($success)): ?>
        <div class="msg success"><?= $success ?></div>
    <?php endif; ?>

    <?php if (!empty($error)): ?>
        <div class="msg error"><?= $error ?></div>
    <?php endif; ?>

    <form method="POST">
        <div class="input-group">
            <label>Matricule</label>
            <input type="text" name="matricule" value="<?= htmlspecialchars($vehicule['matricule']) ?>" required>
        </div>

        <div class="input-group">
            <label>Marque</label>
            <input type="text" name="marque" value="<?= htmlspecialchars($vehicule['marque']) ?>" required>
        </div>

        <div class="input-group">
            <label>Modèle</label>
            <input type="text" name="modele" value="<?= htmlspecialchars($vehicule['modele']) ?>" required>
        </div>

        <div class="input-group">
            <label>Kilométrage</label>
            <input type="number" name="kilometrage" value="<?= $vehicule['kilometrage'] ?>" required>
        </div>

        <div class="input-group">
            <label>Date d'achat</label>
            <input type="date" name="date_achat" value="<?= $vehicule['date_achat'] ?>" required>
        </div>

        <div class="input-group">
            <label>État</label>
            <select name="etat">
                <option value="Disponible"  <?= $vehicule['etat']=="Disponible"?"selected":"" ?>>Disponible</option>
                <option value="En mission"  <?= $vehicule['etat']=="En mission"?"selected":"" ?>>En mission</option>
                <option value="Maintenance" <?= $vehicule['etat']=="Maintenance"?"selected":"" ?>>Maintenance</option>
                <option value="En panne"    <?= $vehicule['etat']=="En panne"?"selected":"" ?>>En panne</option>
                <option value="Reserve"     <?= $vehicule['etat']=="Reserve"?"selected":"" ?>>Reserve</option>
            </select>
        </div>

        <button type="submit" class="submit-btn">Save Changes</button>
    </form>
</div>

<?php include "../dashboard/footer.php"; ?>
