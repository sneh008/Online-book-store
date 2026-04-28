<?php
session_start();

if (isset($_SESSION['user_id']) && isset($_SESSION['user_email'])) {
    include "../db_conn.php";

    if (isset($_POST['author_name'])) {
        $name = trim($_POST['author_name']);

        if (empty($name)) {
            $_SESSION['error'] = "The author name is required.";
            header("Location: ../add-author.php");
            exit;
        } else {
            $sql = "INSERT INTO authors (name) VALUES (?)";
            $stmt = $conn->prepare($sql);
            $res = $stmt->execute([$name]);

            if ($res) {
                $_SESSION['success'] = "Successfully created!";
            } else {
                $_SESSION['error'] = "Unknown error occurred!";
            }

            header("Location: ../add-author.php");
            exit;
        }
    } else {
        $_SESSION['error'] = "Invalid request!";
        header("Location: ../add-author.php");
        exit;
    }
} else {
    header("Location: ../login.php");
    exit;
}
