<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

// 🔒 PROTECT PAGE
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../dashboard/index.php");
    exit;
}

include('../config/database.php');
include('../dashboard/header.php');
include('../dashboard/sidebar.php');

$stmt = $pdo->query("SELECT * FROM app_user ORDER BY id_app_user DESC");
$users = $stmt->fetchAll();
?>

<div class="content">

<div class="table-container">

    <div class="header">
        <h2>👥 Users Management</h2>
        <a href="add_user.php" class="btn-add">+ Ajouter un nouvel utilisateur</a>
    </div>

    <!-- ✅ MESSAGES (FIXED LOGIC) -->
    <?php if(isset($_GET['success'])): ?>

        <?php if($_GET['success'] == 'created'): ?>
            <div class="msg success">User created successfully</div>
        <?php elseif($_GET['success'] == 'deleted'): ?>
            <div class="msg success">User deleted successfully</div>
        <?php endif; ?>

    <?php endif; ?>

    <?php if(isset($_GET['error'])): ?>
        <div class="msg error">Operation failed</div>
    <?php endif; ?>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nom</th>
                <th>Prénom</th>
                <th>Username</th>
                <th>Role</th>
                <th>Actions</th>
            </tr>
        </thead>

        <tbody>
            <?php if(count($users) > 0): ?>
                <?php foreach($users as $u): ?>
                <tr>

                    <td><?= $u['id_app_user'] ?></td>

                    <td><?= htmlspecialchars($u['nom']) ?></td>

                    <td><?= htmlspecialchars($u['prenom']) ?></td>

                    <td><?= htmlspecialchars($u['username']) ?></td>

                    <!-- ROLE -->
                    <td>
                        <?php if($u['role'] == 'admin'): ?>
                            <span class="badge admin">Admin</span>
                        <?php else: ?>
                            <span class="badge user">User</span>
                        <?php endif; ?>
                    </td>

                    <!-- ACTIONS -->
                    <td>
                        <a href="edit_user.php?id=<?= $u['id_app_user'] ?>" class="btn-edit">
                            Edit
                        </a>

                        <?php if(isset($_SESSION['user_id']) && $u['id_app_user'] != $_SESSION['user_id']): ?>
                            <a href="delete_user.php?id=<?= $u['id_app_user'] ?>"
                               class="btn-delete"
                               onclick="return confirm('Delete user?')">
                               Delete
                            </a>
                        <?php else: ?>
                            <span class="you-badge">You</span>
                        <?php endif; ?>
                    </td>

                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6">No users found</td>
                </tr>
            <?php endif; ?>
        </tbody>

    </table>

</div>
</div>

<?php include('../dashboard/footer.php'); ?>