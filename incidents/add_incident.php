<?php
include('../config/database.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $num_mission   = $_POST['num_mission'];
    $description   = $_POST['description'];
    $date_incident = $_POST['date_incident'];
    $gravite       = $_POST['gravite'];

    try {
        $stmt = $pdo->prepare("
            INSERT INTO incident (num_mission, description, date_incident, gravite)
            VALUES (?, ?, ?, ?)
        ");
        $stmt->execute([$num_mission, $description, $date_incident, $gravite]);
        header("Location: list_incidents.php?success=added");
        exit;
    } catch (PDOException $e) {
        header("Location: add_incident.php?error=1");
        exit;
    }
}

include('../dashboard/header.php');
include('../dashboard/sidebar.php');

$missions = $pdo->query("
    SELECT num_mission, objet_mission
    FROM mission
    ORDER BY num_mission DESC
")->fetchAll();
?>

<div class="content">
<div class="form-container">

    <h2>🚨 Ajouter un Incident</h2>

    <?php if(isset($_GET['error'])): ?>
        <div class="msg error">❌ Erreur lors de l'ajout de l'incident</div>
    <?php endif; ?>

    <form method="POST">

        <div class="input-group">
            <label>Mission</label>
            <select name="num_mission" required>
                <option value="" disabled selected>-- Sélectionner une mission --</option>
                <?php foreach($missions as $m): ?>
                    <option value="<?= $m['num_mission'] ?>">
                        <?= $m['num_mission'] ?> - <?= htmlspecialchars($m['objet_mission']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="input-group">
            <label>Description</label>
            <textarea name="description" rows="4" required></textarea>
        </div>

        <div class="input-group">
            <label>Date</label>
            <input type="date" name="date_incident" required>
        </div>

        <div class="input-group">
            <label>Gravité</label>
            <select name="gravite" required>
                <option value="Faible">Faible</option>
                <option value="Moyenne">Moyenne</option>
                <option value="Grave">Grave</option>
            </select>
        </div>

        <button type="submit" class="submit-btn">Ajouter Incident</button>

    </form>

</div>
</div>

<?php include('../dashboard/footer.php'); ?>
