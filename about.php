<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>About Us - Online Book Store</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

    <style>
        /* Combined & Refined CSS for Dark-Glass Theme */
        :root {
            --gold: #ffd700;
            --green: #28a745;
            --blue-light: #1e90ff;
            --glass-bg: rgba(255, 255, 255, .1); /* Lighter for content boxes */
            --navbar-bg: rgba(255, 255, 255, .05); /* Slightly less transparent for navbar */
        }

        * {
            box-sizing: border-box;
            font-family: 'Segoe UI', sans-serif;
        }

        body {
            background: linear-gradient(135deg, #0f2027, #203a43, #2c5364); /* Darker, richer gradient */
            color: #fff;
            margin: 0;
            min-height: 100vh;
            padding-top: 70px; /* To prevent content from being under fixed navbar */
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
            border-radius: 0; /* Remove bottom border-radius if fixed */
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

        .nav-link:hover, .nav-link.active {
            color: #fff !important;
        }

        /* About Content Container */
        .about-content-container { /* Renamed for clarity */
            background: var(--glass-bg); /* Glassmorphism background */
            border: 1px solid rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            padding: 3rem; /* Slightly more padding for a better feel */
            border-radius: 1rem;
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
            width: 100%;
            max-width: 800px;
            color: #f0f0f0;
            margin-top: 2rem; /* Adjusted margin for fixed navbar */
            margin-bottom: 2rem;
        }

        .about-content-container h2 {
            color: var(--gold); /* Gold color for headings */
            font-weight: 700;
            margin-bottom: 1.5rem;
        }

        .about-content-container p {
            font-size: 1.05rem; /* Slightly adjusted font size */
            line-height: 1.7;
            color: #ccc; /* Lighter text color for better readability on dark background */
        }
        .about-content-container p strong {
            color: #fff; /* Ensure strong text stands out */
        }

        footer {
            margin-top: auto; /* Push footer to bottom */
            padding: 1rem 0;
            color: rgba(255,255,255,0.6);
            text-align: center;
            font-size: 0.9rem;
            width: 100%;
        }

        /* Responsive Adjustments */
        @media (max-width: 768px) {
            .navbar-brand {
                font-size: 1.2rem;
            }
            .nav-link {
                font-size: 0.9rem;
                padding: 0.4rem 0.8rem;
            }
            .about-content-container {
                margin-top: 1rem;
                padding: 2rem;
            }
            .about-content-container h2 {
                font-size: 1.8rem;
            }
            .about-content-container p {
                font-size: 1rem;
            }
        }

        @media (max-width: 576px) {
            .navbar-brand {
                font-size: 1.1rem;
            }
            .nav-link {
                font-size: 0.85rem;
                padding: 0.3rem 0.6rem;
            }
            .about-content-container {
                padding: 1.5rem;
            }
            .about-content-container h2 {
                font-size: 1.6rem;
            }
        }
    </style>
</head>

<body>
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

        <div class="about-content-container">
        <h2 class="mb-4">About Our Book Store</h2>
        <p>Welcome to the <strong>Online Book Store</strong> – your one-stop destination for a vast collection of books across genres and languages. Whether you’re a fan of literature, technology, or regional stories, we’ve got something for everyone.</p>
        <p>Our mission is to promote reading by making books accessible, downloadable, and readable directly from your browser. Built with love using PHP, Bootstrap, and modern design techniques.</p>
        <p>We continuously add new titles, support authors, and improve our platform to serve readers better. Thank you for being a part of our journey.</p>
    </div>

    <footer>&copy; <?= date('Y') ?> Online Book Store. All rights reserved.</footer>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>