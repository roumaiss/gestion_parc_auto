<aside class="sidebar" id="sidebar">

    <div class="sidebar-user">
        <div class="sidebar-avatar"><i class="fas fa-user"></i></div>
        <div class="sidebar-user-text">
            <div class="sidebar-username"><?= htmlspecialchars($_SESSION['username']) ?></div>
            <span class="sidebar-role"><?= strtoupper($_SESSION['role']) ?></span>
        </div>
    </div>

    <nav class="sidebar-nav">

        <div class="nav-label">Navigation</div>

        <a href="/gestion_parc_auto/dashboard/index.php" class="sidebar-link">
            <i class="fas fa-home"></i>
            <span class="link-text">Accueil</span>
        </a>

        <!-- Véhicules -->
        <a href="#" class="sidebar-link">
            <i class="fas fa-car"></i>
            <span class="link-text">Véhicules</span>
        </a>
        <div class="sidebar-sub">
            <a href="/gestion_parc_auto/vehicules/add.php" class="sidebar-link">
                <i class="fas fa-plus-circle"></i>
                <span class="link-text">Ajouter un véhicule</span>
            </a>
            <a href="/gestion_parc_auto/vehicules/list.php" class="sidebar-link">
                <i class="fas fa-list"></i>
                <span class="link-text">Liste des véhicules</span>
            </a>
        </div>

        <!-- Chauffeurs -->
        <a href="#" class="sidebar-link">
            <i class="fas fa-id-card"></i>
            <span class="link-text">Chauffeurs</span>
        </a>
        <div class="sidebar-sub">
            <a href="/gestion_parc_auto/vehicules/add_chauffeur.php" class="sidebar-link">
                <i class="fas fa-user-plus"></i>
                <span class="link-text">Ajouter un chauffeur</span>
            </a>
            <a href="/gestion_parc_auto/vehicules/list_chauffeurs.php" class="sidebar-link">
                <i class="fas fa-list"></i>
                <span class="link-text">Liste des chauffeurs</span>
            </a>
        </div>

        <div class="nav-label">Opérations</div>

        <a href="/gestion_parc_auto/missions/list_missions.php" class="sidebar-link">
            <i class="fas fa-briefcase"></i>
            <span class="link-text">Missions</span>
        </a>

        <a href="/gestion_parc_auto/maintenance/list_maintenance.php" class="sidebar-link">
            <i class="fas fa-tools"></i>
            <span class="link-text">Maintenance</span>
        </a>

        <!-- Incidents -->
        <a href="#" class="sidebar-link">
            <i class="fas fa-exclamation-triangle"></i>
            <span class="link-text">Incidents</span>
        </a>
        <div class="sidebar-sub">
            <a href="/gestion_parc_auto/incidents/add_incident.php" class="sidebar-link">
                <i class="fas fa-plus-circle"></i>
                <span class="link-text">Ajouter un incident</span>
            </a>
            <a href="/gestion_parc_auto/incidents/list_incidents.php" class="sidebar-link">
                <i class="fas fa-list"></i>
                <span class="link-text">Liste des incidents</span>
            </a>
        </div>

        <!-- Carburant -->
        <a href="#" class="sidebar-link">
            <i class="fas fa-gas-pump"></i>
            <span class="link-text">Carburant</span>
        </a>
        <div class="sidebar-sub">
            <a href="/gestion_parc_auto/carburant/add_carburant.php" class="sidebar-link">
                <i class="fas fa-plus-circle"></i>
                <span class="link-text">Ajouter carburant</span>
            </a>
            <a href="/gestion_parc_auto/carburant/list_carburant.php" class="sidebar-link">
                <i class="fas fa-list"></i>
                <span class="link-text">Liste carburant</span>
            </a>
        </div>

        <?php if ($_SESSION['role'] === 'admin'): ?>
        <div class="nav-label">Administration</div>
        <a href="/gestion_parc_auto/admin/users.php" class="sidebar-link">
            <i class="fas fa-users"></i>
            <span class="link-text">Utilisateurs</span>
        </a>
        <a href="/gestion_parc_auto/admin/wilayas.php" class="sidebar-link">
            <i class="fas fa-map-marker-alt"></i>
            <span class="link-text">Wilayas</span>
        </a>
        <a href="/gestion_parc_auto/admin/roles.php" class="sidebar-link">
            <i class="fas fa-shield-alt"></i>
            <span class="link-text">Rôles</span>
        </a>
        <?php endif; ?>

    </nav>

    <div class="sidebar-footer">
        <a href="/gestion_parc_auto/logout.php"
           class="sidebar-link logout"
           onclick="return confirm('Se déconnecter ?')">
            <i class="fas fa-sign-out-alt"></i>
            <span class="link-text">Déconnexion</span>
        </a>
    </div>

</aside>
