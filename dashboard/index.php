<?php require_once "header.php"; ?>
<?php
require_once "../config/db.php";

// Total Vehicles
$vehicles = $pdo->query("SELECT COUNT(*) FROM vehicule")->fetchColumn();

// Total Chauffeurs
$drivers = $pdo->query("SELECT COUNT(*) FROM employe")->fetchColumn();

// Total Missions
$missions = $pdo->query("SELECT COUNT(*) FROM mission")->fetchColumn();

// Active missions today
$active_missions = $pdo->query("
    SELECT COUNT(*) 
    FROM mission 
    WHERE date_sortie <= CURDATE() 
      AND (date_retour IS NULL OR date_retour = '0000-00-00' OR date_retour >= CURDATE())
")->fetchColumn();

// Maintenance tasks
$maintenance = $pdo->query("SELECT COUNT(*) FROM entretien")->fetchColumn();

// Incidents count
$incidents = $pdo->query("SELECT COUNT(*) FROM incident")->fetchColumn();
?>

<?php require_once "sidebar.php"; ?>

<div class="content">

    <div class="dashboard-header">
        <h1>Bienvenue, <?= htmlspecialchars($username) ?> 👋</h1>
        <p>Votre rôle : <strong><?= strtoupper($role) ?></strong></p>
    </div>

    <div class="cards">
        <div class="card">
            <h3><i class="fas fa-car"></i> Véhicules</h3>
            <p><?= $vehicles ?> véhicules enregistrés au total.</p>
        </div>

        <div class="card">
            <h3><i class="fas fa-id-card"></i> Chauffeurs</h3>
            <p><?= $drivers ?> chauffeurs dans le système.</p>
        </div>

        <div class="card">
            <h3><i class="fas fa-clipboard-list"></i> Missions</h3>
            <p><?= $missions ?> missions créées.</p>
        </div>

        <div class="card">
            <h3><i class="fas fa-route"></i> Missions actives</h3>
            <p><?= $active_missions ?> missions en cours aujourd’hui.</p>
        </div>

        <div class="card">
            <h3><i class="fas fa-tools"></i> Maintenance</h3>
            <p><?= $maintenance ?> opérations de maintenance.</p>
        </div>

        <div class="card">
            <h3><i class="fas fa-exclamation-triangle"></i> Incidents</h3>
            <p><?= $incidents ?> incidents signalés.</p>
        </div>

        <?php if ($role === 'admin'): ?>
        <div class="card">
            <h3><i class="fas fa-user-shield"></i> Panneau d'administration</h3>
            <p>Gérer les utilisateurs, les wilayas, les rôles et les paramètres du système.</p>
        </div>
        <?php endif; ?>
    </div>

</div>

<?php require_once "footer.php"; ?>