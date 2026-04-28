<?php
session_start();

if (!isset($_SESSION['user_id'], $_SESSION['user_email'])) {
    header("Location: ../login.php");
    exit;
}

require_once "../db_conn.php";

if (isset($_POST['category_name'], $_POST['category_id'])) {
    $name = trim($_POST['category_name']);
    $id = trim($_POST['category_id']);

    $_SESSION['edit_id'] = $id;

    if (empty($name)) {
        $_SESSION['error'] = "The category name is required";
        $_SESSION['old_name'] = $name;
        header("Location: ../edit-category.php");
        exit;
    }

    try {
        $stmt = $conn->prepare("UPDATE categories SET name = ? WHERE id = ?");
        $stmt->execute([$name, $id]);

        $_SESSION['success'] = "Category \"$name\" edited successfully!";
        $_SESSION['edit_id'] = $id;
        header("Location: ../edit-category.php");
        exit;
    } catch (PDOException $e) {
        $_SESSION['error'] = "Failed to edit category. Try again.";
        $_SESSION['old_name'] = $name;
        header("Location: ../edit-category.php");
        exit;
    }
}

header("Location: ../admin.php");
exit;
