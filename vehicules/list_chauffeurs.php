<?php
include "../dashboard/header.php";
include "../dashboard/sidebar.php";
include "../config/database.php"; // ✅ correct

// Fetch all employees
$stmt = $pdo->query("SELECT * FROM employe ORDER BY id_employe DESC");
$employees = $stmt->fetchAll();
?>

<div class="content">
<div class="table-container">

    <h2>📋 Chauffeurs / Employés</h2>

    <table class="styled-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nom</th>
                <th>Fonction</th>
                <th>Actions</th>
            </tr>
        </thead>

        <tbody>
            <?php foreach ($employees as $employee): ?>
            <tr>

                <td><?= $employee['id_employe'] ?></td>

                <td>
                    <?= htmlspecialchars($employee['nom']) ?>
                    <?= htmlspecialchars($employee['prenom']) ?>
                </td>

                <td><?= htmlspecialchars($employee['fonction']) ?></td>

                <td>
                    <a href="edit_chauffeur.php?id=<?= $employee['id_employe'] ?>" 
                       class="edit-btn">Edit</a>

                    <a href="delete_chauffeur.php?id=<?= $employee['id_employe'] ?>" 
                       class="delete-btn"
                       onclick="return confirm('Delete this employee?')">
                       Delete
                    </a>
                </td>

            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

</div>
</div>

<?php include "../dashboard/footer.php"; ?>