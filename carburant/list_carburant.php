<?php
include('../config/database.php');
include('../dashboard/header.php');
include('../dashboard/sidebar.php');

$stmt = $pdo->query("
SELECT cd.*, c.date_delivrance, e.nom, e.prenom
FROM carburant_detail cd
JOIN carburant c ON cd.num_carburant = c.num_carburant
LEFT JOIN employe e ON c.id_employe = e.id_employe
ORDER BY cd.num_detail DESC
");

$carburants = $stmt->fetchAll();
?>

<div class="content">

    <div class="table-container">

        <!-- ✅ MESSAGES -->
        <?php if(isset($_GET['success'])): ?>

            <?php if($_GET['success'] == 'added'): ?>
                <div class="msg success">Carburant ajouté avec succès ✅</div>

            <?php elseif($_GET['success'] == 'updated'): ?>
                <div class="msg success">Carburant modifié avec succès ✏️</div>

            <?php elseif($_GET['success'] == 'deleted'): ?>
                <div class="msg success">Carburant supprimé 🗑</div>

            <?php endif; ?>

        <?php endif; ?>

        <?php if(isset($_GET['error'])): ?>
            <div class="msg error">Une erreur est survenue ❌</div>
        <?php endif; ?>

        <!-- HEADER -->
        <div class="header">
            <h2>⛽ Carburant List</h2>
            <a href="add_carburant.php" class="btn-add">+ Ajouter Carburant</a>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Référence</th>
                    <th>Montant</th>
                    <th>Date</th>
                    <th>Employé</th>
                    <th>Actions</th>
                </tr>
            </thead>

            <tbody>
                <?php if(count($carburants) > 0): ?>
                    <?php foreach($carburants as $c): ?>
                        <tr>

                            <td><?= htmlspecialchars($c['reference']) ?></td>

                            <td><?= number_format($c['montant'], 2) ?> DZD</td>

                            <td><?= $c['date_delivrance'] ?></td>

                            <td>
                                <?= $c['nom'] ?> <?= $c['prenom'] ?>
                            </td>

                            <td>
                                <a href="edit_carburant.php?id=<?= $c['num_detail'] ?>" class="btn-edit">
                                    modifier
                                </a>

                                <a href="delete_carburant.php?id=<?= $c['num_detail'] ?>"
                                   class="btn-delete"
                                   onclick="return confirm('Delete carburant?')">
                                   supprimer
                                </a>
                            </td>

                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5">Aucun carburant trouvé</td>
                    </tr>
                <?php endif; ?>
            </tbody>

        </table>

    </div>

</div>

<?php include('../dashboard/footer.php'); ?>