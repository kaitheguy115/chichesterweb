<?php
session_start();

// Database connection details
$host = 'localhost';
$dbname = 'school_db';
$username = 'spsadmin';
$password = 'spsadmin123!';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Function to add or update a staff member
function addOrUpdateStaff($pdo, $username, $password) {
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("INSERT INTO users (username, password) VALUES (?, ?) ON DUPLICATE KEY UPDATE password = ?");
    $stmt->execute([$username, $hashedPassword, $hashedPassword]);
    return $stmt->rowCount() > 0;
}

// Function to remove a staff member
function removeStaff($pdo, $username) {
    $stmt = $pdo->prepare("DELETE FROM users WHERE username = ?");
    $stmt->execute([$username]);
    return $stmt->rowCount() > 0;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        $username = $_POST['username'];
        
        if ($_POST['action'] === 'add_update') {
            $password = $_POST['password'];
            $result = addOrUpdateStaff($pdo, $username, $password);
            echo $result ? "Staff member added/updated successfully" : "Failed to add/update staff member";
        } elseif ($_POST['action'] === 'remove') {
            $result = removeStaff($pdo, $username);
            echo $result ? "Staff member removed successfully" : "Failed to remove staff member";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Staff - Sussex Primary School</title>
</head>
<body>
    <h1>Manage Staff</h1>
    <h2>Add or Update Staff</h2>
    <form method="POST">
        <input type="hidden" name="action" value="add_update">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>
        <button type="submit">Add/Update Staff</button>
    </form>

    <h2>Remove Staff</h2>
    <form method="POST">
        <input type="hidden" name="action" value="remove">
        <label for="remove_username">Username:</label>
        <input type="text" id="remove_username" name="username" required>
        <button type="submit">Remove Staff</button>
    </form>
</body>
</html>
