<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../dashboard/index.php");
    exit;
}

include('../config/database.php');
include('../dashboard/header.php');
include('../dashboard/sidebar.php');

$id = $_GET['id'] ?? null;
if (!$id) {
    header("Location: users.php");
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM app_user WHERE id_app_user = ?");
$stmt->execute([$id]);
$user = $stmt->fetch();

if (!$user) {
    header("Location: users.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $role     = $_POST['role'];

    $pdo->prepare("UPDATE app_user SET username=?, role=? WHERE id_app_user=?")
        ->execute([$username, $role, $id]);

    header("Location: users.php?success=updated");
    exit;
}
?>

<div class="content">
<div class="form-container">

    <h2>Modifier Utilisateur ✏️</h2>

    <form method="POST">

        <div class="input-group">
            <label>Username</label>
            <input type="text" name="username" value="<?= htmlspecialchars($user['username']) ?>" required>
        </div>

        <div class="input-group">
            <label>Rôle</label>
            <select name="role">
                <option value="admin" <?= $user['role']=='admin'?'selected':'' ?>>Admin</option>
                <option value="user"  <?= $user['role']=='user' ?'selected':'' ?>>User</option>
            </select>
        </div>

        <button type="submit" class="submit-btn">Enregistrer les modifications</button>

    </form>

</div>
</div>

<?php include('../dashboard/footer.php'); ?>
