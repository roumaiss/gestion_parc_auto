<?php
// Safe session initialization
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/* NO CACHE FOR PROTECTED PAGES */
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header("Expires: 0");

/* PAGE PROTECTION */
if (!isset($_SESSION['user_id'])) {
    header("Location: /gestion_parc_auto/login.php");
    exit;
}

$username = $_SESSION['username'];
$role     = $_SESSION['role'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Mobilis Fleet - Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- CSS -->
    <link rel="stylesheet" href="/gestion_parc_auto/assets/dashboard.css">
    <link rel="stylesheet" href="/gestion_parc_auto/assets/theme.css">

    <!-- Font Awesome -->
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <script>
document.addEventListener("DOMContentLoaded", function () {

    /* THEME TOGGLE */
    const toggle = document.getElementById("themeToggle");

    if (toggle) {
        if (localStorage.getItem("theme") === "dark") {
            document.body.classList.add("dark");
            toggle.innerHTML = '<i class="fas fa-sun"></i>';
        }

        toggle.addEventListener("click", function () {
            document.body.classList.toggle("dark");

            if (document.body.classList.contains("dark")) {
                localStorage.setItem("theme", "dark");
                toggle.innerHTML = '<i class="fas fa-sun"></i>';
            } else {
                localStorage.setItem("theme", "light");
                toggle.innerHTML = '<i class="fas fa-moon"></i>';
            }
        });
    }

    /* SIDEBAR TOGGLE */
    const menuToggle = document.getElementById("menuToggle");

    if (menuToggle) {
        menuToggle.addEventListener("click", function () {
            document.body.classList.toggle("sidebar-collapsed");
        });
    }
});

/* FORCE RELOAD IF PAGE IS RESTORED FROM BACK CACHE */
window.addEventListener("pageshow", function (event) {
    if (event.persisted) {
        window.location.reload();
    }
});
</script>
</head>

<body>

<div class="topbar">
    <button id="menuToggle" class="theme-btn">
        <i class="fas fa-bars"></i>
    </button>

    <button id="themeToggle" class="theme-btn">
        <i class="fas fa-moon"></i>
    </button>
</div>