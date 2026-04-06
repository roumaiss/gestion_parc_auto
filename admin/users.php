<?php
session_start();

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

    <div class="page-header">
        <h2>Gestion des Utilisateurs</h2>
        <a href="add_user.php" class="btn-add">+ Ajouter un utilisateur</a>
    </div>

    <?php if(isset($_GET['success'])): ?>
        <?php if($_GET['success'] == 'created'): ?>
            <div class="msg success">✅ Utilisateur créé avec succès.</div>
        <?php elseif($_GET['success'] == 'deleted'): ?>
            <div class="msg success">✅ Utilisateur supprimé.</div>
        <?php endif; ?>
    <?php endif; ?>
    <?php if(isset($_GET['error'])): ?>
        <div class="msg error">❌ Opération échouée.</div>
    <?php endif; ?>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nom</th>
                <th>Prénom</th>
                <th>Username</th>
                <th>Rôle</th>
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
                    <td>
                        <span class="badge <?= $u['role'] === 'admin' ? 'admin' : 'user' ?>">
                            <?= ucfirst($u['role']) ?>
                        </span>
                    </td>
                    <td>
                        <a href="edit_user.php?id=<?= $u['id_app_user'] ?>" class="btn-edit">✏ Modifier</a>
                        <?php if($u['id_app_user'] != $_SESSION['user_id']): ?>
                            <?php $msg = "Supprimer l'utilisateur " . htmlspecialchars($u['username'], ENT_QUOTES) . " ?"; ?>
                            <a href="#" class="btn-delete"
                               onclick="openDeleteModal('delete_user.php?id=<?= $u['id_app_user'] ?>', '<?= $msg ?>')">🗑 Supprimer</a>
                        <?php else: ?>
                            <span class="badge done">Vous</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="6" style="text-align:center;color:#888;">Aucun utilisateur trouvé</td></tr>
            <?php endif; ?>
        </tbody>
    </table>

</div>

<?php include('../dashboard/footer.php'); ?>
