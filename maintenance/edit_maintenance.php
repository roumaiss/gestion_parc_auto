<?php
include('../config/database.php');
include('../dashboard/header.php');
include('../dashboard/sidebar.php');

// 🔒 Check ID exists
if(!isset($_GET['id'])){
    die("ID manquant");
}

$id = $_GET['id'];

// GET DATA
$stmt = $pdo->prepare("SELECT * FROM entretien WHERE num_entretien = ?");
$stmt->execute([$id]);
$m = $stmt->fetch();

// 🔒 Check if record exists
if(!$m){
    die("Entretien introuvable");
}

// UPDATE
if($_SERVER['REQUEST_METHOD'] == 'POST'){

    $type = $_POST['type'];
    $date = $_POST['date'];
    $cout = $_POST['cout'];
    $kilometrage = $_POST['kilometrage'];

    $update = $pdo->prepare("
        UPDATE entretien 
        SET type=?, date_entretien=?, cout=?, kilometrage=? 
        WHERE num_entretien=?
    ");

    $update->execute([$type, $date, $cout, $kilometrage, $id]);

    header("Location: list_maintenance.php");
    exit;
}
?>

<div class="content">
<div class="mission-container">

    <div class="header">
        <h2>Modifier Entretien</h2>
    </div>

    <form method="POST" class="mission-form">

        <label>Type d'entretien</label>
        <input type="text" name="type" 
               value="<?= htmlspecialchars($m['type']) ?>" required>

        <div class="form-grid">
            <div>
                <label>Date</label>
                <input type="date" name="date" 
                       value="<?= $m['date_entretien'] ?>" required>
            </div>

            <div>
                <label>Coût (DZD)</label>
                <input type="number" name="cout" 
                       value="<?= $m['cout'] ?>">
            </div>
        </div>

        <label>Kilométrage</label>
        <input type="number" name="kilometrage" 
               value="<?= $m['kilometrage'] ?>">

        <button type="submit" class="btn-add">Mettre à jour</button>

    </form>

</div>
</div>

<?php include('../dashboard/footer.php'); ?>