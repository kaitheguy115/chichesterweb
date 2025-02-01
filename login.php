<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

$users = [
    ['username' => 'slt', 'password' => 'slt123!'],
    ['username' => 'staff', 'password' => 'staff123!'],
    ['username' => 'webdev', 'password' => 'developer123']
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action']) && $_POST['action'] === 'login') {
        $username = $_POST['username'];
        $password = $_POST['password'];

        foreach ($users as $user) {
            if ($user['username'] === $username && $user['password'] === $password) {
                $_SESSION['username'] = $username;
                echo json_encode(['success' => true]);
                exit;
            }
        }

        echo json_encode(['success' => false, 'message' => 'Invalid username or password']);
    } elseif (isset($_POST['action']) && $_POST['action'] === 'logout') {
        session_destroy();
        echo json_encode(['success' => true]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
}
?>
