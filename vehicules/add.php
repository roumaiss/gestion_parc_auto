<?php
// DB first, before any HTML output
require_once "../config/db.php";
include "../dashboard/header.php";
include "../dashboard/sidebar.php";

$success = "";
$error   = "";
$debug   = [];

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $debug[] = "POST received";
    $debug[] = "POST data: " . json_encode($_POST);

    $matricule   = trim($_POST['matricule']   ?? '');
    $marque      = trim($_POST["marque"]      ?? '');
    $modele      = trim($_POST["modele"]      ?? '');
    $kilometrage = trim($_POST["kilometrage"] ?? '');
    $date_achat  = trim($_POST["date_achat"]  ?? '');
    $etat        = trim($_POST["etat"]        ?? '');
    $created_by  = $_SESSION["user_id"] ?? null;

    $debug[] = "created_by (user_id from session): " . var_export($created_by, true);

    // Validation
    if (empty($matricule) || empty($marque) || empty($modele) || empty($kilometrage) || empty($date_achat)) {
        $error = "❌ Tous les champs sont obligatoires.";
        $debug[] = "Validation failed: empty fields";
    } elseif (!preg_match('/^[0-9\-]+$/', $matricule)) {
        $error = "❌ Matricule doit contenir uniquement des chiffres et '-'.";
        $debug[] = "Validation failed: invalid matricule";
    } elseif ($created_by === null) {
        $error = "❌ Session expirée. Veuillez vous reconnecter.";
        $debug[] = "Validation failed: no user_id in session";
    } else {
        try {
            $debug[] = "Attempting DB insert...";

            $sql = $pdo->prepare("
                INSERT INTO vehicule (matricule, marque, modele, kilometrage, date_achat, etat, created_by)
                VALUES (?, ?, ?, ?, ?, ?, ?)
            ");

            $result = $sql->execute([$matricule, $marque, $modele, $kilometrage, $date_achat, $etat, $created_by]);

            $debug[] = "Execute result: " . var_export($result, true);
            $debug[] = "Rows affected: " . $sql->rowCount();

            if ($result && $sql->rowCount() > 0) {
                $success = "✅ Véhicule ajouté avec succès!";
                $debug[] = "SUCCESS — vehicle inserted";
            } else {
                $error = "❌ Aucune ligne insérée. Vérifiez les données.";
                $debug[] = "FAILED — no rows inserted";
            }

        } catch (PDOException $e) {
            $error = "❌ Erreur base de données: " . $e->getMessage();
            $debug[] = "PDOException: " . $e->getMessage();
            $debug[] = "SQL state: " . $e->getCode();
        }
    }
}
?>

<div class="content">
<div class="form-container">

    <h2>Ajouter Véhicule 🚗</h2>

    <?php if (!empty($success)): ?>
        <div class="msg success"><?= $success ?></div>
    <?php endif; ?>
    <?php if (!empty($error)): ?>
        <div class="msg error"><?= $error ?></div>
    <?php endif; ?>

    <form method="POST" id="addVehicleForm">

        <div class="input-group">
            <label>Matricule</label>
            <input type="text" name="matricule"
                   oninput="this.value = this.value.replace(/[^0-9-]/g, '')"
                   title="Uniquement des chiffres et '-'"
                   required>
        </div>

        <div class="input-group">
            <label>Marque</label>
            <input type="text" name="marque" required>
        </div>

        <div class="input-group">
            <label>Modèle</label>
            <input type="text" name="modele" required>
        </div>

        <div class="input-group">
            <label>Kilométrage</label>
            <input type="number" name="kilometrage" min="0" required>
        </div>

        <div class="input-group">
            <label>Date d'achat</label>
            <input type="date" name="date_achat" required>
        </div>

        <div class="input-group">
            <label>État</label>
            <select name="etat" required>
                <option value="Disponible">Disponible</option>
                <option value="En mission">En mission</option>
                <option value="Maintenance">Maintenance</option>
                <option value="En panne">En panne</option>
                <option value="Reserve">Reserve</option>
            </select>
        </div>

        <button type="submit" class="submit-btn">Ajouter Véhicule</button>
    </form>

</div>
</div>

<!-- DEBUG CONSOLE LOG -->
<script>
const debugLogs = <?= json_encode($debug) ?>;
if (debugLogs.length > 0) {
    console.group('%c[Add Véhicule — Debug]', 'color:#2e8b57;font-weight:bold;font-size:13px;');
    debugLogs.forEach((line, i) => {
        if (line.includes('SUCCESS'))       console.log('%c' + line, 'color:green;font-weight:bold;');
        else if (line.includes('FAILED') || line.includes('Exception') || line.includes('failed'))
                                            console.error(line);
        else                                console.log('%c[' + i + '] ' + line, 'color:#555;');
    });
    console.groupEnd();
}

// Form submit log
document.getElementById('addVehicleForm').addEventListener('submit', function(e) {
    const data = {};
    new FormData(this).forEach((v, k) => data[k] = v);
    console.group('%c[Add Véhicule — Form Submit]', 'color:#1565c0;font-weight:bold;font-size:13px;');
    console.log('Fields:', data);
    console.groupEnd();
});
</script>

<?php include "../dashboard/footer.php"; ?>
