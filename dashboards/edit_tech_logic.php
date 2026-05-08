<?php
session_start();
require_once '../config/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && $_SESSION['role'] == 'Assign Admin') {
    $id = $_POST['user_id'];
    $full_name = $_POST['full_name'];
    $username = $_POST['username'];
    $profession = $_POST['profession'];
    $password = $_POST['password'];

    try {
        if (!empty($password)) {
            $hashed_pass = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("UPDATE users SET full_name = ?, username = ?, profession = ?, password = ? WHERE id = ?");
            $stmt->execute([$full_name, $username, $profession, $hashed_pass, $id]);
        } else {
            $stmt = $pdo->prepare("UPDATE users SET full_name = ?, username = ?, profession = ? WHERE id = ?");
            $stmt->execute([$full_name, $username, $profession, $id]);
        }
        header("Location: manage_technicians.php?success=updated");
    } catch (Exception $e) {
        die("Update Error: " . $e->getMessage());
    }
}