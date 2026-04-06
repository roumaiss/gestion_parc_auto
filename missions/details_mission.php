<?php
include('../config/database.php');
include('../dashboard/header.php');
include('../dashboard/sidebar.php');

if (!isset($_GET['id'])) {
    header("Location: list_missions.php");
    exit;
}

$id = intval($_GET['id']);

$stmt = $pdo->prepare("
    SELECT m.*, v.marque, v.matricule, v.kilometrage,
           e.nom, e.prenom,
           wd.nom_wilaya AS wilaya_depart,
           wa.nom_wilaya AS wilaya_arrivee
    FROM mission m
    JOIN vehicule v  ON m.id_vehicule       = v.id_vehicule
    JOIN employe  e  ON m.id_employe        = e.id_employe
    LEFT JOIN wilaya wd ON m.id_wilaya_depart  = wd.id_wilaya
    LEFT JOIN wilaya wa ON m.id_wilaya_arrivee = wa.id_wilaya
    WHERE m.num_mission = ?
");
$stmt->execute([$id]);
$mission = $stmt->fetch();

if (!$mission) {
    header("Location: list_missions.php");
    exit;
}

$incidents_stmt = $pdo->prepare("SELECT * FROM incident WHERE num_mission = ?");
$incidents_stmt->execute([$id]);
$incidents = $incidents_stmt->fetchAll();

$distance = (!empty($mission['km_retour']) && !empty($mission['km_depart']))
    ? ($mission['km_retour'] - $mission['km_depart']) . ' km'
    : '---';

$statut = (empty($mission['date_retour']) || $mission['date_retour'] == '0000-00-00')
    ? '<span class="badge active">En cours</span>'
    : '<span class="badge done">Terminée</span>';
?>

<div class="content">
<div class="form-container" style="max-width:620px;">

    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;">
        <h2 style="margin:0;">Détails Mission #<?= $mission['num_mission'] ?></h2>
        <?= $statut ?>
    </div>

    <!-- Info grid -->
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px 24px;margin-bottom:24px;">

        <div>
            <div style="font-size:11px;text-transform:uppercase;color:#888;font-weight:600;margin-bottom:2px;">Chauffeur</div>
            <div style="font-weight:600;"><?= htmlspecialchars($mission['nom'].' '.$mission['prenom']) ?></div>
        </div>

        <div>
            <div style="font-size:11px;text-transform:uppercase;color:#888;font-weight:600;margin-bottom:2px;">Véhicule</div>
            <div style="font-weight:600;"><?= htmlspecialchars($mission['marque']) ?> <span style="color:#888;">(<?= htmlspecialchars($mission['matricule']) ?>)</span></div>
        </div>

        <div>
            <div style="font-size:11px;text-transform:uppercase;color:#888;font-weight:600;margin-bottom:2px;">Date départ</div>
            <div><?= $mission['date_sortie'] ?></div>
        </div>

        <div>
            <div style="font-size:11px;text-transform:uppercase;color:#888;font-weight:600;margin-bottom:2px;">Date retour</div>
            <div><?= $mission['date_retour'] ?: '---' ?></div>
        </div>

        <div>
            <div style="font-size:11px;text-transform:uppercase;color:#888;font-weight:600;margin-bottom:2px;">Wilaya départ</div>
            <div><?= htmlspecialchars($mission['wilaya_depart'] ?? '---') ?></div>
        </div>

        <div>
            <div style="font-size:11px;text-transform:uppercase;color:#888;font-weight:600;margin-bottom:2px;">Wilaya arrivée</div>
            <div><?= htmlspecialchars($mission['wilaya_arrivee'] ?? '---') ?></div>
        </div>

        <div>
            <div style="font-size:11px;text-transform:uppercase;color:#888;font-weight:600;margin-bottom:2px;">Km départ</div>
            <div><?= number_format($mission['km_depart']) ?> km</div>
        </div>

        <div>
            <div style="font-size:11px;text-transform:uppercase;color:#888;font-weight:600;margin-bottom:2px;">Km retour</div>
            <div><?= $mission['km_retour'] ? number_format($mission['km_retour']).' km' : '---' ?></div>
        </div>

        <div>
            <div style="font-size:11px;text-transform:uppercase;color:#888;font-weight:600;margin-bottom:2px;">Distance parcourue</div>
            <div style="font-weight:600;color:#1b5e42;"><?= $distance ?></div>
        </div>

        <?php if (!empty($mission['objet_mission'])): ?>
        <div>
            <div style="font-size:11px;text-transform:uppercase;color:#888;font-weight:600;margin-bottom:2px;">Objet</div>
            <div><?= htmlspecialchars($mission['objet_mission']) ?></div>
        </div>
        <?php endif; ?>

    </div>

    <hr style="border:none;border-top:1px solid #e5e7eb;margin-bottom:20px;">

    <!-- Incidents -->
    <h3 style="color:#1b5e42;margin-bottom:12px;font-size:15px;">Incidents liés</h3>

    <?php if (count($incidents) > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Description</th>
                    <th>Gravité</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($incidents as $i): ?>
                <?php $bc = match(strtolower($i['gravite'])) { 'grave' => 'danger', 'moyenne' => 'warning', default => 'done' }; ?>
                <tr>
                    <td><?= $i['date_incident'] ?></td>
                    <td><?= htmlspecialchars($i['description']) ?></td>
                    <td><span class="badge <?= $bc ?>"><?= htmlspecialchars($i['gravite']) ?></span></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p style="color:#888;font-size:14px;">Aucun incident pour cette mission.</p>
    <?php endif; ?>

    <div style="margin-top:24px;">
        <a href="list_missions.php" class="btn-edit" style="text-decoration:none;">← Retour à la liste</a>
    </div>

</div>
</div>

<?php include('../dashboard/footer.php'); ?>
