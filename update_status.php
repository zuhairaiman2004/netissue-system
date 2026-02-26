<?php
session_start();
include('db_conn.php');

// 1. Check if user is admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit();
}

// 2. Process the update
if (isset($_POST['update_now'])) {
    $id = mysqli_real_escape_string($conn, $_POST['issue_id']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);
    $remarks = mysqli_real_escape_string($conn, $_POST['remarks']);

    $query = "UPDATE issues SET status = '$status', admin_remarks = '$remarks' WHERE issue_id = '$id'";

    if (mysqli_query($conn, $query)) {
        // Redirect back with success message
        header("Location: admin_dashboard.php?msg=Ticket updated successfully!");
        exit();
    } else {
        echo "Error updating record: " . mysqli_error($conn);
    }
} else {
    // If someone tries to access this file directly, kick them back
    header("Location: admin_dashboard.php");
    exit();
}
?>