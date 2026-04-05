<?php
ob_start(); // 🔥 prevent header errors

include('../config/database.php');
include('../dashboard/header.php');
include('../dashboard/sidebar.php');

$id = $_GET['id'] ?? null;

if (!$id) {
    header("Location: list_carburant.php");
    exit;
}

/* GET DATA */
$stmt = $pdo->prepare("
    SELECT carburant.*, carburant_detail.reference, carburant_detail.montant
    FROM carburant
    JOIN carburant_detail ON carburant.num_carburant = carburant_detail.num_carburant
    WHERE carburant.num_carburant = ?
");
$stmt->execute([$id]);
$data = $stmt->fetch();

if (!$data) {
    header("Location: list_carburant.php");
    exit;
}

/* HANDLE UPDATE (SAME LOGIC AS ADD) */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $date      = $_POST['date'];
    $reference = $_POST['reference'];
    $montant   = $_POST['montant'];

    try {
        // update carburant
        $stmt1 = $pdo->prepare("
            UPDATE carburant 
            SET date_delivrance=? 
            WHERE num_carburant=?
        ");
        $stmt1->execute([$date, $id]);

        // update detail
        $stmt2 = $pdo->prepare("
            UPDATE carburant_detail 
            SET reference=?, montant=? 
            WHERE num_carburant=?
        ");
        $stmt2->execute([$reference, $montant, $id]);

        // ✅ SAME BEHAVIOR AS ADD
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

    <h2>Modifier Carburant</h2>

    <form method="POST">

        <!-- DATE -->
        <label>Date</label>
        <input type="date" name="date" value="<?= $data['date_delivrance'] ?>" required>

        <!-- DROPDOWN -->
        <label>Type carburant</label>
        <select name="reference" required>
            <option value="Essence" <?= $data['reference']=='Essence' ? 'selected' : '' ?>>Essence</option>
            <option value="Gasoil" <?= $data['reference']=='Gasoil' ? 'selected' : '' ?>>Gasoil</option>
            <option value="Gaz" <?= $data['reference']=='Gaz' ? 'selected' : '' ?>>Gaz</option>
        </select>

        <!-- MONTANT -->
        <label>Montant (DZD)</label>
        <input type="number" step="0.01" name="montant" value="<?= $data['montant'] ?>" required>

        <button class="btn-add">Mettre à jour</button>

    </form>

</div>
</div>

<?php include('../dashboard/footer.php'); ?>