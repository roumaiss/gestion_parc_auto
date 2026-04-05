<div class="sidebar">
    <h2>Mobilis Fleet</h2>

    <a href="#">🏠 Dashboard</a>

    <div class="menu-title">Chauffeurs</div>
    <a href="#">➕ Ajouter Chauffeur</a>
    <a href="#">📋 Liste des Chauffeurs</a>

    <div class="menu-title">Voitures</div>
    <a href="#">➕ Ajouter Voiture</a>
    <a href="#">📋 Liste des Voitures</a>

    <div class="menu-title">Missions</div>
    <a href="#">➕ Ajouter Mission</a>
    <a href="#">📋 Liste des Missions</a>
    <a href="#">⚠️ Ajouter Incident</a>

    <div class="menu-title">Gestion Parc</div>
    <a href="#">🛠️ Entretien</a>
    <a href="#">📊 Suivi</a>
    <a href="#">⛽ Carburant</a>

    <?php if ($_SESSION['role'] === 'admin'): ?>
        <div class="menu-title">Administration</div>
        <a href="#">➕ Ajouter Wilaya</a>
        <a href="#">➕ Ajouter User</a>
    <?php endif; ?>

    <a href="logout.php" class="logout">🚪 Déconnexion</a>
</div>
