<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();
require_once "../config/db.php";

header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if ($username === '' || $password === '') {
        echo json_encode([
            "status" => "error",
            "message" => "Invalid username or password"
        ]);
        exit;
    }

    $stmt = $pdo->prepare("
        SELECT id_app_user, username, password, role
        FROM app_user
        WHERE username = ?
        LIMIT 1
    ");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {

        $_SESSION['user_id']  = $user['id_app_user'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role']     = $user['role'];

        // ✅ SAME REDIRECT FOR BOTH
        echo json_encode([
            "status" => "success",
            "redirect" => "/gestion_parc_auto/dashboard/index.php"
        ]);
        exit;
    }

    echo json_encode([
        "status" => "error",
        "message" => "Invalid username or password"
    ]);
    exit;
}

echo json_encode([
    "status" => "error",
    "message" => "Invalid request method"
]);
exit;