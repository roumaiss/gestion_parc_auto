<?php
session_start();
require_once "config/database.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);

    // Check empty fields
    if (empty($username) || empty($password)) {
        header("Location: login.php?error=1");
        exit;
    }

    // Get user
    $sql = "SELECT * FROM app_user WHERE username = :username LIMIT 1";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(["username" => $username]);
    $user = $stmt->fetch();

    // Check password
    if ($user && password_verify($password, $user["password"])) {

        // SESSION
        $_SESSION["user_id"]  = $user["id_app_user"]; // ✅ unified name
        $_SESSION["username"] = $user["username"];
        $_SESSION["role"]     = $user["role"];

        // ✅ CORRECT REDIRECT (MAIN FIX)
        header("Location: dashboard/index.php");
        exit;

    } else {
        header("Location: login.php?error=1");
        exit;
    }
}
?>