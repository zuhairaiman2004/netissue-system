<?php
session_start();
include('db_conn.php');

// 1. Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

// 2. Process data when button is clicked
if (isset($_POST['submit_report'])) {
    $user_id = $_SESSION['user_id'];
    
    // Ambil data dan bersihkan (Security)
    $category = mysqli_real_escape_string($conn, $_POST['category']);
    $description = mysqli_real_escape_string($conn, $_POST['description']); // Sini punca kalau takde
    $status = "Pending"; // Default status

    // 3. Masukkan ke dalam database
    // Pastikan susunan column dalam database kau sama: user_id, category, description, status
    $query = "INSERT INTO issues (user_id, category, description, status, created_at) 
              VALUES ('$user_id', '$category', '$description', '$status', NOW())";

    if (mysqli_query($conn, $query)) {
        // Berjaya! Hantar balik ke dashboard dengan mesej
        header("Location: user_dashboard.php?msg=Report submitted successfully!");
        exit();
    } else {
        // Kalau error, tunjuk error apa
        echo "Error: " . $query . "<br>" . mysqli_error($conn);
    }
} else {
    // Kalau orang cuba akses file ni terus tanpa form, kick balik
    header("Location: user_dashboard.php");
    exit();
}
?>