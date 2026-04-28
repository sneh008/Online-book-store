<?php
/**
 * delete-category.php
 * -------------------
 * Securely deletes a category by its ID.
 *
 *  - Accessible only to logged‑in admins (checks session).
 *  - Uses prepared statements to prevent SQL injection.
 *  - Refuses to delete if books are still assigned to the category.
 *  - Stores success/error feedback in $_SESSION["success"|"error"] so the
 *    admin.php dashboard can show flash messages without exposing them in the URL.
 */

session_start();

# Ensure the user is logged in ---------------------------------------------
if (!isset($_SESSION['user_id'], $_SESSION['user_email'])) {
    header("Location: ../login.php");
    exit;
}

# Validate the incoming ID --------------------------------------------------
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    $_SESSION['error'] = "Invalid category request.";
    header("Location: ../admin.php");
    exit;
}

$category_id = (int) $_GET['id'];

# DB connection -------------------------------------------------------------
require_once '../db_conn.php';

try {
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    # 1) Confirm the category exists ---------------------------------------
    $stmt = $conn->prepare("SELECT id FROM categories WHERE id = ? LIMIT 1");
    $stmt->execute([$category_id]);

    if ($stmt->rowCount() === 0) {
        $_SESSION['error'] = "Category not found.";
        header("Location: ../admin.php");
        exit;
    }

    # 2) Make sure no books depend on this category ------------------------
    $stmt = $conn->prepare("SELECT COUNT(*) FROM books WHERE category_id = ?");
    $stmt->execute([$category_id]);
    $books_using_category = (int) $stmt->fetchColumn();

    if ($books_using_category > 0) {
        $_SESSION['error'] = "Cannot delete: {$books_using_category} book(s) are still assigned to this category.";
        header("Location: ../admin.php");
        exit;
    }

    # 3) Safe to delete ----------------------------------------------------
    $stmt = $conn->prepare("DELETE FROM categories WHERE id = ?");
    $stmt->execute([$category_id]);

    $_SESSION['success'] = "Category deleted successfully.";

} catch (PDOException $e) {
    $_SESSION['error'] = "Something went wrong while deleting the category.";
}

# Redirect back to the dashboard -------------------------------------------
header("Location: ../admin.php");
exit;