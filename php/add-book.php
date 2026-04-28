<?php
session_start();

if (isset($_SESSION['user_id']) && isset($_SESSION['user_email'])) {
    include "../db_conn.php";
    include "func-validation.php";
    include "func-file-upload.php";

    if (
        isset($_POST['book_title']) &&
        isset($_POST['book_description']) &&
        isset($_POST['book_author']) &&
        isset($_POST['book_category'])
    ) {
        $title       = trim($_POST['book_title']);
        $description = trim($_POST['book_description']);
        $author      = trim($_POST['book_author']);
        $category    = trim($_POST['book_category']);

        // Store old input in session
        $_SESSION['old'] = [
            'title'       => $title,
            'description' => $description,
            'author_id'   => $author,
            'category_id' => $category
        ];

        // Required field validation
        is_empty($title, "Book title", "../add-book.php");
        is_empty($description, "Book description", "../add-book.php");
        is_empty($author, "Book author", "../add-book.php");
        is_empty($category, "Book category", "../add-book.php");

        // File upload handling
        // Handle book cover (reuse if already uploaded)
        if (!empty($_POST['book_cover_existing'])) {
            $book_cover_URL = $_POST['book_cover_existing'];
            $_SESSION['old']['book_cover'] = $book_cover_URL;
        } else {
            $allowed_image_exs = array("jpg", "jpeg", "png");
            $book_cover = upload_file($_FILES['book_cover'], $allowed_image_exs, "cover");

            if ($book_cover['status'] == "error") {
                $_SESSION['error'] = $book_cover['data'];
                header("Location: ../add-book.php");
                exit;
            }
            $book_cover_URL = $book_cover['data'];
            $_SESSION['old']['book_cover'] = $book_cover_URL;
        }

        // Handle book file (reuse if already uploaded)
        if (!empty($_POST['book_file_existing'])) {
            $file_URL = $_POST['book_file_existing'];
            $_SESSION['old']['book_file'] = $file_URL;
        } else {
            $allowed_file_exs = array("pdf", "docx", "pptx");
            $file = upload_file($_FILES['file'], $allowed_file_exs, "files");

            if ($file['status'] == "error") {
                $_SESSION['error'] = $file['data'];
                header("Location: ../add-book.php");
                exit;
            }
            $file_URL = $file['data'];
            $_SESSION['old']['book_file'] = $file_URL;
        }

        // Insert into DB
        $sql = "INSERT INTO books (title, author_id, description, category_id, cover, file)
                VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $res  = $stmt->execute([$title, $author, $description, $category, $book_cover_URL, $file_URL]);

        if ($res) {
            $_SESSION['success'] = "The book was added successfully!";
            unset($_SESSION['old']); // Clear old input
        } else {
            $_SESSION['error'] = "Database error. Please try again.";
        }

        header("Location: ../add-book.php");
        exit;

    } else {
        $_SESSION['error'] = "Invalid form submission.";
        header("Location: ../add-book.php");
        exit;
    }

} else {
    header("Location: ../login.php");
    exit;
}
