<?php
include('../config/database.php');
include('../dashboard/header.php');
include('../dashboard/sidebar.php');

$id = $_GET['id'] ?? null;

if (!$id) {
    header("Location: list_incidents.php");
    exit;
}

/* GET INCIDENT */
$stmt = $pdo->prepare("SELECT * FROM incident WHERE num_incident = ?");
$stmt->execute([$id]);
$incident = $stmt->fetch();

if (!$incident) {
    header("Location: list_incidents.php");
    exit;
}

/* UPDATE */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $description = $_POST['description'];
    $date        = $_POST['date_incident'];
    $gravite     = $_POST['gravite'];

    $update = $pdo->prepare("
        UPDATE incident 
        SET description=?, date_incident=?, gravite=? 
        WHERE num_incident=?
    ");

    $update->execute([$description, $date, $gravite, $id]);

    header("Location: list_incidents.php?success=updated");
    exit;
}
?>

<div class="content">
<div class="form-container">

    <h2>Modifier Incident</h2>

    <form method="POST">

        <label>Description</label>
        <textarea name="description" required><?= htmlspecialchars($incident['description']) ?></textarea>

        <label>Date</label>
        <input type="date" name="date_incident" value="<?= $incident['date_incident'] ?>" required>

        <label>Gravité</label>
        <select name="gravite">
            <option value="faible" <?= $incident['gravite']=='faible'?'selected':'' ?>>Faible</option>
            <option value="grave" <?= $incident['gravite']=='grave'?'selected':'' ?>>Grave</option>
        </select>

        <button class="btn-add">Mettre à jour</button>

    </form>

</div>
</div>

<?php include('../dashboard/footer.php'); ?>