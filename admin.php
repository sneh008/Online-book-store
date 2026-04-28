<?php
session_start();
if (!isset($_SESSION['user_id'], $_SESSION['user_email'])) {
    header("Location: login.php");
    exit;
}

require "db_conn.php";
require "php/func-book.php";
require "php/func-author.php";
require "php/func-category.php";

$books      = get_all_books($conn);
$authors    = get_all_author($conn);
$categories = get_all_categories($conn);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1.0" />
    <title>Admin Dashboard – Online Book Store</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">


    <style>
        :root {
            --gold: #ffd700;
            --green: #28a745;
            --blue-light: #1e90ff;
            --glass-bg: rgba(255, 255, 255, .1);
            --danger-red: #dc3545; /* Added for delete button consistency */
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
            padding-top: 70px; /* To prevent content from being under fixed navbar */
        }

        /* NAVBAR */
        .navbar {
            backdrop-filter: blur(10px); /* Adjusted blur for better readability */
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

        /* PAGE HEADING */
        h2.section-title {
            color: var(--gold);
            font-size: 1.8rem;
            font-weight: 700;
            margin: 3rem 0 1.3rem;
        }

        /* TABLE */
        .glass-table {
            width: 100%;
            color: #fff;
            border-collapse: collapse;
            backdrop-filter: blur(8px);
            background: rgba(255, 255, 255, 0.05); /* Slightly less transparent for table body */
            border: 1px solid rgba(255, 255, 255, 0.2); /* Added border to match feedbacks.php */
            border-radius: 16px;
            overflow: hidden;
            /* Ensures border-radius applies to content */
            min-width: 700px;
            /* Forces horizontal scroll on smaller screens if content exceeds */
        }

        .glass-table thead {
            background: rgba(0, 0, 0, 0.3); /* Darker header for contrast */
        }

        .glass-table th {
            padding: 1rem;
            font-weight: 700;
            color: var(--gold);
            border-bottom: 1px solid rgba(255, 255, 255, .15);
            text-align: left;
            /* Default text alignment */
        }

        .glass-table td {
            padding: 1rem;
            border-bottom: 1px solid rgba(255, 255, 255, .08);
            vertical-align: middle;
            /* Align content vertically in the middle */
        }
        
        /* Consistent row styling as feedbacks.php */
        .glass-table tbody tr {
            background-color: rgba(255, 255, 255, 0.02); /* Default (odd) row background - very subtle */
        }

        .glass-table tbody tr:nth-child(even) {
            background-color: rgba(255, 255, 255, 0.04); /* Even row background - slightly darker */
        }

        .glass-table tr:hover {
            background: rgba(255, 255, 255, .12); /* Increased hover effect for better visibility */
        }

        .glass-table td img {
            width: 60px;
            height: 80px;
            border-radius: 8px;
            margin-right: 8px;
            object-fit: cover;
            /* Ensures image covers the area without distortion */
            display: inline-block; /* Ensure image and text are on the same line */
            vertical-align: middle; /* Align image vertically with text */
        }

        .glass-table td a.text-light {
            display: inline-block;
            vertical-align: middle;
        }

        /* BADGES + BUTTONS */
        .btn-action {
            padding: .4rem .8rem;
            font-size: .85rem;
            border: 0;
            border-radius: 12px;
            text-decoration: none;
            display: inline-flex;
            /* Use flexbox for icon/text alignment */
            align-items: center;
            justify-content: center;
            gap: 6px;
            /* Space between icon and text */
            transition: opacity .2s ease, transform .2s ease;
        }

        .btn-edit {
            background: var(--blue-light);
            color: #fff;
        }

        /* Using var(--danger-red) for consistency */
        .btn-danger-custom {
            background: var(--danger-red);
            color: #fff;
        }

        .btn-action:hover {
            opacity: .9;
            transform: translateY(-1px);
        }

        /* FLASH MSG */
        .flash {
            position: fixed;
            top: 1rem;
            right: 1rem;
            z-index: 2000;
            width: 90%;
            max-width: 400px;
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

        .flash .alert.hide {
            opacity: 0;
            transform: translateY(-20px);
            transition: opacity 0.5s ease-out, transform 0.5s ease-out;
        }

        /* SEARCH */
        .search-box {
            max-width: 24rem;
            margin: 2rem auto;
            padding: 0 1rem;
            /* Add some horizontal padding for smaller screens */
        }

        .search-box .form-control,
        .search-box .btn {
            border-radius: 20px;
            border: 0;
        }

        .search-box .form-control {
            background: var(--glass-bg);
            color: #fff;
            padding-left: 1rem;
            /* Ensure text doesn't hit the edge */
        }

        .search-box .form-control::placeholder {
            color: rgba(255, 255, 255, 0.7);
        }

        .search-box .btn {
            background: var(--gold);
            color: #000;
            font-weight: 600;
        }

        /* SCROLL BTN */
        #topBtn {
            position: fixed;
            bottom: 30px;
            right: 30px;
            display: none;
            /* Hidden by default, shown by JS */
            background: var(--gold);
            border: 0;
            width: 48px;
            height: 48px;
            border-radius: 50%;
            color: #000;
            font-weight: 700;
            font-size: 1.2rem;
            cursor: pointer;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
            transition: background-color 0.3s ease;
            z-index: 999; /* Ensure it's above other content but below navbar/modal */
        }

        #topBtn:hover {
            background-color: #e6b800;
            /* Slightly darker gold on hover */
        }


        /* Make tables horizontally scrollable on small screens */
        .table-responsive {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            /* Smooth scrolling for iOS */
        }

        /* DESCRIPTION READ MORE/LESS */
        .read-more {
            color: var(--gold);
            cursor: pointer;
            font-size: 0.85rem;
            text-decoration: underline;
            display: inline-block;
            margin-top: 4px;
        }

        .desc-short,
        .desc-full {
            display: block;
            white-space: normal;
            word-break: break-word;
        }

        .desc-full.d-none {
            display: none;
        }

        /* ========================================= */
        /* Modal Styling (Integrated from feedbacks.php) */
        /* ========================================= */
        .modal-content {
            background: rgba(15, 32, 39, 0.9);
            /* Darker glass background for modal */
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 1rem;
            color: #fff;
        }

        .modal-header {
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            /* Subtle border for header */
            padding-bottom: 1rem; /* Adjust padding if needed */
        }

        .modal-footer {
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            /* Subtle border for footer */
            padding-top: 1rem; /* Adjust padding if needed */
        }

        .modal-title {
            color: var(--gold);
            /* Uses your gold custom property */
        }

        .btn-close {
            filter: invert(1) grayscale(100%) brightness(200%);
            /* Make close button white */
        }

        /* Buttons within modal */
        .modal-footer .btn-secondary {
            background: #6c757d; /* Standard grey */
            border: none;
            color: #fff;
            transition: background-color 0.2s ease;
        }
        .modal-footer .btn-secondary:hover {
            background-color: #5a6268;
        }
        .modal-footer .btn-danger-custom {
            background: var(--danger-red);
            border: none;
            color: #fff;
            transition: background-color 0.2s ease;
        }
        .modal-footer .btn-danger-custom:hover {
            background-color: #c82333;
        }


        /* ========================================= */
        /* Media Queries for Responsiveness */
        /* ========================================= */

        /* Tablet and Smaller Desktops */
        @media (max-width: 992px) {
            .navbar-brand {
                font-size: 1.25rem;
            }

            .nav-link {
                padding: 0.4rem 0.8rem;
            }
        }


        /* Tablets and Landscape Phones */
        @media (max-width: 768px) {
            h2.section-title {
                font-size: 1.5rem;
                /* Slightly smaller heading */
                margin-top: 2.5rem;
            }

            /* Table adjustments */
            .glass-table th,
            .glass-table td {
                padding: 0.8rem 0.6rem;
                /* Reduced padding */
                font-size: 0.9rem;
                /* Smaller font size for table content */
            }

            .glass-table td img {
                width: 50px;
                height: 65px;
            }

            /* Action Buttons (icons + text) */
            .btn-action {
                padding: .35rem .6rem;
                font-size: 0.75rem;
                gap: 4px;
                /* Reduced gap */
            }

            .btn-action i {
                font-size: 0.85rem;
                display: inline-block;
                /* Ensure icon is always shown */
            }

            .btn-action span {
                display: inline-block;
                /* Ensure text is always shown */
            }

            .search-box {
                margin-top: 1.5rem;
                padding: 0 15px;
                /* More padding on the sides */
            }

            /* Flash message position adjustment */
            .flash {
                top: .8rem;
                right: .8rem;
            }
        }

        /* Smaller Mobile Devices (Portrait) */
        @media (max-width: 576px) {
            .navbar-brand {
                font-size: 1.1rem;
                /* Smaller brand text */
            }

            .navbar-toggler {
                padding: 0.25rem 0.5rem;
                /* Smaller toggler button */
                font-size: 0.9rem;
            }

            .navbar-nav {
                text-align: center;
                /* Center nav items when collapsed */
                margin-top: 0.5rem;
            }

            .nav-item {
                margin-bottom: 0.2rem;
            }

            .nav-link {
                font-size: 0.85rem;
                padding: 0.3rem 0.6rem;
            }

            h2.section-title {
                font-size: 1.3rem;
                /* Even smaller heading */
                margin-top: 2rem;
                text-align: center;
            }

            .glass-table th,
            .glass-table td {
                padding: 0.6rem 0.4rem;
                /* Further reduced padding */
                font-size: 0.8rem;
                /* Smaller table text */
            }

            /* Specific column truncation for very small screens */
            .glass-table td:nth-child(3),
            /* Author */
            .glass-table td:nth-child(4),
            /* Description */
            .glass-table td:nth-child(5) {
                /* Category */
                max-width: 90px;
                /* Reduced max-width to fit more columns */
                overflow: hidden;
                text-overflow: ellipsis;
                white-space: nowrap;
            }

            /* Action Buttons (icon only) for very small screens */
            .btn-action {
                padding: .3rem .5rem;
                /* Smaller padding */
                font-size: 0.7rem;
                gap: 0;
                /* No gap */
            }

            .btn-action span {
                display: none;
                /* Hide text, only show icon */
            }

            .search-box {
                padding: 0 10px;
                /* Minimal padding */
            }

            .flash {
                top: .5rem;
                right: .5rem;
                left: .5rem;
                /* Allow it to span more of the width */
                width: auto;
            }

            .flash .alert {
                font-size: 0.9rem;
                text-align: center;
                padding: .6rem 1rem;
            }

            #topBtn {
                width: 40px;
                height: 40px;
                font-size: 1rem;
                bottom: 20px;
                right: 20px;
            }
            .read-more {
                display: block;
                margin-top: 6px;
                font-size: 0.8rem;
            }
        }

        /* Extra small mobile devices (e.g., iPhone 5/SE) */
        @media (max-width: 375px) {
            .navbar-brand {
                font-size: 1rem;
            }

            .nav-link {
                font-size: 0.8rem;
            }

            h2.section-title {
                font-size: 1.2rem;
            }

            .glass-table th,
            .glass-table td {
                font-size: 0.75rem;
                padding: 0.5rem 0.3rem;
            }

            .glass-table td img {
                width: 40px;
                height: 55px;
                margin-right: 4px;
            }

            .btn-action {
                padding: .25rem .4rem;
                font-size: 0.65rem;
            }
        }
        .glass-table td span.vote-count {
    font-weight: 700;
    color: var(--gold);
    display: inline-block;
    min-width: 24px;
    text-align: center;
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

    <form class="search-box" action="search.php" method="get">
        <div class="input-group">
            <input class="form-control" name="key" placeholder="Search book…" autocomplete="off">
            <button class="btn" type="submit"><i class="fas fa-search"></i></button>
        </div>
    </form>

    <div class="container-fluid px-lg-5">
        <h2 class="section-title">📚 All Books</h2>
        <?php if (!$books): ?>
            <p class="text-center">No books yet.</p>
        <?php else: ?>
            <div class="table-responsive mb-5">
                <table class="glass-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Cover &amp; Title</th>
                            <th>Author</th>
                            <th>Description</th>
                            <th>Category</th>
                            <th>Like</th>
                            <th>Dislike</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i = 0;
                        foreach ($books as $b): $i++; ?>
                            <tr>
                                <td><?= $i ?></td>
                                <td class="align-items-center gap-2"> <img src="uploads/cover/<?= $b['cover'] ?>" alt="cover">
                                    <a href="uploads/files/<?= $b['file'] ?>" target="_blank" class="text-decoration-none text-light flex-grow-1">
                                        <?= htmlspecialchars($b['title']) ?>
                                    </a>
                                </td>
                                <td><?php foreach ($authors as $a) if ($a['id'] == $b['author_id']) echo htmlspecialchars($a['name']); ?></td>
                                <td style="max-width:240px">
                                    <span class="desc-short">
                                        <?= htmlspecialchars(mb_strimwidth($b['description'], 0, 120, '…')) ?>
                                    </span>
                                    <span class="desc-full d-none">
                                        <?= htmlspecialchars($b['description']) ?>
                                    </span>
                                    <?php if (strlen($b['description']) > 120): ?>
                                        <span class="read-more">Read more</span>
                                    <?php endif; ?>
                                </td>
                                <td><?php foreach ($categories as $c) if ($c['id'] == $b['category_id']) echo htmlspecialchars($c['name']); ?></td>
                                <<td><span class="vote-count"><?= intval($b['likes'] ?? 0) ?></span></td>
                                <td><span class="vote-count"><?= intval($b['dislikes'] ?? 0) ?></span></td>


                                <td>
                                    <a href="edit-book.php?id=<?= $b['id'] ?>" class="btn-action btn-edit" title="Edit"><i class="fas fa-pen"></i> <span>Edit</span></a>
                                    <button class="btn-action btn-danger-custom" title="Delete"
                                        data-bs-toggle="modal"
                                        data-bs-target="#confirmDeleteModal"
                                        data-delete-url="php/delete-book.php?id=<?= $b['id'] ?>"
                                        data-type="book"
                                        data-name="<?= htmlspecialchars($b['title']) ?>">
                                        <i class="fas fa-trash-alt"></i> <span>Delete</span>
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>

        <h2 class="section-title">🏷️ All Categories</h2>
        <div class="table-responsive mb-5">
            <table class="glass-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!$categories): ?>
                        <tr>
                            <td colspan="3" class="text-center p-4">No categories.</td>
                        </tr>
                        <?php else: $i = 0;
                        foreach ($categories as $cat): $i++; ?>
                            <tr>
                                <td><?= $i ?></td>
                                <td><?= htmlspecialchars($cat['name']) ?></td>
                                <td>
                                    <a href="edit-category.php?id=<?= $cat['id'] ?>" class="btn-action btn-edit"><i class="fas fa-pen"></i>Edit</a>
                                    <button class="btn-action btn-danger-custom"
                                        data-bs-toggle="modal"
                                        data-bs-target="#confirmDeleteModal"
                                        data-delete-url="php/delete-category.php?id=<?= $cat['id'] ?>"
                                        data-type="category"
                                        data-name="<?= htmlspecialchars($cat['name']) ?>">
                                        <i class="fas fa-trash-alt"></i> <span>Delete</span>
                                    </button>

                                </td>
                            </tr>
                        <?php endforeach;
                        endif; ?>
                </tbody>
            </table>
        </div>

        <h2 class="section-title">✍️ All Authors</h2>
        <div class="table-responsive mb-5">
            <table class="glass-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!$authors): ?>
                        <tr>
                            <td colspan="3" class="text-center p-4">No authors.</td>
                        </tr>
                        <?php else: $i = 0;
                        foreach ($authors as $au): $i++; ?>
                            <tr>
                                <td><?= $i ?></td>
                                <td><?= htmlspecialchars($au['name']) ?></td>
                                <td>
                                    <a href="edit-author.php?id=<?= $au['id'] ?>" class="btn-action btn-edit"><i class="fas fa-pen"></i>Edit</a>
                                    <button class="btn-action btn-danger-custom"
                                        data-bs-toggle="modal"
                                        data-bs-target="#confirmDeleteModal"
                                        data-delete-url="php/delete-author.php?id=<?= $au['id'] ?>"
                                        data-type="author"
                                        data-name="<?= htmlspecialchars($au['name']) ?>">
                                        <i class="fas fa-trash-alt"></i> <span>Delete</span>
                                    </button>

                                </td>
                            </tr>
                        <?php endforeach;
                        endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <button id="topBtn"><i class="fas fa-arrow-up"></i></button>

    <div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmDeleteModalLabel">Confirm Deletion</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p id="deleteModalText">Are you sure you want to delete this item?</p>
                    <p class="text-muted">This action cannot be undone.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <a href="#" class="btn btn-danger-custom" id="confirmDeleteBtn">Yes, Delete</a>
                </div>
            </div>
        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script>
        // Back-to-top
        const topBtn = document.getElementById('topBtn');
        window.addEventListener('scroll', () => topBtn.style.display = window.scrollY > 400 ? 'block' : 'none');
        topBtn.onclick = () => window.scrollTo({ top: 0, behavior: 'smooth' });

        // Flash message auto-hide logic
setTimeout(() => {
    const flashAlert = document.querySelector('.flash .alert');
    if (flashAlert) {
        flashAlert.classList.add('hide');
        flashAlert.addEventListener('transitionend', function () {
            flashAlert.parentElement.remove(); // remove the wrapper too
        });
    }
}, 1500);


        // Auto-scroll to top for flash message on small screens
        if (window.innerWidth < 768 && document.querySelector('.flash')) {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        // Delete modal logic (updated to match new structure and dynamic content)
        const confirmDeleteModalElement = document.getElementById('confirmDeleteModal');
        confirmDeleteModalElement.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget; // Button that triggered the modal
            const deleteUrl = button.getAttribute('data-delete-url');
            const name = button.getAttribute('data-name');
            const type = button.getAttribute('data-type');

            const modalTextElement = this.querySelector('#deleteModalText');
            const confirmButton = this.querySelector('#confirmDeleteBtn');

            modalTextElement.textContent = `Are you sure you want to delete ${type} "${name}"?`;
            confirmButton.href = deleteUrl;
        });

        // Read more/less for description
        document.querySelectorAll('.read-more').forEach(el => {
            el.addEventListener('click', () => {
                const parent = el.closest('td');
                parent.querySelector('.desc-short').classList.toggle('d-none');
                parent.querySelector('.desc-full').classList.toggle('d-none');
                el.textContent = el.textContent === 'Read more' ? 'Read less' : 'Read more';
            });
        });
    </script>
</body>
</html>