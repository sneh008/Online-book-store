<?php
session_start();
$error = isset($_SESSION['error']) ? $_SESSION['error'] : '';
$old_email = isset($_SESSION['email']) ? $_SESSION['email'] : '';
unset($_SESSION['error'], $_SESSION['email']);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
    <style>
        /* same styles as before */
        body {
            background: linear-gradient(135deg, #FF6B6B 0%, #4ECDC4 100%);
            font-family: 'Montserrat', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            overflow: hidden;
            position: relative;
        }

        body::before,
        body::after {
            content: '';
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.15);
            animation: float 10s ease-in-out infinite;
        }

        body::before {
            width: 150px;
            height: 150px;
            top: 10%;
            left: 15%;
        }

        body::after {
            width: 200px;
            height: 200px;
            bottom: 10%;
            right: 10%;
            animation-delay: 2s;
        }

        @keyframes float {
            0% {
                transform: translateY(0) rotate(0deg);
            }

            50% {
                transform: translateY(-20px) rotate(10deg);
            }

            100% {
                transform: translateY(0) rotate(0deg);
            }
        }

        .login-form {
            background: rgba(255, 255, 255, 0.98);
            padding: 4rem;
            border-radius: 1.5rem;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.25);
            max-width: 35rem;
            width: 100%;
            animation: slideInUp 1s ease-out;
        }

        @keyframes slideInUp {
            from {
                opacity: 0;
                transform: translateY(50px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .login-form h1 {
            color: #333;
            margin-bottom: 2.5rem;
            font-weight: 700;
            font-size: 2.8rem;
        }

        .form-label {
            color: #444;
            font-weight: 600;
            margin-bottom: 0.75rem;
        }

        .form-control {
            border-radius: 0.75rem;
            padding: 0.9rem 1.2rem;
            border: 1px solid #ddd;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: #4ECDC4;
            box-shadow: 0 0 0 0.25rem rgba(78, 205, 196, 0.25);
        }

        .btn-primary {
            background: linear-gradient(to right, #FF6B6B 0%, #FFB64B 100%);
            border: none;
            border-radius: 0.75rem;
            padding: 0.9rem 1.8rem;
            font-size: 1.2rem;
            font-weight: 700;
            width: 100%;
            margin-top: 2.5rem;
        }

        .btn-primary:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 25px rgba(255, 107, 107, 0.4);
        }

        .custom-error-message {
            background-color: rgba(255, 107, 107, 0.15);
            color: #d63031;
            border: 1px solid rgba(255, 107, 107, 0.4);
            border-radius: 0.75rem;
            padding: 1rem 1.5rem;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            font-size: 0.95rem;
            font-weight: 500;
        }

        .custom-error-message .icon {
            margin-right: 1rem;
            font-size: 1.2rem;
            color: #FF6B6B;
        }

        .store-link {
            text-align: center;
            display: block;
            margin-top: 1.5rem;
            color: #555;
            font-weight: 600;
        }

        .admin-note {
            text-align: center;
            margin-top: 2rem;
            font-size: 0.9rem;
            color: #666;
            opacity: 0.8;
        }
    </style>
</head>

<body>
    <form class="login-form" method="post" action="php/auth.php">
        <h1 class="text-center">LOGIN</h1>

        <?php if (!empty($error)): ?>
            <div id="errorMessage" class="custom-error-message" role="alert">
                <i class="fas fa-exclamation-circle icon"></i>
                <span><?= htmlspecialchars($error) ?></span>
            </div>
        <?php endif; ?>

        <div class="mb-3">
            <label for="exampleInputEmail1" class="form-label">Email address</label>
            <input type="email" class="form-control" id="exampleInputEmail1" placeholder="Enter your email"
                name="email" value="<?= htmlspecialchars($old_email) ?>" autofocus>
        </div>

        <div class="mb-3">
            <label for="exampleInputPassword1" class="form-label">Password</label>
            <input type="password" class="form-control" id="exampleInputPassword1"
                placeholder="Enter your password" name="password">
        </div>

        <button type="submit" class="btn btn-primary">Login</button>
        <a href="index.php" class="store-link">Explore Our Store</a>
        <p class="admin-note">Note: Only admin can access or log in here.</p>
    </form>

    <script>
        // Optional: fade-out error
        document.addEventListener('DOMContentLoaded', function() {
            const errorMessage = document.getElementById('errorMessage');
            if (errorMessage) {
                setTimeout(() => {
                    errorMessage.style.opacity = '0';
                }, 2500);
            }
        });
    </script>
</body>

</html>