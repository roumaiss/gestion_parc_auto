<?php
include('../config/database.php');
include('../dashboard/header.php');
include('../dashboard/sidebar.php');

$id = $_GET['id'] ?? null;
if (!$id) {
    header("Location: list_chauffeurs.php");
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM employe WHERE id_employe = ?");
$stmt->execute([$id]);
$chauffeur = $stmt->fetch();

if (!$chauffeur) {
    header("Location: list_chauffeurs.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom     = $_POST['nom'];
    $prenom  = $_POST['prenom'];

    if (!preg_match('/^[A-Za-zÀ-ÿ\s]+$/', $nom) || !preg_match('/^[A-Za-zÀ-ÿ\s]+$/', $prenom)) {
        header("Location: edit_chauffeur.php?id=$id&error=invalid_name");
        exit;
    }

    $fonction = $_POST['fonction'];

    $pdo->prepare("UPDATE employe SET nom=?, prenom=?, fonction=? WHERE id_employe=?")
        ->execute([$nom, $prenom, $fonction, $id]);

    header("Location: list_chauffeurs.php?success=updated");
    exit;
}
?>

<div class="content">
<div class="form-container">

    <h2>Modifier Chauffeur ✏️</h2>

    <?php if(isset($_GET['error']) && $_GET['error'] == 'invalid_name'): ?>
        <div class="msg error">Nom et prénom doivent contenir uniquement des lettres ❌</div>
    <?php endif; ?>

    <form method="POST">

        <div class="input-group">
            <label>Nom</label>
            <input type="text" name="nom"
                   value="<?= htmlspecialchars($chauffeur['nom']) ?>"
                   pattern="[A-Za-zÀ-ÿ\s]+"
                   oninput="this.value = this.value.replace(/[^A-Za-zÀ-ÿ\s]/g, '')"
                   required>
        </div>

        <div class="input-group">
            <label>Prénom</label>
            <input type="text" name="prenom"
                   value="<?= htmlspecialchars($chauffeur['prenom']) ?>"
                   pattern="[A-Za-zÀ-ÿ\s]+"
                   oninput="this.value = this.value.replace(/[^A-Za-zÀ-ÿ\s]/g, '')"
                   required>
        </div>

        <div class="input-group">
            <label>Fonction</label>
            <select name="fonction">
                <option value="Chauffeur" <?= $chauffeur['fonction']=='Chauffeur'?'selected':'' ?>>Chauffeur</option>
                <option value="Employé"   <?= $chauffeur['fonction']=='Employé'  ?'selected':'' ?>>Employé</option>
            </select>
        </div>

        <button type="submit" class="submit-btn">Enregistrer les modifications</button>

    </form>

</div>
</div>

<?php include('../dashboard/footer.php'); ?>
