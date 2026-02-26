<?php
session_start();
include('db_conn.php');

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Kita guna mysqli_real_escape_string untuk keselamatan sikit
    $username = mysqli_real_escape_string($conn, $username);
    $password = mysqli_real_escape_string($conn, $password);

    // Tukar 'user_id' atau 'id' ikut apa yang kau letak kat database
    $query = "SELECT * FROM users WHERE username='$username' AND password='$password'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);
        
        $_SESSION['user_id'] = $row['user_id']; // Pastikan ini sama dengan nama kolum di database
        $_SESSION['role'] = $row['role'];

        if ($row['role'] == 'admin') {
            header("Location: admin_dashboard.php");
            exit();
        } else {
            header("Location: user_dashboard.php");
            exit();
        }
    } else {
        echo "<script>alert('Username atau Password Salah!'); window.location='index.php';</script>";
    }
}
?>