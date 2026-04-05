<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Must be logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.html");
    exit;
}

// Check ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: list.php?error=invalid_id");
    exit;
}

$id = intval($_GET['id']);

require_once "../config/db.php";

/* 🔥 CHECK IF VEHICLE USED IN MISSIONS */
$check = $pdo->prepare("SELECT COUNT(*) FROM mission WHERE id_vehicule = ?");
$check->execute([$id]);
$count = $check->fetchColumn();

if ($count > 0) {
    // ❌ vehicle is used → block delete
    header("Location: list.php?error=used");
    exit;
}

/* ✅ DELETE VEHICLE */
$stmt = $pdo->prepare("DELETE FROM vehicule WHERE id_vehicule = ?");
$success = $stmt->execute([$id]);

if ($success) {
    header("Location: list.php?deleted=1");
    exit;
} else {
    header("Location: list.php?error=delete_failed");
    exit;
}