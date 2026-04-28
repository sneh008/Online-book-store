<?php
/**
 * delete-author.php
 * ------------------
 * Securely deletes an author by their ID.
 *
 *  - Accessible only to logged‑in admins (checks session).
 *  - Uses prepared statements to prevent SQL injection.
 *  - Refuses to delete if books are still assigned to the author.
 *  - Stores success/error feedback in $_SESSION["success"|"error"]
 *    so the admin.php dashboard can show flash messages without exposing them in the URL.
 */

session_start();

# Ensure the user is logged in ---------------------------------------------
if (!isset($_SESSION['user_id'], $_SESSION['user_email'])) {
    header("Location: ../login.php");
    exit;
}

# Validate the incoming ID -------------------------------------------------
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    $_SESSION['error'] = "Invalid author request.";
    header("Location: ../admin.php");
    exit;
}

$author_id = (int) $_GET['id'];

# DB connection ------------------------------------------------------------
require_once '../db_conn.php';

try {
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    # 1) Confirm the author exists -----------------------------------------
    $stmt = $conn->prepare("SELECT id FROM authors WHERE id = ? LIMIT 1");
    $stmt->execute([$author_id]);

    if ($stmt->rowCount() === 0) {
        $_SESSION['error'] = "Author not found.";
        header("Location: ../admin.php");
        exit;
    }

    # 2) Check if any books depend on this author --------------------------
    $stmt = $conn->prepare("SELECT COUNT(*) FROM books WHERE author_id = ?");
    $stmt->execute([$author_id]);
    $books_using_author = (int) $stmt->fetchColumn();

    if ($books_using_author > 0) {
        $_SESSION['error'] = "Cannot delete: {$books_using_author} book(s) are still assigned to this author.";
        header("Location: ../admin.php");
        exit;
    }

    # 3) Safe to delete ----------------------------------------------------
    $stmt = $conn->prepare("DELETE FROM authors WHERE id = ?");
    $stmt->execute([$author_id]);

    $_SESSION['success'] = "Author deleted successfully.";

} catch (PDOException $e) {
    $_SESSION['error'] = "Something went wrong while deleting the author.";
}

# Redirect back to the dashboard -------------------------------------------
header("Location: ../admin.php");
exit;
