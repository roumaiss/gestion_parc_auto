<?php
session_start();
include('../admin/admin_protect.php');
include('../config/database.php');

// CHECK ID
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: users.php");
    exit;
}

$id = $_GET['id'];

try {

    // OPTIONAL: prevent deleting yourself
    if ($_SESSION['user_id'] == $id) {
        header("Location: users.php?error=self_delete");
        exit;
    }

    // DELETE USER
    $stmt = $pdo->prepare("DELETE FROM app_user WHERE id_app_user = ?");
    $stmt->execute([$id]);

    header("Location: users.php?success=deleted");
    exit;

} catch (PDOException $e) {
    header("Location: users.php?error=delete_failed");
    exit;
}