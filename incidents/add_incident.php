<?php
include('../config/database.php');

/* =========================
   HANDLE FORM SUBMIT 🔥
========================= */
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

        // ✅ redirect with success
        header("Location: list_incidents.php?success=added");
        exit;

    } catch (PDOException $e) {
        header("Location: add_incident.php?error=1");
        exit;
    }
}

/* =========================
   LOAD UI
========================= */
include('../dashboard/header.php');
include('../dashboard/sidebar.php');

// get missions
$missions = $pdo->query("
    SELECT num_mission, objet_mission 
    FROM mission 
    ORDER BY num_mission DESC
")->fetchAll();
?>

<div class="content">
<div class="form-container">

    <h2>🚨 Add New Incident</h2>

    <!-- ✅ MESSAGE -->
    <?php if(isset($_GET['error'])): ?>
        <div class="msg error">❌ Error while adding incident</div>
    <?php endif; ?>

    <form method="POST">

        <!-- MISSION -->
        <label>Mission</label>
        <select name="num_mission" required>
            <option value="" disabled selected>-- Select mission --</option>
            <?php foreach($missions as $m): ?>
                <option value="<?= $m['num_mission'] ?>">
                    <?= $m['num_mission'] ?> - <?= $m['objet_mission'] ?>
                </option>
            <?php endforeach; ?>
        </select>

        <!-- DESCRIPTION -->
        <label>Description</label>
        <textarea name="description" rows="4" required></textarea>

        <!-- DATE -->
        <label>Date</label>
        <input type="date" name="date_incident" required>

        <!-- GRAVITE -->
        <label>Gravité</label>
        <select name="gravite" required>
            <option value="Faible">Faible</option>
            <option value="Moyenne">Moyenne</option>
            <option value="Grave">Grave</option>
        </select>

        <button type="submit" class="submit-btn">Add Incident</button>

    </form>

</div>
</div>

<?php include('../dashboard/footer.php'); ?>