<?php
session_start();

if (!isset($_SESSION["id_app_user"])) {
    header("Location: login.php");
    exit;
}
?>

<h1>Bienvenue <?php echo $_SESSION["username"]; ?></h1>
<p>Rôle : <?php echo $_SESSION["role"]; ?></p>

<a href="logout.php">Déconnexion</a>
