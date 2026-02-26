<?php
session_start();
include('db_conn.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$role = $_SESSION['role'] ?? 'user';

// FUNGSI 1: DELETE ALL (Hanya untuk Admin)
if (isset($_GET['all']) && $_GET['all'] == 'true' && $role === 'admin') {
    $query = "DELETE FROM issues"; // Cuci semua sekali dalam table
    if (mysqli_query($conn, $query)) {
        header("Location: admin_dashboard.php?msg=All records cleared");
        exit();
    }
}

// FUNGSI 2: DELETE SATU-SATU (Untuk Admin & User)
if (isset($_GET['id'])) {
    $issue_id = mysqli_real_escape_string($conn, $_GET['id']);
    $user_id = $_SESSION['user_id'];

    if ($role === 'admin') {
        $query = "DELETE FROM issues WHERE issue_id = '$issue_id'";
    } else {
        $query = "DELETE FROM issues WHERE issue_id = '$issue_id' AND user_id = '$user_id'";
    }

    if (mysqli_query($conn, $query)) {
        $location = ($role === 'admin') ? "admin_dashboard.php" : "user_dashboard.php";
        header("Location: $location?msg=Deleted");
        exit();
    }
}
?>