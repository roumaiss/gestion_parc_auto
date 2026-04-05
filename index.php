<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion | Gestion Parc Automobile</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<div class="login-container">
    <div class="login-box">

        <img src="assets/img/logo.png" class="logo" alt="Logo">

        <h2>Gestion du Parc Automobile</h2>

        <form method="POST" action="auth/login_process.php">
            <input type="text" name="username" placeholder="Nom d'utilisateur" required>
            <input type="password" name="password" placeholder="Mot de passe" required>
            <button type="submit">Se connecter</button>
        </form>

    </div>
</div>

</body>
</html>
