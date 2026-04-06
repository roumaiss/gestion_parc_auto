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
        <a href="#" class="sidebar-link logout" onclick="openLogoutModal()">
            <i class="fas fa-sign-out-alt"></i>
            <span class="link-text">Déconnexion</span>
        </a>
    </div>

<!-- LOGOUT MODAL -->
<div id="logoutModal" style="
    display:none; position:fixed; inset:0; z-index:9999;
    background:rgba(0,0,0,0.5);
    align-items:center; justify-content:center;
">
    <div style="
        background:var(--card-bg,#fff);
        border-radius:14px;
        padding:32px 28px;
        width:100%; max-width:380px;
        box-shadow:0 20px 60px rgba(0,0,0,0.25);
        text-align:center;
        font-family:'Segoe UI',sans-serif;
    ">
        <div style="
            width:60px; height:60px; border-radius:50%;
            background:#fef2f2; color:#ef4444;
            display:flex; align-items:center; justify-content:center;
            font-size:26px; margin:0 auto 16px;
        ">
            <i class="fas fa-sign-out-alt"></i>
        </div>
        <h3 style="margin:0 0 8px;font-size:18px;color:var(--text,#222);">Déconnexion</h3>
        <p style="margin:0 0 24px;color:#888;font-size:14px;">Êtes-vous sûr de vouloir vous déconnecter ?</p>
        <div style="display:flex;gap:12px;justify-content:center;">
            <button onclick="closeLogoutModal()" style="
                padding:10px 24px; border-radius:8px;
                border:2px solid #dcdcdc; background:transparent;
                color:var(--text,#222); font-size:14px; cursor:pointer;
                font-weight:600; transition:0.2s;
            " onmouseover="this.style.background='#f3f4f6'" onmouseout="this.style.background='transparent'">
                Annuler
            </button>
            <a href="/gestion_parc_auto/logout.php" style="
                padding:10px 24px; border-radius:8px;
                background:#ef4444; color:white;
                font-size:14px; font-weight:600;
                text-decoration:none; display:inline-block;
                border:2px solid #ef4444; transition:0.2s;
            " onmouseover="this.style.background='#dc2626'" onmouseout="this.style.background='#ef4444'">
                Se déconnecter
            </a>
        </div>
    </div>
</div>

<script>
function openLogoutModal()  { document.getElementById('logoutModal').style.display = 'flex'; }
function closeLogoutModal() { document.getElementById('logoutModal').style.display = 'none'; }
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeLogoutModal();
});
</script>

</aside>
