<?php
session_start();

/* ───────── 1. VALIDATE SEARCH KEY ───────── */
if (!isset($_GET['key']) || trim($_GET['key']) === '') {
    header("Location: index.php");
    exit;
}
$key = $_GET['key'];

/* ───────── 2. DATABASE + HELPERS ───────── */
include "db_conn.php";
include "php/func-book.php";
include "php/func-author.php";
include "php/func-category.php";

$books      = search_books($conn, $key);
$authors    = get_all_author($conn);
$categories = get_all_categories($conn);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Search “<?= htmlspecialchars($key) ?>” – Online Book Store</title>

    <!-- Bootstrap + Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet" />

    <!-- ===== DARK‑GLASS THEME (identical to category/author) ===== -->
    <style>
        :root {
            --gold: #ffd700;
            --green: #28a745;
            --blue-light: #1e90ff;
            --glass-bg: rgba(255, 255, 255, .1);
        }

        * { box-sizing: border-box; font-family: 'Segoe UI', sans-serif; }

        body {
            background: linear-gradient(135deg, #0f2027, #203a43, #2c5364);
            color: #fff;
            margin: 0;
            min-height: 100vh;
            padding-bottom: 3rem;
        }

        /* ─── NAVBAR ─── */
        .navbar {
            backdrop-filter: blur(10px);
            background: var(--glass-bg);
            border-bottom: 1px solid rgba(255, 255, 255, .12);
        }
        .navbar-brand { color: var(--gold) !important; font-weight: 700; }
        .nav-link     { color: #ddd !important; font-weight: 500; }
        .nav-link:hover { color: #fff !important; }

        /* ─── PAGE TITLE ─── */
        .page-title {
            font-size: 2rem;
            font-weight: 700;
            color: var(--gold);
            margin: 2rem 0 1.25rem;
            display: flex;
            align-items: center;
            gap: .75rem;
        }

        /* ─── FILTERS / SEARCH BAR ─── */
        .filters {
            max-width: 960px;
            margin: 0 auto 2.5rem;
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
        }
        .filters select,
        .filters .search-input {
            flex: 1 1 280px;
            padding: .7rem 1rem;
            border-radius: 10px;
            border: none;
            background: var(--glass-bg);
            color: #fff;
            backdrop-filter: blur(8px);
        }
        .filters .search-btn {
            background: var(--gold);
            color: #000;
            font-weight: 600;
            border: none;
            border-radius: 10px;
            padding: .7rem 1.25rem;
        }

        /* ─── GRID + CARDS ─── */
        .grid {
            display: grid;
            gap: 2rem;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            padding: 0 1rem;
        }
        .card {
            background: var(--glass-bg);
            backdrop-filter: blur(8px);
            border: none;
            border-radius: 20px;
            overflow: hidden;
            transition: transform .25s;
        }
        .card:hover { transform: translateY(-5px); }
        .card img   { height: 250px; object-fit: cover; }
        .card-body  { color: #fff; padding: 1rem 1.25rem; }
        .card-title { font-size: 1.15rem; font-weight: 700; }
        .card-text  { font-size: .9rem; margin-bottom: 1.1rem; }
        .card-footer{
            padding: .8rem 1.25rem;
            border-top: 1px solid rgba(255,255,255,.15);
            display: flex; justify-content: space-between;
            background: none;
        }
        .btn-open { background: var(--green); color:#fff; font-size:.8rem; border:none; padding:.4rem 1rem; border-radius:20px; }
        .btn-dl   { background: var(--blue-light); color:#fff; font-size:.8rem; border:none; padding:.4rem 1rem; border-radius:20px; }

        /* ─── EMPTY STATE ─── */
        .empty { text-align:center; margin:3rem 0; color:#eee; }

        footer { text-align:center; color:#bbb; font-size:.9rem; margin-top:4rem; }

        @media(max-width:576px){ .page-title{font-size:1.6rem;} }

        /* tweak default select look */
        select.form-select,
        .search-input { background-color: rgba(255,255,255,0.1); color:#fff; border:1px solid rgba(255,255,255,0.2); }
        select.form-select option{ background-color:#0A1931; color:#fff; }
    </style>
</head>

<body>

    <!-- ─── NAVBAR ─── -->
   <nav class="navbar navbar-expand-lg navbar-dark px-4 py-3">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">📚 Online Book Store</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="feedback.php">Feedback</a></li>
                    <li class="nav-item"><a class="nav-link" href="about.php">About</a></li>
                    <li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- ─── PAGE TITLE ─── -->
    <div class="container">
        <h1 class="page-title">
            <a href="index.php"><i class="fas fa-arrow-left"></i></a>
            Search: “<?= htmlspecialchars($key) ?>”
        </h1>
    </div>

    <!-- ─── SEARCH BAR + FILTERS ─── -->
    <div class="filters">
        <!-- search input -->
        <form action="search.php" method="get" class="d-flex flex-grow-1" style="flex:1; gap:8px;">
            <input type="text" name="key" value="<?= htmlspecialchars($key) ?>" class="search-input" placeholder="Search books…">
            <button class="search-btn"><i class="fas fa-search"></i></button>
        </form>

        <!-- category dropdown -->
        <select class="form-select" onchange="if(this.value)location.href=this.value">
            <option disabled>-- Choose Category --</option>
            <option value="index.php">All Categories</option>
            <?php foreach ($categories as $cat) { ?>
                <option value="category.php?id=<?= $cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></option>
            <?php } ?>
        </select>

        <!-- author dropdown -->
        <select class="form-select" onchange="if(this.value)location.href=this.value">
            <option value="">All Authors</option>
            <?php foreach ($authors as $a) { ?>
                <option value="author.php?id=<?= $a['id'] ?>"><?= htmlspecialchars($a['name']) ?></option>
            <?php } ?>
        </select>
    </div>

    <!-- ─── RESULTS GRID ─── -->
    <div class="container">
        <?php if (empty($books)) { ?>
            <div class="empty">
                <i class="fas fa-search-minus fa-4x mb-3"></i>
                <h4>No matches for “<?= htmlspecialchars($key) ?>”.</h4>
            </div>
        <?php } else { ?>
            <div class="grid">
                <?php foreach ($books as $bk) {
                    /* look up author + category names once per book */
                    $authorName = '';
                    $catName    = '';
                    foreach ($authors as $a) {
                        if ($a['id'] == $bk['author_id']) { $authorName = $a['name']; break; }
                    }
                    foreach ($categories as $c) {
                        if ($c['id'] == $bk['category_id']) { $catName = $c['name']; break; }
                    }
                ?>
                    <div class="card">
                        <img src="uploads/cover/<?= htmlspecialchars($bk['cover']) ?>" alt="Cover of <?= htmlspecialchars($bk['title']) ?>">
                        <div class="card-body">
                            <div class="card-title"><?= htmlspecialchars($bk['title']) ?></div>
                            <div class="card-text">
                                <b>By:</b> <?= htmlspecialchars($authorName) ?><br>
                                <b>Category:</b> <?= htmlspecialchars($catName) ?>
                            </div>
                        </div>
                        <div class="card-footer">
                            <a class="btn-open" href="uploads/files/<?= htmlspecialchars($bk['file']) ?>" target="_blank">Open</a>
                            <a class="btn-dl"   href="uploads/files/<?= htmlspecialchars($bk['file']) ?>" download>Download</a>
                        </div>
                    </div>
                <?php } ?>
            </div>
        <?php } ?>
    </div>

    <footer>&copy; <?= date('Y') ?> Online Book Store. All rights reserved.</footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
