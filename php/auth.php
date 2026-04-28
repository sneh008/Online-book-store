<?php 
session_start();

if (isset($_POST['email']) && isset($_POST['password'])) {

    include "../db_conn.php";
    include "func-validation.php";

    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    $_SESSION['email'] = $email;

    if (empty($email)) {
        $_SESSION['error'] = "Email is required";
        header("Location: ../login.php");
        exit;
    }

    if (empty($password)) {
        $_SESSION['error'] = "Password is required";
        header("Location: ../login.php");
        exit;
    }

    $sql = "SELECT * FROM admin WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$email]);

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_email'] = $user['email'];
            unset($_SESSION['error'], $_SESSION['email']);
            header("Location: ../admin.php");
            exit;
        } else {
            $_SESSION['error'] = "Incorrect password";
            header("Location: ../login.php");
            exit;
        }
    } else {
        $_SESSION['error'] = "No user found with this email";
        header("Location: ../login.php");
        exit;
    }

} else {
    header("Location: ../login.php");
    exit;
}
