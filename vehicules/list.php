<?php
// HEADER + SIDEBAR
include "../dashboard/header.php";
include "../dashboard/sidebar.php";

// DB
require_once "../config/db.php";

// Fetch all vehicles
$stmt = $pdo->query("SELECT * FROM vehicule ORDER BY id_vehicule DESC");
$vehicules = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="page-actions">
    <a href="../dashboard/index.php" class="home-btn">🏠 Home</a>
</div>

<style>
.page-actions {
    margin-left: 260px; 
    margin-top: 20px;
    display: flex;
    justify-content: space-between;
    width: calc(100% - 260px);
    padding: 0 20px;
}

.home-btn {
    background: #1b5e42;
    padding: 10px 18px;
    color: #fff;
    border-radius: 6px;
    text-decoration: none;
    font-weight: bold;
    transition: 0.2s;
}

.home-btn:hover { background: #134a32; }

/* Table Styling */
.table-container {
    margin-left: 260px;
    margin-top: 20px;
    padding: 20px;
}

table {
    width: 100%;
    border-collapse: collapse;
    background: white;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 4px 10px rgba(0,0,0,0.08);
}

table th {
    background: #1b5e42;
    color: white;
    padding: 12px;
    text-align: left;
}

table td {
    padding: 12px;
    border-bottom: 1px solid #ddd;
}

.action-btn {
    padding: 6px 10px;
    border-radius: 5px;
    font-size: 14px;
    text-decoration: none;
    margin-right: 6px;
}

.edit-btn { background: #2196F3; color: white; }
.delete-btn { background: #D32F2F; color: white; }

.dark table { background: #222; color: #eee; }
.dark table th { background: #0d3a2c; }
.dark table td { border-color: #333; }
</style>

<div class="table-container">
    <h2>📋 Vehicles List</h2>

    <!-- ✅ SUCCESS MESSAGE -->
    <?php if (isset($_GET['deleted'])): ?>
        <div style="background:#d4edda;color:#155724;padding:10px;border-radius:6px;margin-bottom:15px;">
            ✅ Vehicle deleted successfully.
        </div>
    <?php endif; ?>

    <!-- ❌ VEHICLE USED -->
    <?php if (isset($_GET['error']) && $_GET['error'] == 'used'): ?>
        <div style="background:#f8d7da;color:#721c24;padding:10px;border-radius:6px;margin-bottom:15px;">
            ❌ Cannot delete this vehicle (already used in missions)
        </div>
    <?php endif; ?>

    <!-- ❌ GENERAL ERROR -->
    <?php if (isset($_GET['error']) && $_GET['error'] == 'delete_failed'): ?>
        <div style="background:#f8d7da;color:#721c24;padding:10px;border-radius:6px;margin-bottom:15px;">
            ❌ Error while deleting the vehicle.
        </div>
    <?php endif; ?>

    <table>
        <tr>
            <th>ID</th>
            <th>Matricule</th>
            <th>Marque</th>
            <th>Modèle</th>
            <th>Kilométrage</th>
            <th>Date achat</th>
            <th>État</th>
            <th>Actions</th>
        </tr>

        <?php foreach ($vehicules as $v): ?>
        <tr>
            <td><?= $v['id_vehicule'] ?></td>
            <td><?= htmlspecialchars($v['matricule']) ?></td>
            <td><?= htmlspecialchars($v['marque']) ?></td>
            <td><?= htmlspecialchars($v['modele']) ?></td>
            <td><?= $v['kilometrage'] ?> km</td>
            <td><?= $v['date_achat'] ?></td>
            <td><?= $v['etat'] ?></td>

            <td>
                <a href="edit.php?id=<?= $v['id_vehicule'] ?>" class="action-btn edit-btn">✏ modifier</a>

                <a href="delete.php?id=<?= $v['id_vehicule'] ?>"
                   class="action-btn delete-btn"
                   onclick="return confirm('Delete this vehicle?')">
                   🗑 supprimer
                </a>
            </td>
        </tr>
        <?php endforeach; ?>

    </table>
</div>

<?php include "../dashboard/footer.php"; ?>
</body>
</html>