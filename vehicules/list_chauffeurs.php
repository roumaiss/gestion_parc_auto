<?php
include "../dashboard/header.php";
include "../dashboard/sidebar.php";
include "../config/database.php";

$stmt = $pdo->query("SELECT * FROM employe ORDER BY id_employe DESC");
$employees = $stmt->fetchAll();
?>

<div class="content">

    <div class="page-header">
        <h2>Liste des Chauffeurs</h2>
    </div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nom</th>
                <th>Prénom</th>
                <th>Fonction</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($employees as $e): ?>
            <tr>
                <td><?= $e['id_employe'] ?></td>
                <td><?= htmlspecialchars($e['nom']) ?></td>
                <td><?= htmlspecialchars($e['prenom']) ?></td>
                <td><span class="badge <?= $e['fonction'] === 'Chauffeur' ? 'done' : 'warning' ?>"><?= htmlspecialchars($e['fonction']) ?></span></td>
                <td>
                    <a href="edit_chauffeur.php?id=<?= $e['id_employe'] ?>" class="btn-edit">✏ Modifier</a>
                    <a href="delete_chauffeur.php?id=<?= $e['id_employe'] ?>"
                       class="btn-delete"
                       onclick="return confirm('Supprimer cet employé ?')">🗑 Supprimer</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

</div>

<?php include "../dashboard/footer.php"; ?>
