<?php
session_start();
include "db_conn.php";
include "php/func-book.php";
$books = get_all_books($conn);
include "php/func-author.php";
$authors = get_all_author($conn);
include "php/func-category.php";
$categories = get_all_categories($conn);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Online Book Store</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <style>
        * {
            font-family: 'Segoe UI', sans-serif;
            box-sizing: border-box;
        }
        body {
            margin: 0;
            padding: 0;
            background: linear-gradient(to bottom right, #0f2027, #203a43, #2c5364);
            min-height: 100vh;
            color: #fff;
        }
        .navbar {
            backdrop-filter: blur(10px);
            background-color: rgba(255, 255, 255, 0.08);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        .navbar-brand { font-weight: bold; color: #FFD700 !important; }
        .nav-link { color: #ddd !important; }
        .nav-link:hover { color: #fff !important; }
        .search-bar {
            margin-top: 2rem;
            max-width: 700px;
            margin-left: auto;
            margin-right: auto;
        }
        .search-bar input.form-control {
            padding: 1rem;
            border-radius: 50px 0 0 50px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            outline: none;
            background: rgba(32, 58, 67, 0.6);
            color: #fff;
        }
        .search-bar input.form-control::placeholder { color: rgba(255, 255, 255, 0.7); }
        .search-bar button {
            border-radius: 0 50px 50px 0;
            border: none;
            background: #FFD700;
            color: #000;
            font-weight: bold;
            padding: 0 25px;
        }
        .filters {
            margin: 2rem auto;
            max-width: 700px;
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 10px;
        }
        .filters select {
            padding: 0.6rem 1rem;
            border-radius: 10px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            outline: none;
            width: 48%;
            background: rgba(32, 58, 67, 0.6);
            color: #fff;
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3E%3Cpath fill='none' stroke='%23ffffff' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m2 5 6 6 6-6'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 0.75rem center;
            background-size: 16px 12px;
        }
        .filters select option { background-color: #203a43; color: #fff; }
        .book-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
            padding: 2rem;
        }
        .card {
            background: rgba(255, 255, 255, 0.1);
            border: none;
            border-radius: 20px;
            backdrop-filter: blur(10px);
            overflow: hidden;
            transition: transform 0.3s;
            display: flex;
            flex-direction: column;
            height: 100%;
        }
        .card:hover { transform: translateY(-5px); }
        .card img {
            height: 250px;
            object-fit: cover;
            width: 100%;
            display: block;
        }
        .card-body { padding: 1rem; color: #fff; flex-grow: 1; }
        .card-title { font-size: 1.2rem; font-weight: bold; margin-bottom: 0.5rem; }
        .card-text { font-size: 0.9rem; margin-bottom: 0.5rem; }
        .description-truncated {
            display: -webkit-box;
            -webkit-box-orient: vertical;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: normal;
        }
        .show-more-btn {
            background: none;
            border: none;
            color: #FFD700;
            font-size: 0.85rem;
            cursor: pointer;
            padding: 0;
            margin-top: 0.5rem;
            text-align: left;
        }
        .show-more-btn:hover { text-decoration: underline; }
        .card-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.8rem 1rem;
            background: transparent;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            margin-top: auto;
        }
        .btn-open { background-color: #28a745; color: white; border-radius: 20px; font-size: 0.8rem; }
        .btn-download { background-color: #1e90ff; color: white; border-radius: 20px; font-size: 0.8rem; }
        footer { text-align: center; margin: 3rem auto 1rem; color: #bbb; font-size: 0.9rem; }
        
        /* Redesigned Button Styles */
        /* Redesigned Button Styles */
.vote-buttons {
    display: flex;
    gap: 8px; /* Space between the like and dislike buttons */
}
.vote-btn {
    background-color: rgba(255, 255, 255, 0.1); /* Subtle, translucent background */
    border: 1px solid rgba(255, 255, 255, 0.2); /* Light border */
    color: #FFD700; /* Gold color for the icons */
    padding: 8px 12px;
    border-radius: 10px;
    cursor: pointer;
    transition: all 0.2s ease;
    font-size: 0.9rem;
}
.vote-btn:hover {
    background-color: rgba(255, 255, 255, 0.2);
    transform: translateY(-2px);
}
.vote-btn.active {
    background-color: #FFD700; /* Solid gold when active */
    color: #203a43; /* Dark text on gold background */
    border-color: #FFD700;
}

/* Styles for vote counts */
.vote-count {
    margin-left: 5px;
    font-size: 0.8rem;
    font-weight: bold;
    color: white; /* Color for the number */
}

/* Back to Top Button */
#backToTop {
    position: fixed;
    bottom: 30px;
    right: 30px;
    background: #FFD700;
    color: #203a43;
    border: none;
    border-radius: 50%;
    width: 50px;
    height: 50px;
    font-size: 1.5rem;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    box-shadow: 0 4px 10px rgba(0,0,0,0.3);
    transition: opacity 0.4s ease, transform 0.3s ease;
    z-index: 999;
    opacity: 0;
    visibility: hidden;
}
#backToTop.show {
    opacity: 1;
    visibility: visible;
}
#backToTop:hover {
    background: #FFA500;
    transform: translateY(-3px);
}

    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark px-4 py-3">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">📚 Online Book Store</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
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

    <form action="search.php" method="get" class="search-bar d-flex justify-content-center">
        <input type="text" name="key" class="form-control" placeholder="Search books, authors, or categories..." required>
        <button type="submit"><i class="fas fa-search"></i></button>
    </form>

    <div class="filters">
        <select onchange="location = this.value;">
            <option value="index.php">All Categories</option>
            <?php foreach ($categories as $category) { ?>
                <option value="category.php?id=<?= $category['id'] ?>"><?= htmlspecialchars($category['name']) ?></option>
            <?php } ?>
        </select>
        <select onchange="location = this.value;">
            <option value="index.php">All Authors</option>
            <?php foreach ($authors as $author) { ?>
                <option value="author.php?id=<?= $author['id'] ?>"><?= htmlspecialchars($author['name']) ?></option>
            <?php } ?>
        </select>
    </div>

    <div class="container">
        <?php if (empty($books)) { ?>
            <div class="text-center text-light mt-5">
                <h4>No books found in the library.</h4>
                <i class="fas fa-book-open fa-3x mt-3"></i>
            </div>
        <?php } else { ?>
            <div class="book-grid">
                <?php foreach ($books as $book) {
                    $user_vote = $_SESSION["voted_{$book['id']}"] ?? null;
                    $user_liked = $user_vote === 'like';
                    $user_disliked = $user_vote === 'dislike';

                    // Fetch vote counts (requires a function in func-book.php)
                    // Example function call (you need to implement this):
                    // $vote_counts = get_vote_counts_for_book($conn, $book['id']);
                    // For now, we'll use a placeholder
                    $like_count = $book['like_count'] ?? 0;
                    $dislike_count = $book['dislike_count'] ?? 0;

                    $authorName = '';
                    foreach ($authors as $author) {
                        if ($author['id'] == $book['author_id']) {
                            $authorName = $author['name'];
                            break;
                        }
                    }
                    $categoryName = '';
                    foreach ($categories as $cat) {
                        if ($cat['id'] == $book['category_id']) {
                            $categoryName = $cat['name'];
                            break;
                        }
                    }
                    $description = htmlspecialchars($book['description'] ?? 'No description available.');
                    $shortDescription = substr($description, 0, 150);
                    $hasMore = strlen($description) > 150;
                    if ($hasMore) { $shortDescription .= '...'; }
                ?>
                    <div class="card">
                        <a href="uploads/cover/<?= htmlspecialchars($book['cover']) ?>" target="_blank">
                            <img src="uploads/cover/<?= htmlspecialchars($book['cover']) ?>" class="card-img-top" alt="Book Cover">
                        </a>
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($book['title']) ?></h5>
                            <p class="card-text">
                                <strong>By:</strong> <?= htmlspecialchars($authorName) ?><br>
                                <strong>Category:</strong> <?= htmlspecialchars($categoryName) ?>
                            </p>
                            <div class="book-description">
                                <p class="card-text description-text" id="desc-<?= $book['id'] ?>">
                                    <strong>Description:</strong>
                                    <span class="truncated-content"><?= $shortDescription ?></span>
                                    <span class="full-content" style="display: none;"><?= $description ?></span>
                                </p>
                                <?php if ($hasMore) { ?>
                                    <button class="show-more-btn" data-book-id="<?= $book['id'] ?>">Show More</button>
                                <?php } ?>
                            </div>
                        </div>
                        <div class="card-footer">
                            <a href="uploads/files/<?= htmlspecialchars($book['file']) ?>" target="_blank" class="btn btn-open">Open</a>
                            <a href="uploads/files/<?= htmlspecialchars($book['file']) ?>" download class="btn btn-download">Download</a>
<div class="vote-buttons">
    <button class="vote-btn" onclick="vote(<?= $book['id'] ?>, 'like')">
        <i class="fas fa-thumbs-up"></i>
        <span class="vote-count" id="likes-<?= $book['id'] ?>"><?= $book['likes'] ?></span>
    </button>
    <button class="vote-btn" onclick="vote(<?= $book['id'] ?>, 'dislike')">
        <i class="fas fa-thumbs-down"></i>
        <span class="vote-count" id="dislikes-<?= $book['id'] ?>"><?= $book['dislikes'] ?></span>
    </button>
</div>
                        </div>
                    </div>
                <?php } ?>
            </div>
        <?php } ?>
    </div>
<button id="backToTop" title="Back to Top"><i class="fas fa-arrow-up"></i></button>
    <footer>
        &copy; <?= date("Y") ?> Online Book Store. All rights reserved.
    </footer>

    <script>
function vote(bookId, type) {
    $.ajax({
        url: "vote.php",
        type: "POST",
        data: { book_id: bookId, vote: type },
        dataType: "json",
        success: function(response) {
            if (response.likes !== undefined && response.dislikes !== undefined) {
                // Update the like and dislike counts dynamically
                $("#likes-" + bookId).text(response.likes);
                $("#dislikes-" + bookId).text(response.dislikes);
            }
        },
        error: function(xhr, status, error) {
            console.error("Error: " + error);
        }
    });
}
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
$(document).ready(function () {
    $(".show-more-btn").on("click", function () {
            const bookId = $(this).data("book-id");
                    const truncated = $("#desc-" + bookId + " .truncated-content");
                            const full = $("#desc-" + bookId + " .full-content");

                                    if (full.is(":visible")) {
                                                full.hide();
                                                            truncated.show();
                                                                        $(this).text("Show More");
                                                                                } else {
                                                                                            full.show();
                                                                                                        truncated.hide();
                                                                                                                    $(this).text("Show Less");
                                                                                                                            }
                                                                                                                                });
                                                                                                                                });
    
    

                                                                                                                            </script>
  <script>
const backToTopBtn = document.getElementById("backToTop");

window.addEventListener("scroll", () => {
    if (window.scrollY > 300) {
        backToTopBtn.classList.add("show");
    } else {
        backToTopBtn.classList.remove("show");
    }
});

backToTopBtn.addEventListener("click", () => {
    window.scrollTo({ top: 0, behavior: "smooth" });
});
</script>
                                                                                                                          
</body>
</html>