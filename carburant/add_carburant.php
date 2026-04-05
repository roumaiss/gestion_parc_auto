<?php
ob_start();

include('../config/database.php');

$employes = $pdo->query("SELECT * FROM employe")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_employe = $_POST['id_employe'];
    $date       = $_POST['date_delivrance'];
    $reference  = $_POST['reference'];
    $montant    = $_POST['montant'];

    try {
        $stmt1 = $pdo->prepare("
            INSERT INTO carburant (id_employe, date_delivrance)
            VALUES (?, ?)
        ");
        $stmt1->execute([$id_employe, $date]);

        $num_carburant = $pdo->lastInsertId();

        $stmt2 = $pdo->prepare("
            INSERT INTO carburant_detail (num_carburant, reference, montant)
            VALUES (?, ?, ?)
        ");
        $stmt2->execute([$num_carburant, $reference, $montant]);

        header("Location: list_carburant.php?success=added");
        exit;

    } catch (PDOException $e) {
        header("Location: list_carburant.php?error=1");
        exit;
    }
}

include('../dashboard/header.php');
include('../dashboard/sidebar.php');
?>

<div class="content">
<div class="form-container">

    <h2>⛽ Ajouter Carburant</h2>

    <form method="POST">

        <div class="input-group">
            <label>Employé</label>
            <select name="id_employe" required>
                <?php foreach($employes as $e): ?>
                    <option value="<?= $e['id_employe'] ?>">
                        <?= htmlspecialchars($e['nom']) ?> <?= htmlspecialchars($e['prenom']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="input-group">
            <label>Date</label>
            <input type="date" name="date_delivrance" required>
        </div>

        <div class="input-group">
            <label>Type carburant</label>
            <select name="reference" required>
                <option value="" disabled selected>-- Choisir --</option>
                <option value="Essence">Essence</option>
                <option value="Gasoil">Gasoil</option>
                <option value="Gaz">Gaz</option>
            </select>
        </div>

        <div class="input-group">
            <label>Montant (DZD)</label>
            <input type="number" step="0.01" min="1" name="montant" required>
        </div>

        <button type="submit" class="submit-btn">Ajouter Carburant</button>

    </form>

</div>
</div>

<?php include('../dashboard/footer.php'); ?>
