<?php
require_once "../config/db.php";
include "header.php";
include "sidebar.php";

$vehicles        = $pdo->query("SELECT COUNT(*) FROM vehicule")->fetchColumn();
$drivers         = $pdo->query("SELECT COUNT(*) FROM employe")->fetchColumn();
$missions        = $pdo->query("SELECT COUNT(*) FROM mission")->fetchColumn();
$active_missions = $pdo->query("
    SELECT COUNT(*) FROM mission
    WHERE date_sortie <= CURDATE()
      AND (date_retour IS NULL OR date_retour = '0000-00-00' OR date_retour >= CURDATE())
")->fetchColumn();
$maintenance     = $pdo->query("SELECT COUNT(*) FROM entretien")->fetchColumn();
$incidents       = $pdo->query("SELECT COUNT(*) FROM incident")->fetchColumn();
?>

<div class="content">

    <div class="dashboard-header" style="margin-bottom:28px;">
        <h1 style="font-size:24px;font-weight:700;margin-bottom:4px;">
            Bienvenue, <?= htmlspecialchars($username) ?> 👋
        </h1>
        <p style="font-size:15px;color:#555;">
            Votre rôle : <strong><?= strtoupper($role) ?></strong>
        </p>
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
            <p><?= $active_missions ?> missions en cours aujourd'hui.</p>
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
            <h3><i class="fas fa-user-shield"></i> Administration</h3>
            <p>Gérer les utilisateurs, les wilayas, les rôles et les paramètres du système.</p>
        </div>
        <?php endif; ?>
    </div>

</div>

<?php include "footer.php"; ?>
