<?php
include('../config/database.php');
include('../dashboard/header.php');
include('../dashboard/sidebar.php');

$id = $_GET['id'];

// GET chauffeur
$stmt = $pdo->prepare("SELECT * FROM employe WHERE id_employe = ?");
$stmt->execute([$id]);
$chauffeur = $stmt->fetch();

// UPDATE
if($_SERVER['REQUEST_METHOD'] == 'POST'){

    $nom = $_POST['nom'];
$prenom = $_POST['prenom'];

if (!preg_match('/^[A-Za-zÀ-ÿ\s]+$/', $nom) || !preg_match('/^[A-Za-zÀ-ÿ\s]+$/', $prenom)) {
    header("Location: edit_chauffeur.php?id=" . $id . "&error=invalid_name");
    exit;
}
    $fonction = $_POST['fonction'];

    $update = $pdo->prepare("
        UPDATE employe 
        SET nom=?, prenom=?, fonction=? 
        WHERE id_employe=?
    ");

    $update->execute([$nom, $prenom, $fonction, $id]);

    header("Location: list_chauffeurs.php");
    exit;
}
?>

<div class="content">
<div class="mission-container">

    <div class="header">
        <h2>Modifier Chauffeur</h2>
    </div>

    <form method="POST" class="mission-form">

        <label>Nom</label>
        <input type="text" name="nom" value="<?= $chauffeur['nom'] ?>" required>

        <label>Prénom</label>
        <input type="text" name="prenom" value="<?= $chauffeur['prenom'] ?>" required>

        <label>Fonction</label>
        <select name="fonction">
            <option value="Chauffeur" <?= $chauffeur['fonction']=='Chauffeur'?'selected':'' ?>>Chauffeur</option>
            <option value="Employé" <?= $chauffeur['fonction']=='Employé'?'selected':'' ?>>Employé</option>
        </select>

        <button class="btn-add">Mettre à jour</button>

    </form>

</div>
</div>

<?php include('../dashboard/footer.php'); ?>