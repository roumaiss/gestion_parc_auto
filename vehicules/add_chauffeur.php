<?php
// HEADER + SIDEBAR
include "../dashboard/header.php";
include "../dashboard/sidebar.php";

// DB connection
require_once "../config/db.php";

// Form handler
$success = "";
$error   = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nom = $_POST['nom'];
$prenom = $_POST['prenom'];

if (!preg_match('/^[A-Za-zÀ-ÿ\s]+$/', $nom) || !preg_match('/^[A-Za-zÀ-ÿ\s]+$/', $prenom)) {
    header("Location: add_chauffeur.php?error=invalid_name");
    exit;
}
    $fonction   = $_POST["fonction"];
    $created_by = $_SESSION["user_id"];

    $sql = $pdo->prepare("
        INSERT INTO employe
        (nom, prenom, fonction, created_by)
        VALUES (?, ?, ?, ?)
    ");

    if ($sql->execute([$nom, $prenom, $fonction, $created_by])) {
        $success = "Chauffeur added successfully!";
    } else {
        $error = "❌ Error: Could not add chauffeur.";
    }
}

?>

<link rel="stylesheet" href="../assets/forms.css">
<?php if(isset($_GET['error']) && $_GET['error'] == 'invalid_name'): ?>
    <div class="msg error">
        Nom et prénom doivent contenir uniquement des lettres ❌
    </div>
<?php endif; ?>
<div class="form-container">
    <h2>Ajouter Chauffeur 👨‍✈️</h2>
    
    <?php if (!empty($success)): ?>
        <div class="msg success"><?= $success ?></div>
    <?php endif; ?>

    <?php if (!empty($error)): ?>
        <div class="msg error"><?= $error ?></div>
    <?php endif; ?>

    <form method="POST">
        <div class="input-group">
            <label>Nom</label>
            <input 
    type="text" 
    name="nom"
    pattern="[A-Za-zÀ-ÿ\s]+"
    oninput="this.value = this.value.replace(/[^A-Za-zÀ-ÿ\s]/g, '')"
    title="Only letters allowed"
    required
>
        </div>

        <div class="input-group">
            <label>Prénom</label>
            <input 
    type="text" 
    name="prenom"
    pattern="[A-Za-zÀ-ÿ\s]+"
    oninput="this.value = this.value.replace(/[^A-Za-zÀ-ÿ\s]/g, '')"
    title="Only letters allowed"
    required
>
        </div>

        <div class="input-group">
            <label>Fonction</label>
            <select name="fonction" required>
                <option value="Chauffeur">Chauffeur</option>
                <option value="Employé">Employé</option>
            </select>
        </div>

        <button type="submit" class="submit-btn">Ajouter Chauffeur</button>
    </form>
</div>

<?php include "../dashboard/footer.php"; ?>
</body>
</html>
