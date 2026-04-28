<?php
// php/feedback-process.php
session_start();
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../contact.php");
    exit;
}

require_once "../db_conn.php";          // <- gives you $conn (PDO)

// ─── 1. Collect & validate ───
$name    = trim($_POST['name']    ?? '');
$email   = trim($_POST['email']   ?? '');
$rating  = intval($_POST['rating'] ?? 0);
$message = trim($_POST['message'] ?? '');

$errors = [];

if ($name === '')        $errors[] = "Name is required.";
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Valid e‑mail required.";
if ($rating < 1 || $rating > 5)                 $errors[] = "Please give a rating (1‑5 stars).";
if ($message === '')     $errors[] = "Feedback message cannot be empty.";

if ($errors) {
    $_SESSION['error'] = implode(' ', $errors);
    header("Location: ../feedback.php");
    exit;
}

// ─── 2. Insert into DB ───
try {
    $stmt = $conn->prepare(
        "INSERT INTO feedback (name, email, rating, message) 
         VALUES (:name, :email, :rating, :message)"
    );
    $stmt->execute([
        ':name'    => $name,
        ':email'   => $email,
        ':rating'  => $rating,
        ':message' => $message
    ]);

    $_SESSION['success'] = "Thank you for your feedback!";
} catch (PDOException $e) {
    $_SESSION['error'] = "Database error – please try again later.";
}
// ─── 3. Redirect back ───
header("Location: ../feedback.php");
exit;
