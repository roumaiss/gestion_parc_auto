<div class="sidebar">

    <div class="logo-section">
        <img src="../Image/logo.png" class="sidebar-logo">
        <h2 class="menu-text">Mobilis flotte</h2>
    </div>

    <div class="user-info">
        👋 <strong class="menu-text"><?= htmlspecialchars($_SESSION['username']) ?></strong>
        <span class="role-badge menu-text"><?= strtoupper($_SESSION['role']) ?></span>
    </div>

   <ul>

    <!-- ACCUEIL -->
    <li>
        <a href="../dashboard/index.php">
            <i class="fas fa-home"></i>
            <span class="menu-text"> Accueil</span>
        </a>
    </li>

    <!-- VÉHICULES -->
    <li>
        <a href="#">
            <i class="fas fa-car"></i>
            <span class="menu-text"> Véhicules</span>
        </a>
    </li>
    <li class="submenu">
        <a href="../vehicules/add.php">
            <span class="menu-text">🚗 Ajouter un véhicule</span>
        </a>
    </li>
    <li class="submenu">
        <a href="../vehicules/list.php">
            <span class="menu-text">📋 Liste des véhicules</span>
        </a>
    </li>

    <!-- CHAUFFEURS -->
    <li>
        <a href="#">
            <i class="fas fa-id-card"></i>
            <span class="menu-text"> Chauffeurs</span>
        </a>
    </li>
    <li class="submenu">
        <a href="../vehicules/add_chauffeur.php">
            <span class="menu-text">👨‍✈️ Ajouter un chauffeur</span>
        </a>
    </li>
    <li class="submenu">
        <a href="../vehicules/list_chauffeurs.php">
            <span class="menu-text">📋 Liste des chauffeurs</span>
        </a>
    </li>

    <!-- MISSIONS -->
    <li>
        <a href="../missions/list_missions.php">
            <i class="fas fa-briefcase"></i>
            <span class="menu-text"> Missions</span>
        </a>
    </li>

    <!-- MAINTENANCE -->
    <li>
        <a href="../maintenance/list_maintenance.php">
            <i class="fas fa-tools"></i>
            <span class="menu-text"> Maintenance</span>
        </a>
    </li>

    <!-- INCIDENTS -->
    <li>
        <a href="#">
            <i class="fas fa-exclamation-triangle"></i>
            <span class="menu-text"> Incidents</span>
        </a>
    </li>
    <li class="submenu">
        <a href="../incidents/add_incident.php">
            <span class="menu-text">➕ Ajouter un incident</span>
        </a>
    </li>
    <li class="submenu">
        <a href="../incidents/list_incidents.php">
            <span class="menu-text">📋 Liste des incidents</span>
        </a>
    </li>

    <!-- CARBURANT -->
    <li>
        <a href="#">
            <i class="fas fa-gas-pump"></i>
            <span class="menu-text"> Carburant</span>
        </a>
    </li>
    <li class="submenu">
        <a href="../carburant/add_carburant.php">
            <span class="menu-text">⛽ Ajouter du carburant</span>
        </a>
    </li>
    <li class="submenu">
        <a href="../carburant/list_carburant.php">
            <span class="menu-text">📋 Liste du carburant</span>
        </a>
    </li>

    <!-- ADMIN -->
    <?php if ($_SESSION['role'] === 'admin'): ?>
        <li>
            <a href="#">
                <i class="fas fa-cog"></i>
                <span class="menu-text"> Administration</span>
            </a>
        </li>

        <li class="submenu">
            <a href="../admin/users.php">
                <span class="menu-text">👥 Utilisateurs</span>
            </a>
        </li>

        <li class="submenu">
            <a href="../admin/wilayas.php">
                <span class="menu-text">📍 Wilayas</span>
            </a>
        </li>

        <li class="submenu">
            <a href="../admin/roles.php">
                <span class="menu-text">🛡 Rôles</span>
            </a>
        </li>
    <?php endif; ?>

    <!-- DÉCONNEXION -->
    <li>
        <a href="/gestion_parc_auto/logout.php" 
           class="logout"
           onclick="return confirm('Se déconnecter ?')">
            <i class="fas fa-sign-out-alt"></i>
            <span class="menu-text"> Déconnexion</span>
        </a>
    </li>

</ul>
</div>