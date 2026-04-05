<?php
$host    = "127.0.0.1";  // 👈 changed
$port    = "3307";        // 👈 added
$db      = "gestion_parc_auto";
$user    = "root";
$pass    = "";
$charset = "utf8mb4";

try {
    $pdo = new PDO(
        "mysql:host=$host;port=$port;dbname=$db;charset=$charset",  // 👈 port added here
        $user,
        $pass,
        [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]
    );
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}