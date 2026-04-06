<?php
include('../config/database.php');
include('../dashboard/header.php');
include('../dashboard/sidebar.php');

$id = $_GET['id'] ?? null;
if (!$id) {
    header("Location: list_incidents.php");
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM incident WHERE num_incident = ?");
$stmt->execute([$id]);
$incident = $stmt->fetch();

if (!$incident) {
    header("Location: list_incidents.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $description = $_POST['description'];
    $date        = $_POST['date_incident'];
    $gravite     = $_POST['gravite'];

    $pdo->prepare("UPDATE incident SET description=?, date_incident=?, gravite=? WHERE num_incident=?")
        ->execute([$description, $date, $gravite, $id]);

    header("Location: list_incidents.php?success=updated");
    exit;
}
?>

<div class="content">
<div class="form-container">

    <h2>Modifier Incident ✏️</h2>

    <form method="POST">

        <div class="input-group">
            <label>Description</label>
            <textarea name="description" rows="4" required><?= htmlspecialchars($incident['description']) ?></textarea>
        </div>

        <div class="input-group">
            <label>Date</label>
            <input type="date" name="date_incident" value="<?= $incident['date_incident'] ?>" required>
        </div>

        <div class="input-group">
            <label>Gravité</label>
            <select name="gravite">
                <option value="Faible"  <?= $incident['gravite']=='Faible' ?'selected':'' ?>>Faible</option>
                <option value="Moyenne" <?= $incident['gravite']=='Moyenne'?'selected':'' ?>>Moyenne</option>
                <option value="Grave"   <?= $incident['gravite']=='Grave'  ?'selected':'' ?>>Grave</option>
            </select>
        </div>

        <button type="submit" class="submit-btn">Enregistrer les modifications</button>

    </form>

</div>
</div>

<?php include('../dashboard/footer.php'); ?>
