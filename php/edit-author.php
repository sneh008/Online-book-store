<?php
session_start();

if (!isset($_SESSION['user_id'], $_SESSION['user_email'])) {
    header("Location: ../login.php");
    exit;
}

require_once "../db_conn.php";

// Check if request is POST and required fields are set
if ($_SERVER['REQUEST_METHOD'] === 'POST' &&
    isset($_POST['author_name'], $_POST['author_id'])) {

    $name = trim($_POST['author_name']);
    $id = trim($_POST['author_id']);

    $_SESSION['edit_id'] = $id;

    // Validation
    if (empty($name)) {
        $_SESSION['error'] = "The Author name is required.";
        $_SESSION['old_name'] = $name;
        header("Location: ../edit-author.php");
        exit;
    }

    try {
        $stmt = $conn->prepare("UPDATE authors SET name = ? WHERE id = ?");
        $stmt->execute([$name, $id]);

        $_SESSION['success'] = "Author \"$name\" edited successfully!";
        $_SESSION['edit_id'] = $id;
        header("Location: ../edit-author.php");
        exit;

    } catch (PDOException $e) {
        $_SESSION['error'] = "Failed to edit author. Please try again.";
        $_SESSION['old_name'] = $name;
        header("Location: ../edit-author.php");
        exit;
    }

} else {
    // If the script is accessed directly or data is missing
    $_SESSION['error'] = "Invalid request. Author data missing.";
    header("Location: ../admin.php");
    exit;
}
