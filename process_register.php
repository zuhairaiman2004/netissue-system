<?php
// Sambungan ke Database - Menggunakan db_conn.php yang sedia ada
require_once 'db_conn.php';

if (isset($_POST['register'])) {
    $user     = mysqli_real_escape_string($conn, $_POST['username']);
    $email    = mysqli_real_escape_string($conn, $_POST['email']);
    $pass     = $_POST['password'];

    // 1. Password Hashing (WAJIB untuk Security)
    $hashed_pass = password_hash($pass, PASSWORD_DEFAULT);

    // 2. Set role default sebagai 'user'
    $role = 'user';

    // 3. Semak jika username atau email dah wujud
    $check_query = "SELECT * FROM users WHERE username='$user' OR email='$email'";
    $check_result = mysqli_query($conn, $check_query);
    
    if (mysqli_num_rows($check_result) > 0) {
        $existing_user = mysqli_fetch_assoc($check_result);
        if ($existing_user['username'] === $user) {
            echo "<script>alert('Alamak! Username ni dah ada orng guna. Cuba yang lain.'); window.location='register.php';</script>";
        } else {
            echo "<script>alert('Email ni dah didaftarkan. Sila guna email lain.'); window.location='register.php';</script>";
        }
    } else {
        // 4. Masukkan data ke table 'users'
        $sql = "INSERT INTO users (username, email, password, role) VALUES ('$user', '$email', '$hashed_pass', '$role')";
        
        if (mysqli_query($conn, $sql)) {
            echo "<script>alert('Akaun berjaya dicipta! Sila login.'); window.location='../login.php';</script>";
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    }
}
mysqli_close($conn);
?>