<?php
session_start();

// 🔒 PROTECT
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../dashboard/index.php");
    exit;
}

include('../config/database.php');
include('../dashboard/header.php');
include('../dashboard/sidebar.php');

// ADD USER
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role'];

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

        <h2>➕ Ajouter un nouvel utilisateur</h2>

        <form method="POST">

            <label>Nom</label>
            <input type="text" name="nom" required>

            <label>Prénom</label>
            <input type="text" name="prenom" required>

            <label>Username</label>
            <input type="text" name="username" required>

            <label>Password</label>
            <input type="password" name="password" required>

            <label>Role</label>
            <select name="role">
                <option value="user">User</option>
                <option value="admin">Admin</option>
            </select>

            <button type="submit" class="btn-submit">Create User</button>

        </form>

    </div>

</div>

<?php include('../dashboard/footer.php'); ?>