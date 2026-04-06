<?php
include "../dashboard/header.php";
include "../dashboard/sidebar.php";
require_once "../config/db.php";

if (!isset($_GET["id"]) || empty($_GET["id"])) {
    header("Location: list.php");
    exit;
}

$id = intval($_GET["id"]);
$stmt = $pdo->prepare("SELECT * FROM vehicule WHERE id_vehicule = ?");
$stmt->execute([$id]);
$vehicule = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$vehicule) {
    header("Location: list.php");
    exit;
}

$success = "";
$error   = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $matricule   = $_POST["matricule"];
    $marque      = $_POST["marque"];
    $modele      = $_POST["modele"];
    $kilometrage = $_POST["kilometrage"];
    $date_achat  = $_POST["date_achat"];
    $etat        = $_POST["etat"];

    $sql = $pdo->prepare("
        UPDATE vehicule SET matricule=?, marque=?, modele=?, kilometrage=?, date_achat=?, etat=?
        WHERE id_vehicule=?
    ");

    if ($sql->execute([$matricule, $marque, $modele, $kilometrage, $date_achat, $etat, $id])) {
        $success = "✅ Véhicule modifié avec succès.";
    } else {
        $error = "❌ Erreur lors de la modification.";
    }
}
?>

<div class="content">
<div class="form-container">

    <h2>Modifier Véhicule ✏️</h2>

    <?php if ($success): ?><div class="msg success"><?= $success ?></div><?php endif; ?>
    <?php if ($error):   ?><div class="msg error"><?= $error ?></div><?php endif; ?>

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
                <?php foreach(['Disponible','En mission','Maintenance','En panne','Reserve'] as $e): ?>
                <option value="<?= $e ?>" <?= $vehicule['etat']==$e?'selected':'' ?>><?= $e ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <button type="submit" class="submit-btn">Enregistrer les modifications</button>
    </form>

</div>
</div>

<?php include "../dashboard/footer.php"; ?>
