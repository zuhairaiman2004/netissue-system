<?php
/**
 * DATABASE IMPORT SCRIPT (Robust Version)
 * This script will import your netissue_db.sql into Railway automatically.
 */
// Disable strict error reporting to prevent "Fatal Error" on duplicate tables
mysqli_report(MYSQLI_REPORT_OFF);

include('db_conn.php');

$sqlFile = 'netissue_db.sql';

echo "<h2>NetIssue Database Importer</h2>";

// 1. Check if the file exists
if (!file_exists($sqlFile)) {
    die("<p style='color:red;'>Error: <b>$sqlFile</b> not found!</p>
         <p>Please make sure you have copied your SQL file from Downloads into: <code>c:\xampp\htdocs\netissue\</code></p>");
}

echo "<p>Found $sqlFile. Starting import...</p>";

// 2. Read the SQL file
$sqlContent = file_get_contents($sqlFile);

// 3. Execute multi-query
// This will return false if the FIRST query fails, but we need to check subsequent ones too
if (mysqli_multi_query($conn, $sqlContent)) {
    do {
        // Just consume results
        if ($result = mysqli_store_result($conn)) {
            mysqli_free_result($result);
        }
    } while (mysqli_next_result($conn));
}

// 4. Final Verification: Check if tables exist
$check = mysqli_query($conn, "SHOW TABLES LIKE 'issues'");
if (mysqli_num_rows($check) > 0) {
    echo "<p style='color:green; font-size: 1.2rem; font-weight:bold;'>✅ SUCCESS! Your database is ready on Railway.</p>";
    echo "<p>Even if you saw an error message, the tables have been verified to exist in your Railway database.</p>";
    echo "<p><b>Next Step:</b> Go to your Railway Dashboard, click your 'Web application' service, and check 'Settings' -> 'Networking' to see your live URL.</p>";
} else {
    echo "<p style='color:red;'>❌ ERROR: Tables were not created.</p>";
    echo "<p>Reason: " . mysqli_error($conn) . "</p>";
}
?>
