<?php
include('../config/database.php');
include('../dashboard/header.php');
include('../dashboard/sidebar.php');

if (!isset($_GET['id'])) {
    header("Location: list_missions.php");
    exit;
}

$id = $_GET['id'];

/* GET FULL DATA */
$stmt = $pdo->prepare("
SELECT 
    m.*,
    v.marque,
    v.matricule,
    v.kilometrage,
    e.nom,
    e.prenom,
    wd.nom_wilaya AS wilaya_depart,
    wa.nom_wilaya AS wilaya_arrivee
FROM mission m
JOIN vehicule v ON m.id_vehicule = v.id_vehicule
JOIN employe e ON m.id_employe = e.id_employe
LEFT JOIN wilaya wd ON m.id_wilaya_depart = wd.id_wilaya
LEFT JOIN wilaya wa ON m.id_wilaya_arrivee = wa.id_wilaya
WHERE m.num_mission = ?
");

$stmt->execute([$id]);
$mission = $stmt->fetch();

if (!$mission) {
    echo "Mission not found";
    exit;
}

/* GET INCIDENTS */
$stmt = $pdo->prepare("
SELECT * FROM incident WHERE num_mission = ?
");
$stmt->execute([$id]);
$incidents = $stmt->fetchAll();

/* DISTANCE */
$distance = null;
if (!empty($mission['km_retour'])) {
    $distance = $mission['km_retour'] - $mission['km_depart'];
}
?>

<div class="content">
<div class="form-container">

    <h2>📄 Mission Details</h2>

    <p><strong>N° Mission:</strong> <?= $mission['num_mission'] ?></p>

    <p><strong>Chauffeur:</strong> <?= $mission['nom'] ?> <?= $mission['prenom'] ?></p>

    <p><strong>Véhicule:</strong> <?= $mission['marque'] ?> (<?= $mission['matricule'] ?>)</p>

    <p><strong>Date sortie:</strong> <?= $mission['date_sortie'] ?></p>
    <p><strong>Date retour:</strong> <?= $mission['date_retour'] ?? '---' ?></p>

    <p><strong>Wilaya départ:</strong> <?= $mission['wilaya_depart'] ?? '---' ?></p>
    <p><strong>Wilaya arrivée:</strong> <?= $mission['wilaya_arrivee'] ?? '---' ?></p>

    <p><strong>Km départ:</strong> <?= $mission['km_depart'] ?></p>
    <p><strong>Km retour:</strong> <?= $mission['km_retour'] ?? '---' ?></p>

    <p><strong>Distance:</strong> 
        <?= $distance !== null ? $distance . ' km' : '---' ?>
    </p>

    <hr>

    <h3>🚨 Incidents</h3>

    <?php if(count($incidents) > 0): ?>
        <ul>
            <?php foreach($incidents as $i): ?>
                <li>
                    <?= $i['date_incident'] ?> - 
                    <?= $i['description'] ?> 
                    (<?= $i['gravite'] ?>)
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>No incidents</p>
    <?php endif; ?>

</div>
</div>

<?php include('../dashboard/footer.php'); ?>