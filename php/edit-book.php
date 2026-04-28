<?php
session_start();

if (!isset($_SESSION['user_id'], $_SESSION['user_email'])) {
    header("Location: ../login.php");
    exit;
}

include "../db_conn.php";
include "func-validation.php";
include "func-file-upload.php";

if (
    isset($_POST['book_id']) &&
    isset($_POST['book_title']) &&
    isset($_POST['book_description']) &&
    isset($_POST['book_author']) &&
    isset($_POST['book_category']) &&
    isset($_FILES['book_cover']) &&
    isset($_FILES['file']) &&
    isset($_POST['current_cover']) &&
    isset($_POST['current_file'])
) {
    $id = $_POST['book_id'];
    $title = $_POST['book_title'];
    $description = $_POST['book_description'];
    $author = $_POST['book_author'];
    $category = $_POST['book_category'];
    $current_cover = $_POST['current_cover'];
    $current_file = $_POST['current_file'];

    is_empty($title, "Book title", "../edit-book.php");
    is_empty($description, "Book description", "../edit-book.php");
    is_empty($author, "Book author", "../edit-book.php");
    is_empty($category, "Book category", "../edit-book.php");

    $_SESSION['edit_id'] = $id;

    if (!empty($_FILES['book_cover']['name']) && !empty($_FILES['file']['name'])) {
        $book_cover = upload_file($_FILES['book_cover'], ["jpg", "jpeg", "png"], "cover");
        $file = upload_file($_FILES['file'], ["pdf", "docx", "pptx"], "files");

        if ($book_cover['status'] == "error" || $file['status'] == "error") {
            $_SESSION['error'] = $book_cover['data'] ?? $file['data'];
            header("Location: ../edit-book.php");
            exit;
        }

        unlink("../uploads/cover/$current_cover");
        unlink("../uploads/files/$current_file");

        $sql = "UPDATE books SET title=?, author_id=?, description=?, category_id=?, cover=?, file=? WHERE id=?";
        $stmt = $conn->prepare($sql);
        $res = $stmt->execute([$title, $author, $description, $category, $book_cover['data'], $file['data'], $id]);

        if ($res) {
            $_SESSION['success'] = "Successfully updated!";
        } else {
            $_SESSION['error'] = "Unknown error occurred!";
        }
        header("Location: ../edit-book.php");
        exit;
    }

    if (!empty($_FILES['book_cover']['name'])) {
        $book_cover = upload_file($_FILES['book_cover'], ["jpg", "jpeg", "png"], "cover");

        if ($book_cover['status'] == "error") {
            $_SESSION['error'] = $book_cover['data'];
            header("Location: ../edit-book.php");
            exit;
        }

        unlink("../uploads/cover/$current_cover");

        $sql = "UPDATE books SET title=?, author_id=?, description=?, category_id=?, cover=? WHERE id=?";
        $stmt = $conn->prepare($sql);
        $res = $stmt->execute([$title, $author, $description, $category, $book_cover['data'], $id]);

        if ($res) {
            $_SESSION['success'] = "Successfully updated!";
        } else {
            $_SESSION['error'] = "Unknown error occurred!";
        }
        header("Location: ../edit-book.php");
        exit;
    }

    if (!empty($_FILES['file']['name'])) {
        $file = upload_file($_FILES['file'], ["pdf", "docx", "pptx"], "files");

        if ($file['status'] == "error") {
            $_SESSION['error'] = $file['data'];
            header("Location: ../edit-book.php");
            exit;
        }

        unlink("../uploads/files/$current_file");

        $sql = "UPDATE books SET title=?, author_id=?, description=?, category_id=?, file=? WHERE id=?";
        $stmt = $conn->prepare($sql);
        $res = $stmt->execute([$title, $author, $description, $category, $file['data'], $id]);

        if ($res) {
            $_SESSION['success'] = "Successfully updated!";
        } else {
            $_SESSION['error'] = "Unknown error occurred!";
        }
        header("Location: ../edit-book.php");
        exit;
    }

    // No file updates, just text updates
    $sql = "UPDATE books SET title=?, author_id=?, description=?, category_id=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $res = $stmt->execute([$title, $author, $description, $category, $id]);

    if ($res) {
        $_SESSION['success'] = "Successfully updated!";
    } else {
        $_SESSION['error'] = "Unknown error occurred!";
    }

    header("Location: ../edit-book.php");
    exit;
} else {
    $_SESSION['error'] = "Invalid request. Data missing.";
    header("Location: ../admin.php");
    exit;
}