<?php
session_start();

if (
    isset($_SESSION['user_id']) &&
    isset($_SESSION['user_email'])
) {
    include "../db_conn.php";

    if (isset($_GET['id']) && !empty($_GET['id'])) {
        $id = $_GET['id'];

        // Fetch the book first to get cover and file names
        $stmt = $conn->prepare("SELECT * FROM books WHERE id = ?");
        $stmt->execute([$id]);

        if ($stmt->rowCount() > 0) {
            $book = $stmt->fetch();

            // Delete book from database
            $stmtDel = $conn->prepare("DELETE FROM books WHERE id = ?");
            if ($stmtDel->execute([$id])) {
                // Delete cover and file
                @unlink("../uploads/cover/" . $book['cover']);
                @unlink("../uploads/files/" . $book['file']);

                $_SESSION['success'] = "Book deleted successfully.";
            } else {
                $_SESSION['error'] = "Failed to delete book.";
            }
        } else {
            $_SESSION['error'] = "Book not found.";
        }
    } else {
        $_SESSION['error'] = "Invalid ID.";
    }

    header("Location: ../admin.php");
    exit;
} else {
    header("Location: ../login.php");
    exit;
}
?>
