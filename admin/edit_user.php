<?php
session_start();
include('../config/database.php');

// 🔒 PROTECT (admin only)
if ($_SESSION['role'] !== 'admin') {
    header("Location: ../dashboard/index.php");
    exit;
}

include('../dashboard/header.php');
include('../dashboard/sidebar.php');

// GET ID (FIXED)
$id = $_GET['id'] ?? null;

if (!$id) {
    die("User ID missing");
}

// FETCH USER (FIXED COLUMN NAME)
$stmt = $pdo->prepare("SELECT * FROM app_user WHERE id_app_user = ?");
$stmt->execute([$id]);
$user = $stmt->fetch();

if (!$user) {
    die("User not found");
}

// UPDATE USER
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $username = $_POST['username'];
    $role = $_POST['role'];

    $update = $pdo->prepare("
        UPDATE app_user 
        SET username = ?, role = ? 
        WHERE id_app_user = ?
    ");

    $update->execute([$username, $role, $id]);

    header("Location: users.php");
    exit;
}
?>

<div class="content">

    <div class="form-container">

        <h2>✏️ Edit User</h2>

        <form method="POST">

            <label>Username</label>
            <input type="text" name="username"
                   value="<?= htmlspecialchars($user['username']) ?>" required>

            <label>Role</label>
            <select name="role">
                <option value="admin" <?= $user['role']=='admin'?'selected':'' ?>>Admin</option>
                <option value="user" <?= $user['role']=='user'?'selected':'' ?>>User</option>
            </select>

            <button type="submit" class="btn-submit">Update User</button>

        </form>

    </div>

</div>

<?php include('../dashboard/footer.php'); ?>