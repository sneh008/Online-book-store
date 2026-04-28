<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Feedback - Online Book Store</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

    <style>
        /* Combined & Refined CSS for Dark-Glass Theme */
        :root {
            --gold: #ffd700;
            --green: #28a745;
            --blue-light: #1e90ff;
            --glass-bg: rgba(255, 255, 255, .1);
            /* Lighter for forms */
            --navbar-bg: rgba(255, 255, 255, .05);
            /* Slightly less transparent for navbar */
        }

        * {
            box-sizing: border-box;
            font-family: 'Segoe UI', sans-serif;
        }

        body {
            background: linear-gradient(135deg, #0f2027, #203a43, #2c5364);
            /* Darker, richer gradient */
            color: #fff;
            margin: 0;
            min-height: 100vh;
            padding-top: 70px;
            /* To prevent content from being under fixed navbar */
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-start;
        }

        /* NAVBAR */
        .navbar {
            backdrop-filter: blur(10px);
            background: var(--navbar-bg);
            border-bottom: 1px solid rgba(255, 255, 255, .12);
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 1000;
            border-radius: 0;
            /* Remove bottom border-radius if fixed */
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

        .nav-link:hover,
        .nav-link.active {
            color: #fff !important;
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
            transition: opacity 0.5s ease-out, transform 0.5s ease-out;
        }

        .flash .alert-danger {
            background-color: rgba(220, 53, 69, 0.7);
        }

        .flash .alert.hide {
            opacity: 0;
            transform: translateY(-20px);
        }

        /* Feedback Form Specific Styles */
        .feedback-form-container {
            /* Renamed for clarity */
            background: var(--glass-bg);
            /* Glassmorphism background */
            border: 1px solid rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            padding: 3rem;
            border-radius: 1rem;
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
            width: 100%;
            max-width: 600px;
            /* Reduced max-width slightly for better aesthetic */
            color: #f0f0f0;
            margin-top: 2rem;
            /* Adjusted margin for fixed navbar */
            margin-bottom: 2rem;
        }

        .feedback-form-container .form-label {
            color: #ddd;
            font-weight: 500;
        }

        .feedback-form-container .form-control {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.3);
            color: #fff;
            border-radius: 0.5rem;
            padding: 0.75rem 1rem;
        }

        .feedback-form-container .form-control::placeholder {
            color: rgba(255, 255, 255, 0.7);
        }

        .feedback-form-container .form-control:focus {
            background: rgba(255, 255, 255, 0.1);
            border-color: var(--gold);
            box-shadow: 0 0 0 0.25rem rgba(255, 215, 0, 0.25);
            color: #fff;
        }

        .btn-submit {
            /* Renamed for clarity */
            background: var(--gold);
            color: #000;
            border: none;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            border-radius: 0.5rem;
            transition: background-color 0.3s ease, transform 0.2s ease;
            width: 100%;
        }

        .btn-submit:hover {
            background-color: #e6b800;
            transform: translateY(-2px);
        }

        .rating-stars i {
            color: #bbb;
            /* Default star color */
            font-size: 1.5rem;
            /* Slightly larger stars */
            cursor: pointer;
            transition: all 0.2s ease-in-out;
        }

        .rating-stars i:hover,
        .rating-stars i.active {
            color: var(--gold);
            /* Active star color */
            transform: scale(1.1);
        }

        footer {
            margin-top: auto;
            /* Push footer to bottom */
            padding: 1rem 0;
            color: rgba(255, 255, 255, 0.6);
            text-align: center;
            font-size: 0.9rem;
            width: 100%;
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

            .feedback-form-container {
                padding: 1.5rem;
                margin-top: 1rem;
            }

            .feedback-form-container h2 {
                font-size: 1.8rem;
            }
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark px-4 py-3">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">📚 Online Book Store</a>
            <button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link active" aria-current="page" href="feedback.php">Feedback</a></li>
                    <li class="nav-item"><a class="nav-link" href="about.php">About</a></li>
                    <li class="nav-item">
                        <?php if (isset($_SESSION['user_id'])) { ?>
                            <a class="nav-link" href="admin.php">Admin</a>
                        <?php } else { ?>
                            <a class="nav-link" href="login.php">Login</a>
                        <?php } ?>
                    </li>
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


    <div class="feedback-form-container">
        <h2 class="mb-4 text-center text-light">📝 Share Your Feedback</h2>

        <form action="php/feedback-process.php" method="post">
            <div class="mb-3">
                <label class="form-label">Your Name</label>
                <input type="text" name="name" class="form-control" placeholder="Enter your name" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Your Email</label>
                <input type="email" name="email" class="form-control" placeholder="Enter your email" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Rate Our Book Store</label>
                <div class="rating-stars">
                    <?php for ($i = 1; $i <= 5; $i++): ?>
                        <i class="fas fa-star" data-rate="<?= $i ?>"></i>
                    <?php endfor; ?>
                </div>
                <input type="hidden" name="rating" id="rating-value" value="0" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Your Feedback</label>
                <textarea name="message" class="form-control" rows="5" placeholder="Write your thoughts..." required></textarea>
            </div>
            <button type="submit" class="btn btn-submit">Submit Feedback</button>
        </form>
    </div>

    <footer>&copy; <?= date('Y') ?> Online Book Store. All rights reserved.</footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

    <script>
        // Star Rating Logic
        const stars = document.querySelectorAll('.rating-stars i');
        const ratingInput = document.getElementById('rating-value');
        stars.forEach(star => {
            star.addEventListener('click', () => {
                const rating = star.dataset.rate;
                ratingInput.value = rating;
                stars.forEach(s => s.classList.toggle('active', s.dataset.rate <= rating));
            });
        });

        // Flash message auto-hide logic
        setTimeout(() => {
            const flashMessage = document.querySelector('.flash');
            if (flashMessage) {
                flashMessage.remove(); // Or add a 'hide' class for a fade-out effect if desired
            }
        }, 2500);

        // Auto-scroll to top for flash message on small screens
        if (window.innerWidth < 768 && document.querySelector('.flash')) {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        }
    </script>
</body>

</html>