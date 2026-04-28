<?php
session_start();

if (isset($_SESSION['user_id']) && isset($_SESSION['user_email'])) {
?>
  <!DOCTYPE html>
  <html lang="en">

  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Add Category – Online Book Store</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet" />

    <style>
      /* [ ... Existing CSS from admin.php here ... ] */
      :root {
        --gold: #ffd700;
        --green: #28a745;
        --blue-light: #1e90ff;
        --glass-bg: rgba(255, 255, 255, .1);
      }

      /* Base Styles (for all screen sizes, acts as desktop-first) */
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
        /* Space for potential footer or content below fold */
        display: flex;
        /* Added for vertical centering of the form */
        flex-direction: column;
        /* Added for vertical centering of the form */
        align-items: center;
        /* Added for vertical centering of the form */
        justify-content: center;
        /* Added for vertical centering of the form */
        padding-top: 70px;
        /* To prevent content from being under fixed navbar */
      }

      /* NAVBAR */
      .navbar {
        backdrop-filter: blur(10px);
        background: var(--glass-bg);
        border-bottom: 1px solid rgba(255, 255, 255, .12);
        position: fixed;
        /* Keep navbar fixed */
        top: 0;
        width: 100%;
        z-index: 1000;
      }

      .navbar-brand {
        color: var(--gold) !important;
        font-weight: 700;
        font-size: 1.4rem;
        /* Default size for larger screens */
      }

      .nav-link {
        color: #ddd !important;
        font-weight: 500;
        padding: 0.5rem 1rem;
        /* Default padding for larger screens */
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
        /* Adjusted for better visibility */
      }

      .flash .alert-danger {
        background-color: rgba(220, 53, 69, 0.7);
        /* Adjusted for better visibility */
      }

      /* Form Specific Styles */
      .add-category-form {
        background: rgba(255, 255, 255, 0.1);
        /* Glassmorphism background */
        border: 1px solid rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(10px);
        padding: 3rem;
        border-radius: 1rem;
        box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
        width: 100%;
        max-width: 500px;
        /* Adjusted max-width */
        color: #f0f0f0;
        /* Text color for the form */
      }

      .add-category-form .form-label {
        color: #ddd;
        font-weight: 500;
      }

      .add-category-form .form-control {
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.3);
        color: #fff;
        border-radius: 0.5rem;
        padding: 0.75rem 1rem;
      }

      .add-category-form .form-control::placeholder {
        color: rgba(255, 255, 255, 0.7);
      }

      .add-category-form .form-control:focus {
        background: rgba(255, 255, 255, 0.1);
        border-color: var(--gold);
        box-shadow: 0 0 0 0.25rem rgba(255, 215, 0, 0.25);
        color: #fff;
      }

      .add-category-form .btn-primary {
        background: var(--gold);
        color: #000;
        border: none;
        padding: 0.75rem 1.5rem;
        font-weight: 600;
        border-radius: 0.5rem;
        transition: background-color 0.3s ease, transform 0.2s ease;
        width: auto;
        /* Changed to auto to fit content */
      }

      .add-category-form .btn-primary:hover {
        background-color: #e6b800;
        /* Slightly darker gold */
        transform: translateY(-2px);
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

        .add-category-form {
          padding: 1.5rem;
        }

        .add-category-form h1 {
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
            <li class="nav-item"><a class="nav-link active" href="add-category.php">Add Category</a></li>
            <li class="nav-item"><a class="nav-link" href="add-author.php">Add Author</a></li>
            <li class="nav-item"><a class="nav-link" href=" feedback_list.php">Feedbacks</a></li>
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
      <form action="php/add-category.php" method="post" class="add-category-form">
        <h1 class="text-center mb-4 text-light">➕Add New Category</h1>

        <div class="mb-3">
          <label class="form-label">Category Name</label>
          <input type="text" class="form-control" name="category_name" autocomplete="off">
        </div>

        <button type="submit" class="btn btn-primary">Add Category</button>
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
<?php } else {
  header("Location: login.php");
  exit;
} ?>