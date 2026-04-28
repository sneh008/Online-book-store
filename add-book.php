<?php
session_start();

if (!isset($_SESSION['user_id'], $_SESSION['user_email'])) {
    header("Location: login.php");
    exit;
}

require "db_conn.php";
require "php/func-category.php";
require "php/func-author.php";

$categories = get_all_categories($conn);
$authors    = get_all_author($conn);

$old = $_SESSION['old'] ?? [];
$title       = $old['title']       ?? '';
$desc        = $old['description'] ?? ($old['desc'] ?? '');
$author_id   = $old['author_id']   ?? 0;
$category_id = $old['category_id'] ?? 0;
$book_cover  = $old['book_cover']  ?? '';
$book_file   = $old['book_file']   ?? '';

unset($_SESSION['old']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Add Book</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"/>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet"/>

  <style>
    :root {
      --gold: #ffd700;
      --green: #28a745;
      --blue-light: #1e90ff;
      --glass-bg: rgba(255, 255, 255, .1);
    }

    body {
      background: linear-gradient(135deg, #0f2027, #203a43, #2c5364);
      color: #fff;
      margin: 0;
      min-height: 100vh;
      padding-bottom: 4rem;
    }

    .navbar {
      backdrop-filter: blur(10px);
      background: var(--glass-bg);
      border-bottom: 1px solid rgba(255, 255, 255, .12);
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

    .add-book-form {
      background: rgba(255,255,255,0.08);
      padding: 3rem;
      border-radius: 1rem;
      backdrop-filter: blur(12px);
      box-shadow: 0 20px 40px rgba(0,0,0,.4);
      width: 100%;
      max-width: 700px;
      color: #fff;
    }

    .form-control {
      background-color: rgba(255,255,255,0.05);
      border: none;
      color: #fff;
    }

    .form-control::placeholder {
      color: rgba(255,255,255,0.6);
    }

    .btn-primary {
      background: linear-gradient(to right,#FFD700,#FFA500);
      border: none;
      padding: .9rem;
      font-weight: bold;
      border-radius: .75rem;
      transition: .3s;
      width: 100%;
      color: #000;
    }

    .btn-primary:hover {
      background: linear-gradient(to left,#FFD700,#FFA500);
      transform: translateY(-2px);
    }

    .custom-alert {
      border-radius: .75rem;
      padding: 1rem 1.5rem;
      margin-bottom: 1.5rem;
      display: flex;
      align-items: center;
      font-weight: 500;
      animation: fadeIn .5s ease-out;
      transition: opacity .5s;
    }

    .custom-alert-danger {
      background: rgba(255,107,107,.15);
      color: #ff6b6b;
      border: 1px solid rgba(255,107,107,.4);
    }

    .custom-alert-success {
      background: rgba(40,167,69,0.2);
      color: #28a745;
      border: 1px solid rgba(40,167,69,0.4);
    }

    .custom-alert .icon {
      margin-right: 1rem;
      font-size: 1.2rem;
    }

    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(-10px); }
      to { opacity: 1; transform: translateY(0); }
    }
  </style>
</head>
<body>
  <!-- ===== NAVBAR ===== -->
  <nav class="navbar navbar-expand-lg navbar-dark px-4 py-3">
    <div class="container-fluid">
      <a class="navbar-brand" href="admin.php">📚 Admin Panel</a>
      <button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#nav"><span class="navbar-toggler-icon"></span></button>
      <div class="collapse navbar-collapse" id="nav">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item"><a class="nav-link" href="admin.php">Home</a></li>
          <li class="nav-item"><a class="nav-link" href="index.php">User view</a></li>
          <li class="nav-item"><a class="nav-link active" href="add-book.php">Add Book</a></li>
          <li class="nav-item"><a class="nav-link" href="add-category.php">Add Category</a></li>
          <li class="nav-item"><a class="nav-link" href="add-author.php">Add Author</a></li>
          <li class="nav-item"><a class="nav-link" href="feedback_list.php">Feedbacks</a></li>
          <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
        </ul>
      </div>
    </div>
  </nav>

  <div class="container d-flex flex-column align-items-center mt-4">
    <form action="php/add-book.php" method="post" enctype="multipart/form-data" class="add-book-form">
      <h1 class="text-center mb-4">Add New Book</h1>

      <?php if (!empty($_SESSION['error'])): ?>
        <div class="custom-alert custom-alert-danger">
          <i class="fas fa-times-circle icon"></i>
          <span><?= htmlspecialchars($_SESSION['error']) ?></span>
        </div>
        <?php unset($_SESSION['error']); ?>
      <?php endif; ?>

      <?php if (!empty($_SESSION['success'])): ?>
        <div class="custom-alert custom-alert-success">
          <i class="fas fa-check-circle icon"></i>
          <span><?= htmlspecialchars($_SESSION['success']) ?></span>
        </div>
        <?php unset($_SESSION['success']); ?>
      <?php endif; ?>

      <div class="mb-3">
        <label class="form-label">Book Title</label>
        <input type="text" class="form-control" name="book_title" value="<?= htmlspecialchars($title) ?>">
      </div>

      <div class="mb-3">
        <label class="form-label">Book Description</label>
        <textarea class="form-control" name="book_description" rows="3"><?= htmlspecialchars($desc) ?></textarea>
      </div>


      <div class="mb-3">
        <label class="form-label">Book Author</label>
        <select name="book_author" class="form-control">
          <option value="0">Select author</option>
          <?php if ($authors): foreach ($authors as $a): ?>
            <option value="<?= $a['id'] ?>" <?= $a['id']==$author_id ? 'selected' : '' ?>>
              <?= htmlspecialchars($a['name']) ?>
            </option>
          <?php endforeach; endif; ?>
        </select>
      </div>

      <div class="mb-3">
        <label class="form-label">Book Category</label>
        <select name="book_category" class="form-control">
          <option value="0">Select category</option>
          <?php if ($categories): foreach ($categories as $c): ?>
            <option value="<?= $c['id'] ?>" <?= $c['id']==$category_id ? 'selected' : '' ?>>
              <?= htmlspecialchars($c['name']) ?>
            </option>
          <?php endforeach; endif; ?>
        </select>
      </div>

      <div class="mb-3">
        <label class="form-label">Book Cover</label>
        <input type="file" class="form-control" name="book_cover" <?= $book_cover ? 'disabled' : '' ?>>
        <?php if ($book_cover): ?>
          <div class="form-text text-success">
            ✅ Cover already uploaded: <strong><?= htmlspecialchars($book_cover) ?></strong>
          </div>
          <input type="hidden" name="book_cover_existing" value="<?= htmlspecialchars($book_cover) ?>">
        <?php endif; ?>
      </div>

      <div class="mb-3">
        <label class="form-label">Book File (PDF, DOCX, etc.)</label>
        <input type="file" class="form-control" name="file" <?= $book_file ? 'disabled' : '' ?>>
        <?php if ($book_file): ?>
          <div class="form-text text-success">
            ✅ File already uploaded: <strong><?= htmlspecialchars($book_file) ?></strong>
          </div>
          <input type="hidden" name="book_file_existing" value="<?= htmlspecialchars($book_file) ?>">
        <?php endif; ?>
      </div>

      <button type="submit" class="btn btn-primary">Add Book</button>
    </form>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', () => {
      document.querySelectorAll('.custom-alert').forEach(alert => {
        setTimeout(() => {
          alert.style.opacity = '0';
          setTimeout(() => alert.remove(), 500);
        }, 2500);
      });
    });
  </script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
