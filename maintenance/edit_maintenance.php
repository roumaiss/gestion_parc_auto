<?php
include('../config/database.php');
include('../dashboard/header.php');
include('../dashboard/sidebar.php');

if (!isset($_GET['id'])) {
    header("Location: list_maintenance.php");
    exit;
}

$id = intval($_GET['id']);
$stmt = $pdo->prepare("SELECT * FROM entretien WHERE num_entretien = ?");
$stmt->execute([$id]);
$m = $stmt->fetch();

if (!$m) {
    header("Location: list_maintenance.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $type        = $_POST['type'];
    $date        = $_POST['date'];
    $cout        = $_POST['cout'];
    $kilometrage = $_POST['kilometrage'];

    $pdo->prepare("UPDATE entretien SET type=?, date_entretien=?, cout=?, kilometrage=? WHERE num_entretien=?")
        ->execute([$type, $date, $cout, $kilometrage, $id]);

    header("Location: list_maintenance.php?success=updated");
    exit;
}
?>

<div class="content">
<div class="form-container">

    <h2>Modifier Entretien ✏️</h2>

    <form method="POST">

        <div class="input-group">
            <label>Type d'entretien</label>
            <input type="text" name="type" value="<?= htmlspecialchars($m['type']) ?>" required>
        </div>

        <div class="input-group">
            <label>Date</label>
            <input type="date" name="date" value="<?= $m['date_entretien'] ?>" required>
        </div>

        <div class="input-group">
            <label>Coût (DZD)</label>
            <input type="number" name="cout" value="<?= $m['cout'] ?>" min="0">
        </div>

        <div class="input-group">
            <label>Kilométrage</label>
            <input type="number" name="kilometrage" value="<?= $m['kilometrage'] ?>" min="0">
        </div>

        <button type="submit" class="submit-btn">Enregistrer les modifications</button>

    </form>

</div>
</div>

<?php include('../dashboard/footer.php'); ?>
