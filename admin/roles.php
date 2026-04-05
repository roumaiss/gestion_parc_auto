<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

// 🔒 PROTECT PAGE
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../dashboard/index.php");
    exit;
}

include('../config/database.php');
include('../dashboard/header.php');
include('../dashboard/sidebar.php');
?>

<div class="content">
<div class="mission-container">

    <div class="header">
        <h2>🔐 Roles System</h2>
    </div>

    <table>
        <thead>
            <tr>
                <th>Role</th>
                <th>Description</th>
                <th>Access</th>
            </tr>
        </thead>

        <tbody>

            <tr>
                <td><span class="badge done">Admin</span></td>
                <td>Full access to system</td>
                <td>All modules</td>
            </tr>

            <tr>
                <td><span class="badge active">User</span></td>
                <td>Limited access</td>
                <td>Missions, Vehicles, Maintenance, carburant, incidents</td>
            </tr>

           

        </tbody>
    </table>

</div>
</div>

<?php include('../dashboard/footer.php'); ?>