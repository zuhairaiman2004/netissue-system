<?php
// Enable Error Reporting for Debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Fallbacks for local XAMPP environment
$hostname = getenv('DB_HOST') ?: "localhost";
$username = getenv('DB_USER') ?: "root";
$password = getenv('DB_PASSWORD') ?: "";
$dbname   = getenv('DB_NAME') ?: "netissue_db";
$port     = getenv('DB_PORT') ?: 3306;

// To support direct URL connecting provided by Railway (Optional but recommended)
// Railway usually provides a MYSQL_URL env variable in the format: mysql://USER:PASSWORD@HOST:PORT/DATABASE
$mysqlUrl = getenv('MYSQL_URL');
if ($mysqlUrl) {
    $dbUrlParts = parse_url($mysqlUrl);
    $hostname = $dbUrlParts['host'];
    $username = $dbUrlParts['user'];
    $password = $dbUrlParts['pass'];
    $dbname   = ltrim($dbUrlParts['path'], '/');
    $port     = $dbUrlParts['port'] ?? 3306;
}

// Memulakan sambungan
$conn = mysqli_connect($hostname, $username, $password, $dbname, $port);

// Check kalau sambungan gagal
if (!$conn) {
    die("Alamak! Sambungan ke database gagal: " . mysqli_connect_error());
}
// Kalau berjaya, dia takkan keluar apa-apa (ini bagus!)
?>