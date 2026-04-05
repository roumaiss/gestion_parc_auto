<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../dashboard/index.php");
    exit;
}

include('../config/database.php');
include('../dashboard/header.php');
include('../dashboard/sidebar.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom      = $_POST['nom'];
    $prenom   = $_POST['prenom'];
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role     = $_POST['role'];

    $stmt = $pdo->prepare("
        INSERT INTO app_user (nom, prenom, username, password, role)
        VALUES (?, ?, ?, ?, ?)
    ");
    $stmt->execute([$nom, $prenom, $username, $password, $role]);

    header("Location: users.php?success=created");
    exit;
}
?>

<div class="content">
<div class="form-container">

    <h2>Ajouter un Utilisateur</h2>

    <form method="POST">

        <div class="input-group">
            <label>Nom</label>
            <input type="text" name="nom" required>
        </div>

        <div class="input-group">
            <label>Prénom</label>
            <input type="text" name="prenom" required>
        </div>

        <div class="input-group">
            <label>Username</label>
            <input type="text" name="username" required>
        </div>

        <div class="input-group">
            <label>Mot de passe</label>
            <input type="password" name="password" required>
        </div>

        <div class="input-group">
            <label>Rôle</label>
            <select name="role">
                <option value="user">User</option>
                <option value="admin">Admin</option>
            </select>
        </div>

        <button type="submit" class="submit-btn">Créer Utilisateur</button>

    </form>

</div>
</div>

<?php include('../dashboard/footer.php'); ?>
