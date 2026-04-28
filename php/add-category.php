<?php
session_start();

if (!isset($_SESSION['user_id'], $_SESSION['user_email'])) {
    header("Location: ../login.php");
    exit;
}

require_once "../db_conn.php";

if (isset($_POST['category_name'])) {
    $name = trim($_POST['category_name']);

    if ($name === '') {
        $_SESSION['error'] = "The category name is required.";
        header("Location: ../add-category.php");
        exit;
    }

    try {
        $stmt = $conn->prepare("INSERT INTO categories (name) VALUES (?)");
        $stmt->execute([$name]);

        $_SESSION['success'] = "Category \"$name\" added successfully!";
        header("Location: ../add-category.php");
        exit;

    } catch (PDOException $e) {
        $_SESSION['error'] = "Failed to add category. Try again.";
        header("Location: ../add-category.php");
        exit;
    }
}

header("Location: ../admin.php");
exit;
