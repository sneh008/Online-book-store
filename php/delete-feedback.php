<?php
session_start();

// Ensure the user is logged in as an admin
if (!isset($_SESSION['user_id'], $_SESSION['user_email'])) {
    header("Location: ../login.php"); // Redirect to login if not logged in
    exit;
}

// Check if the form was submitted via POST and feedback_id is set
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['feedback_id'])) {
    include "../db_conn.php"; // Adjust path if db_conn.php is in a different location

    $feedback_id = $_POST['feedback_id'];

    try {
        $stmt = $conn->prepare("DELETE FROM feedback WHERE id = ?");
        $stmt->execute([$feedback_id]);

        if ($stmt->rowCount() > 0) {
            $_SESSION['success'] = "Feedback deleted successfully!";
        } else {
            $_SESSION['error'] = "Failed to delete feedback or feedback not found.";
        }
    } catch (PDOException $e) {
        $_SESSION['error'] = "Database error: " . $e->getMessage();
    }

    // Redirect back to the feedbacks list page
    header("Location: ../feedback_list.php");
    exit;

} else {
    // If accessed directly without POST data
    $_SESSION['error'] = "Invalid request.";
    header("Location: ../feedback_list.php");
    exit;
}
?>