<?php
// ── Session & Auth ──────────────────────────────────────────────
if (session_status() === PHP_SESSION_NONE) session_start();

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header("Expires: 0");

if (!isset($_SESSION['user_id'])) {
    header("Location: /gestion_parc_auto/login.php");
    exit;
}

$username = $_SESSION['username'];
$role     = $_SESSION['role'];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mobilis Fleet</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* ── RESET ──────────────────────────────────────────────── */
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --sidebar-w: 260px;
            --sidebar-collapsed-w: 64px;
            --topbar-h: 60px;
            --green-dark: #1b5e42;
            --green-mid:  #2e8b57;
            --bg:         #f4f6f8;
            --card-bg:    #ffffff;
            --text:       #222;
            --transition: 0.3s ease;
        }

        body {
            font-family: "Segoe UI", sans-serif;
            background: var(--bg);
            color: var(--text);
        }

        /* ── DARK MODE ──────────────────────────────────────────── */
        body.dark { --bg: #0d1117; --card-bg: #161b22; --text: #e6edf3; }
        body.dark .sidebar              { background: linear-gradient(180deg,#0f3023,#1a4a33); }
        body.dark .sidebar-link         { color: #cdd9e5; }
        body.dark .sidebar-link:hover   { background: rgba(255,255,255,0.08); }
        body.dark .footer               { border-color: #30363d; color: #8b949e; }
        body.dark table                 { background: #161b22; color: #e6edf3; }
        body.dark table th              { background: #0d3a2c; }
        body.dark table td              { border-color: #30363d; }
        body.dark .card,
        body.dark .modern-table,
        body.dark .styled-table         { background: #161b22; color: #e6edf3; border-color: #30363d; }
        body.dark .form-container       { background: #161b22; color: #e6edf3; }
        body.dark input,
        body.dark select,
        body.dark textarea              { background: #1f2937; color: #e6edf3; border-color: #374151; }

        /* ══════════════════════════════════════════════════════════
           SIDEBAR
        ══════════════════════════════════════════════════════════ */
        .sidebar {
            position: fixed;
            top: 0; left: 0;
            width: var(--sidebar-w);
            height: 100vh;
            background: linear-gradient(180deg, var(--green-dark), var(--green-mid));
            color: white;
            display: flex;
            flex-direction: column;
            overflow-y: auto;
            overflow-x: hidden;
            transition: width var(--transition);
            z-index: 1000;
            scrollbar-width: thin;
            scrollbar-color: rgba(255,255,255,0.2) transparent;
        }
        body.sidebar-collapsed .sidebar { width: var(--sidebar-collapsed-w); }

        /* user block */
        .sidebar-user {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 6px;
            padding: 20px 16px 20px;
            border-bottom: 1px solid rgba(255,255,255,0.15);
        }
        .sidebar-avatar {
            width: 58px; height: 58px;
            border-radius: 50%;
            background: rgba(255,255,255,0.2);
            display: flex; align-items: center; justify-content: center;
            font-size: 26px; flex-shrink: 0; margin-bottom: 4px;
        }
        .sidebar-username { font-weight: 600; font-size: 16px; white-space: nowrap; }
        .sidebar-role {
            font-size: 11px;
            background: rgba(255,255,255,0.2);
            padding: 2px 7px; border-radius: 10px;
            display: inline-block; margin-top: 2px;
        }
        .sidebar-user-text { text-align: center; }
        body.sidebar-collapsed .sidebar-user-text { display: none; }

        /* nav */
        .sidebar-nav { padding: 10px 0; flex: 1; }

        .nav-label {
            font-size: 10px; font-weight: 700;
            letter-spacing: 1px; text-transform: uppercase;
            color: rgba(255,255,255,0.45);
            padding: 14px 18px 4px;
            white-space: nowrap; overflow: hidden;
        }
        body.sidebar-collapsed .nav-label { opacity: 0; }

        .sidebar-link {
            display: flex; align-items: center; gap: 12px;
            padding: 10px 18px;
            color: rgba(255,255,255,0.88);
            text-decoration: none; font-size: 14px;
            transition: background 0.2s;
            white-space: nowrap; overflow: hidden;
        }
        .sidebar-link:hover  { background: rgba(255,255,255,0.12); }
        .sidebar-link.active { background: rgba(255,255,255,0.18); font-weight: 600; }
        .sidebar-link i { font-size: 16px; width: 20px; text-align: center; flex-shrink: 0; }
        .link-text { overflow: hidden; }
        body.sidebar-collapsed .link-text { display: none; }

        /* sub-links */
        .sidebar-sub .sidebar-link {
            padding-left: 48px; font-size: 13px;
            color: rgba(255,255,255,0.72);
        }
        body.sidebar-collapsed .sidebar-sub { display: none; }

        /* logout */
        .sidebar-footer { padding: 10px 0 14px; border-top: 1px solid rgba(255,255,255,0.15); }
        .sidebar-link.logout       { color: #fca5a5; }
        .sidebar-link.logout:hover { background: rgba(239,68,68,0.15); }

        /* ══════════════════════════════════════════════════════════
           TOPBAR
        ══════════════════════════════════════════════════════════ */
        .topbar {
            position: fixed;
            top: 0;
            left: var(--sidebar-w);
            width: calc(100% - var(--sidebar-w));
            height: var(--topbar-h);
            background: linear-gradient(90deg, var(--green-dark), var(--green-mid));
            display: flex; align-items: center; justify-content: space-between;
            padding: 0 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.2);
            z-index: 1100;
            transition: left var(--transition), width var(--transition);
        }
        body.sidebar-collapsed .topbar {
            left: var(--sidebar-collapsed-w);
            width: calc(100% - var(--sidebar-collapsed-w));
        }
        .topbar-left, .topbar-right { display: flex; align-items: center; gap: 10px; }
        .topbar-title  { color: white; font-size: 17px; font-weight: 700; }
        .topbar-user   { color: rgba(255,255,255,0.85); font-size: 13px; }
        .topbar-btn {
            background: rgba(255,255,255,0.15); border: none; color: white;
            width: 36px; height: 36px; border-radius: 8px; cursor: pointer;
            display: flex; align-items: center; justify-content: center;
            font-size: 15px; transition: background 0.2s;
        }
        .topbar-btn:hover { background: rgba(255,255,255,0.28); }

        /* ══════════════════════════════════════════════════════════
           CONTENT / TABLE-CONTAINER
        ══════════════════════════════════════════════════════════ */
        .content,
        .table-container {
            margin-left: var(--sidebar-w) !important;
            margin-top: var(--topbar-h) !important;
            padding: 28px;
            min-height: calc(100vh - var(--topbar-h));
            transition: margin-left var(--transition);
            width: calc(100% - var(--sidebar-w)) !important;
            box-sizing: border-box;
        }
        body.sidebar-collapsed .content,
        body.sidebar-collapsed .table-container {
            margin-left: var(--sidebar-collapsed-w) !important;
            width: calc(100% - var(--sidebar-collapsed-w)) !important;
        }

        .form-container {
            max-width: 700px;
            margin: 20px auto !important;
            padding: 28px;
            border-radius: 12px;
            background: var(--card-bg);
            color: var(--text);
            box-shadow: 0 4px 16px rgba(0,0,0,0.08);
        }
        .form-container h2 { color: var(--green-dark); margin-bottom: 20px; }

        /* ── CARDS ──────────────────────────────────────────────── */
        .cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 20px;
        }
        .card {
            background: var(--card-bg);
            border: 1px solid #e5e7eb;
            border-radius: 12px; padding: 22px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.06);
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .card:hover { transform: translateY(-3px); box-shadow: 0 6px 18px rgba(0,0,0,0.1); }
        .card h3 { color: var(--green-dark); font-size: 16px; margin-bottom: 8px; }
        .card p  { font-size: 14px; color: #555; }
        body.dark .card p { color: #8b949e; }

        /* ── TABLES ─────────────────────────────────────────────── */
        table, .modern-table, .styled-table {
            width: 100%; border-collapse: collapse;
            background: white; border-radius: 10px;
            overflow: hidden; box-shadow: 0 4px 10px rgba(0,0,0,0.06);
        }
        table th, .modern-table th, .styled-table th {
            background: var(--green-dark); color: white;
            padding: 12px; text-align: left;
        }
        table td, .modern-table td, .styled-table td {
            padding: 12px; border-bottom: 1px solid #eee;
        }
        table tr:hover, .modern-table tr:hover, .styled-table tr:hover { background: #f9f9f9; }
        body.dark table tr:hover,
        body.dark .modern-table tr:hover,
        body.dark .styled-table tr:hover { background: #1f2937; }

        /* ── BUTTONS ────────────────────────────────────────────── */
        .btn-add    { background: #22c55e; color: white; padding: 9px 14px; border-radius: 8px; text-decoration: none; font-weight: 600; border: none; cursor: pointer; }
        .btn-edit   { background: #2196F3; color: white; padding: 6px 10px; border-radius: 5px; text-decoration: none; margin-right: 5px; }
        .btn-delete { background: #e53935; color: white; padding: 6px 10px; border-radius: 5px; text-decoration: none; }
        .btn-view   { background: #6c5ce7; color: white; padding: 6px 10px; border-radius: 5px; text-decoration: none; margin-right: 5px; }
        .home-btn   { background: var(--green-dark); color: white; padding: 9px 16px; border-radius: 6px; text-decoration: none; font-weight: 600; }
        .btn-add:hover    { background: #16a34a; }
        .btn-edit:hover   { background: #1976D2; }
        .btn-delete:hover { background: #c62828; }
        .home-btn:hover   { background: #134a32; }
        .submit-btn, .btn-submit {
            margin-top: 20px; width: 100%;
            background: var(--green-dark); color: white;
            padding: 12px; border: none; border-radius: 8px;
            font-size: 16px; cursor: pointer;
        }
        .submit-btn:hover, .btn-submit:hover { background: #134a32; }

        /* ── BADGES ─────────────────────────────────────────────── */
        .badge { padding: 4px 10px; border-radius: 6px; font-size: 12px; color: white; }
        .badge.active, .badge.planned { background: #f59e0b; }
        .badge.done                   { background: #10b981; }
        .badge.danger                 { background: #ef4444; }
        .badge.warning                { background: #f59e0b; }
        .badge.admin  { background: #2e7d32; }
        .badge.user   { background: #546e7a; }

        /* ── MESSAGES ───────────────────────────────────────────── */
        .msg { padding: 10px; border-radius: 6px; margin-bottom: 15px; }
        .msg.success { background: #d4edda; color: #155724; }
        .msg.error   { background: #f8d7da; color: #721c24; }

        /* ── PAGE HEADER ────────────────────────────────────────── */
        .page-header, .header {
            display: flex; justify-content: space-between; align-items: center;
            margin-bottom: 20px;
        }
        .page-header h2, .header h2 { font-size: 20px; color: var(--green-dark); }

        /* ── FOOTER ─────────────────────────────────────────────── */
        .footer {
            margin-top: 40px; padding: 18px 28px;
            text-align: center; font-size: 13px;
            color: #888; border-top: 1px solid #e5e7eb;
        }

        /* ── OVERLAY (mobile) ───────────────────────────────────── */
        .sidebar-overlay {
            display: none; position: fixed; inset: 0;
            background: rgba(0,0,0,0.45); z-index: 999;
        }
        body.sidebar-open .sidebar-overlay { display: block; }

        /* ══════════════════════════════════════════════════════════
           RESPONSIVE
        ══════════════════════════════════════════════════════════ */
        @media (max-width: 768px) {
            .sidebar {
                top: var(--topbar-h) !important;
                left: calc(-1 * var(--sidebar-w)) !important;
                width: var(--sidebar-w) !important;
                transition: left var(--transition);
            }
            body.sidebar-open .sidebar { left: 0 !important; }
            .topbar { left: 0 !important; width: 100% !important; }
            .content, .table-container {
                margin-left: 0 !important;
                width: 100% !important;
                padding: 16px;
            }
            .topbar-user { display: none; }
        }
    </style>
</head>
<body>

<!-- ══ OVERLAY ═══════════════════════════════════════════════════ -->
<div class="sidebar-overlay" id="sidebarOverlay"></div>

<!-- ══ TOPBAR ════════════════════════════════════════════════════ -->
<header class="topbar">
    <div class="topbar-left">
        <button id="menuToggle" class="topbar-btn" title="Menu">
            <i class="fas fa-bars"></i>
        </button>
        <span class="topbar-title">Mobilis Fleet</span>
    </div>
    <div class="topbar-right">
        <span class="topbar-user">
            <i class="fas fa-user-circle"></i>
            <?= htmlspecialchars($username) ?> &nbsp;·&nbsp; <?= strtoupper($role) ?>
        </span>
        <button id="themeToggle" class="topbar-btn" title="Thème">
            <i class="fas fa-moon"></i>
        </button>
    </div>
</header>
