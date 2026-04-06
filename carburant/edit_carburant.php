<?php
ob_start();
include('../config/database.php');
include('../dashboard/header.php');
include('../dashboard/sidebar.php');

$id = $_GET['id'] ?? null;
if (!$id) {
    header("Location: list_carburant.php");
    exit;
}

$stmt = $pdo->prepare("
    SELECT c.*, cd.reference, cd.montant, cd.num_detail
    FROM carburant c
    JOIN carburant_detail cd ON c.num_carburant = cd.num_carburant
    WHERE cd.num_detail = ?
");
$stmt->execute([$id]);
$data = $stmt->fetch();

if (!$data) {
    header("Location: list_carburant.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $date      = $_POST['date'];
    $reference = $_POST['reference'];
    $montant   = $_POST['montant'];

    try {
        $pdo->prepare("UPDATE carburant SET date_delivrance=? WHERE num_carburant=?")
            ->execute([$date, $data['num_carburant']]);

        $pdo->prepare("UPDATE carburant_detail SET reference=?, montant=? WHERE num_detail=?")
            ->execute([$reference, $montant, $id]);

        header("Location: list_carburant.php?success=updated");
        exit;
    } catch (PDOException $e) {
        header("Location: list_carburant.php?error=1");
        exit;
    }
}
?>

<div class="content">
<div class="form-container">

    <h2>Modifier Carburant ✏️</h2>

    <form method="POST">

        <div class="input-group">
            <label>Date</label>
            <input type="date" name="date" value="<?= $data['date_delivrance'] ?>" required>
        </div>

        <div class="input-group">
            <label>Type carburant</label>
            <select name="reference" required>
                <option value="Essence" <?= $data['reference']=='Essence'?'selected':'' ?>>Essence</option>
                <option value="Gasoil"  <?= $data['reference']=='Gasoil' ?'selected':'' ?>>Gasoil</option>
                <option value="Gaz"     <?= $data['reference']=='Gaz'    ?'selected':'' ?>>Gaz</option>
            </select>
        </div>

        <div class="input-group">
            <label>Montant (DZD)</label>
            <input type="number" step="0.01" name="montant" value="<?= $data['montant'] ?>" required>
        </div>

        <button type="submit" class="submit-btn">Enregistrer les modifications</button>

    </form>

</div>
</div>

<?php include('../dashboard/footer.php'); ?>
