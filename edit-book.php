<?php
session_start();

if (!isset($_SESSION['user_id'], $_SESSION['user_email'])) {
    header("Location: login.php");
    exit;
}

if (!isset($_GET['id']) && !isset($_SESSION['edit_id'])) {
    header("Location: admin.php");
    exit;
}

// Prefer GET ID, but fall back to session if set (e.g., after a redirect from processing)
$id = $_GET['id'] ?? $_SESSION['edit_id'] ?? null;

// If ID is not valid or not found, redirect
if (empty($id)) {
    header("Location: admin.php");
    exit;
}

// Unset session ID to prevent re-using it on subsequent page loads without a new GET request
unset($_SESSION['edit_id']);

include "db_conn.php";
include "php/func-book.php";
$book = get_book($conn, $id);

if ($book == 0) { // Check if book was actually found
    header("Location: admin.php");
    exit;
}

include "php/func-category.php";
$categories = get_all_categories($conn);
include "php/func-author.php";
$authors = get_all_author($conn);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Edit Book – Online Book Store</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet" />

    <style>
        /* CSS from admin.php and other admin-facing pages */
        :root {
            --gold: #ffd700;
            --green: #28a745;
            --blue-light: #1e90ff;
            --glass-bg: rgba(255, 255, 255, .1);
        }

        * {
            box-sizing: border-box;
            font-family: 'Segoe UI', sans-serif;
        }

        body {
            background: linear-gradient(135deg, #0f2027, #203a43, #2c5364);
            color: #fff;
            margin: 0;
            min-height: 100vh;
            padding-bottom: 4rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding-top: 70px;
            /* To prevent content from being under fixed navbar */
        }

        /* NAVBAR */
        .navbar {
            backdrop-filter: blur(10px);
            background: var(--glass-bg);
            border-bottom: 1px solid rgba(255, 255, 255, .12);
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 1000;
        }

        .navbar-brand {
            color: var(--gold) !important;
            font-weight: 700;
            font-size: 1.4rem;
        }

        .nav-link {
            color: #ddd !important;
            font-weight: 500;
            padding: 0.5rem 1rem;
        }

        .nav-link:hover {
            color: #fff !important;
        }

        /* FLASH MSG */
        .flash {
            position: fixed;
            top: 1rem;
            right: 1rem;
            z-index: 2000;
        }

        .flash .alert {
            padding: .7rem 1.2rem;
            backdrop-filter: blur(6px);
            border: 0;
            color: #fff;
            background-color: rgba(40, 167, 69, 0.7);
        }

        .flash .alert-danger {
            background-color: rgba(220, 53, 69, 0.7);
        }

        /* Form Specific Styles */
        .edit-book-form {
            /* Changed class name for clarity */
            background: rgba(255, 255, 255, 0.1);
            /* Glassmorphism background */
            border: 1px solid rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            padding: 3rem;
            border-radius: 1rem;
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
            width: 100%;
            max-width: 700px;
            color: #f0f0f0;
        }

        .edit-book-form .form-label {
            color: #ddd;
            font-weight: 500;
        }

        .edit-book-form .form-control,
        .edit-book-form select.form-control,
        .edit-book-form textarea.form-control {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.3);
            color: #fff;
            border-radius: 0.5rem;
            padding: 0.75rem 1rem;
        }

        .edit-book-form .form-control::placeholder {
            color: rgba(255, 255, 255, 0.7);
        }

        .edit-book-form .form-control:focus,
        .edit-book-form select.form-control:focus,
        .edit-book-form textarea.form-control:focus {
            background: rgba(255, 255, 255, 0.1);
            border-color: var(--gold);
            box-shadow: 0 0 0 0.25rem rgba(255, 215, 0, 0.25);
            color: #fff;
        }

        .edit-book-form select.form-control option {
            background-color: #0f2027;
            /* Dark background for dropdown options */
            color: #fff;
        }

        .edit-book-form .btn-primary {
            background: var(--gold);
            color: #000;
            border: none;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            border-radius: 0.5rem;
            transition: background-color 0.3s ease, transform 0.2s ease;
            width: 100%;
        }

        .edit-book-form .btn-primary:hover {
            background-color: #e6b800;
            transform: translateY(-2px);
        }

        .edit-book-form .btn-outline-secondary {
            color: #ddd;
            border-color: #ddd;
            margin-bottom: 20px;
            /* Space below button */
            transition: all 0.3s ease;
        }

        .edit-book-form .btn-outline-secondary:hover {
            background-color: rgba(255, 255, 255, 0.2);
            color: #fff;
            border-color: #fff;
        }

        .edit-book-form .link-dark {
            /* Styling for "Current Cover/File" links */
            color: var(--gold) !important;
            text-decoration: none;
            font-weight: 500;
            display: inline-block;
            margin-top: 0.5rem;
        }

        .edit-book-form .link-dark:hover {
            text-decoration: underline;
            color: #e6b800 !important;
        }


        /* Adjustments for small screens */
        @media (max-width: 576px) {
            .navbar-brand {
                font-size: 1.1rem;
            }

            .nav-link {
                font-size: 0.85rem;
                padding: 0.3rem 0.6rem;
            }

            .flash {
                top: .5rem;
                right: .5rem;
                left: .5rem;
                width: auto;
            }

            .flash .alert {
                font-size: 0.9rem;
                text-align: center;
                padding: .6rem 1rem;
            }

            .edit-book-form {
                padding: 1.5rem;
            }

            .edit-book-form h1 {
                font-size: 1.8rem;
            }
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark px-4 py-3">
        <div class="container-fluid">
            <a class="navbar-brand" href="admin.php">📚 Admin Panel</a>
            <button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#nav"><span class="navbar-toggler-icon"></span></button>
            <div class="collapse navbar-collapse" id="nav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="admin.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="index.php">User view</a></li>
                    <li class="nav-item"><a class="nav-link" href="add-book.php">Add Book</a></li>
                    <li class="nav-item"><a class="nav-link" href="add-category.php">Add Category</a></li>
                    <li class="nav-item"><a class="nav-link" href="add-author.php">Add Author</a></li>
                    <li class="nav-item"><a class="nav-link" href="feedbacks.php">Feedbacks</a></li>
                    <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <?php if (isset($_SESSION['success']) || isset($_SESSION['error'])): ?>
        <div class="flash">
            <div class="alert <?= isset($_SESSION['success']) ? 'alert-success' : 'alert-danger' ?> shadow rounded-pill">
                <?= $_SESSION['success'] ?? $_SESSION['error'];
                unset($_SESSION['success'], $_SESSION['error']); ?>
            </div>
        </div>
    <?php endif; ?>

    <div class="container d-flex flex-column align-items-center">
        <form action="php/edit-book.php" method="post" enctype="multipart/form-data" class="edit-book-form">
            <a href="admin.php" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left"></i> Back
            </a>

            <h1 class="text-center mb-4 text-light">Edit Book</h1>

            <div class="mb-3">
                <label class="form-label">Book Title</label>
                <input type="hidden" name="book_id" value="<?= $book['id'] ?>">
                <input type="text" class="form-control" name="book_title" value="<?= htmlspecialchars($book['title']) ?>" autocomplete="off">
            </div>

            <div class="mb-3">
                <label class="form-label">Book Description</label>
                <textarea name="book_description" class="form-control" rows="5"><?= htmlspecialchars($book['description']) ?></textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Book Author</label>
                <select name="book_author" class="form-control">
                    <option value="0">Select author</option>
                    <?php foreach ($authors as $a): ?>
                        <option value="<?= $a['id'] ?>" <?= $a['id'] == $book['author_id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($a['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Book Category</label>
                <select name="book_category" class="form-control">
                    <option value="0">Select category</option>
                    <?php foreach ($categories as $c): ?>
                        <option value="<?= $c['id'] ?>" <?= $c['id'] == $book['category_id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($c['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Book Cover</label>
                <input type="file" class="form-control" name="book_cover" accept="image/*">
                <input type="hidden" name="current_cover" value="<?= htmlspecialchars($book['cover']) ?>">
                <?php if (!empty($book['cover'])): ?>
                    <a href="uploads/cover/<?= htmlspecialchars($book['cover']) ?>" class="link-dark" target="_blank">Current Cover: <?= htmlspecialchars($book['cover']) ?></a>
                <?php else: ?>
                    <p class="text-muted mt-2">No cover uploaded yet.</p>
                <?php endif; ?>
            </div>

            <div class="mb-3">
                <label class="form-label">Book File (PDF, DOCX, etc.)</label>
                <input type="file" class="form-control" name="file" accept=".pdf, .doc, .docx, .ppt, .pptx, .txt">
                <input type="hidden" name="current_file" value="<?= htmlspecialchars($book['file']) ?>">
                <?php if (!empty($book['file'])): ?>
                    <a href="uploads/files/<?= htmlspecialchars($book['file']) ?>" class="link-dark" target="_blank">Current File: <?= htmlspecialchars($book['file']) ?></a>
                <?php else: ?>
                    <p class="text-muted mt-2">No file uploaded yet.</p>
                <?php endif; ?>
            </div>

            <button type="submit" class="btn btn-primary">Edit Book</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Flash message auto-hide logic (from admin.php)
        setTimeout(() => {
            const flashMessage = document.querySelector('.flash');
            if (flashMessage) {
                flashMessage.remove();
            }
        }, 2500);

        // Auto-scroll to top for flash message on small screens (from admin.php)
        if (window.innerWidth < 768 && document.querySelector('.flash')) {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        }
    </script>
</body>

</html>