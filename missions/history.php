<?php
include('../config/database.php');

$id = $_GET['id'];

$stmt = $pdo->prepare("
SELECT * FROM entretien 
WHERE id_vehicule = ?
ORDER BY date_entretien DESC
");

$stmt->execute([$id]);
$data = $stmt->fetchAll();
?>

<h2>Historique véhicule</h2>

<table>
<tr>
    <th>Date</th>
    <th>Type</th>
    <th>Coût</th>
    <th>KM</th>
</tr>

<?php foreach($data as $h): ?>
<tr>
    <td><?= $h['date_entretien'] ?></td>
    <td><?= $h['type'] ?></td>
    <td><?= $h['cout'] ?> DZD</td>
    <td><?= $h['kilometrage'] ?> km</td>
</tr>
<?php endforeach; ?>
</table>