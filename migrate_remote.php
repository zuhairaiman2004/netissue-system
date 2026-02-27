<?php
/**
 * REMOTE DATABASE MIGRATION TOOL
 * Visit this page once (e.g., your-link.railway.app/migrate_remote.php) 
 * to update your live database schema.
 */
require_once 'db_conn.php';

echo "<h2>NetIssue Live Migration Tool</h2>";

// 1. Add email column
$check_column = mysqli_query($conn, "SHOW COLUMNS FROM `users` LIKE 'email'");
if (mysqli_num_rows($check_column) == 0) {
    $sql = "ALTER TABLE users ADD COLUMN email VARCHAR(100) AFTER username";
    if (mysqli_query($conn, $sql)) {
        echo "<p style='color:green;'>✅ Successfully added 'email' column to 'users' table.</p>";
    } else {
        echo "<p style='color:red;'>❌ Error adding email column: " . mysqli_error($conn) . "</p>";
    }
} else {
    echo "<p>ℹ️ 'email' column already exists.</p>";
}

// 2. Update role enum
$sql_enum = "ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'user', 'staff') DEFAULT 'user'";
if (mysqli_query($conn, $sql_enum)) {
    echo "<p style='color:green;'>✅ Successfully updated 'role' enum (admin, user, staff).</p>";
} else {
    echo "<p style='color:red;'>❌ Error updating role enum: " . mysqli_error($conn) . "</p>";
}

echo "<hr><p>Migration completed. You can delete this file (migrate_remote.php) for security after you see SUCCESS.</p>";
echo "<a href='index.php'>Go to Login Page</a>";

mysqli_close($conn);
?>
