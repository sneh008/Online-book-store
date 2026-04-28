<?php
session_start();

if (!isset($_SESSION['user_id'], $_SESSION['user_email'])) {
    header("Location: login.php");
    exit;
}

include "db_conn.php";

// Fetch all feedbacks
$stmt = $conn->prepare("SELECT * FROM feedback ORDER BY id DESC");
$stmt->execute();
$feedbacks = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Feedbacks – Online Book Store</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

    <style>
        /* CSS from admin.php and other admin-facing pages */
        :root {
            --gold: #ffd700;
            --green: #28a745;
            --blue-light: #1e90ff;
            --glass-bg: rgba(255, 255, 255, .1);
            --danger-red: #dc3545; /* Added for delete button */
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

        /* Table Styling */
        .table {
            background: rgba(255, 255, 255, 0.05);
            /* Lighter glass effect */
            border: 1px solid rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(5px);
            border-radius: 0.75rem;
            overflow: hidden;
            /* Ensures rounded corners apply to content */
            color: #fff;
            /* Default text color for table content */
        }

        .table thead {
            background-color: rgba(0, 0, 0, 0.3);
            /* Darker header for contrast */
            color: var(--gold);
        }

        .table th,
        .table td {
            border-color: rgba(255, 255, 255, 0.15);
            /* Lighter border for table cells */
            padding: 1rem;
            vertical-align: middle;
        }

        /* NEW / MODIFIED ROW STYLING FOR STRIPES */
        .table tbody tr {
            background-color: rgba(255, 255, 255, 0.02); /* Default (odd) row background - very subtle */
        }

        .table tbody tr:nth-child(even) {
            background-color: rgba(255, 255, 255, 0.04); /* Even row background - slightly darker */
        }

        .table-hover tbody tr:hover {
            background-color: rgba(255, 255, 255, 0.12); /* Increased hover effect for better visibility */
        }
        /* END NEW / MODIFIED ROW STYLING */

        .alert-info {
            background-color: rgba(30, 144, 255, 0.2);
            /* Semi-transparent blue */
            color: #add8e6;
            /* Lighter blue text */
            border: 1px solid rgba(30, 144, 255, 0.4);
            border-radius: 0.75rem;
            padding: 1.25rem;
        }

        .btn-secondary {
            background: linear-gradient(to right, #6c757d, #5a6268);
            border: none;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            border-radius: 0.5rem;
            transition: background-color 0.3s ease, transform 0.2s ease;
            color: #fff;
        }

        .btn-secondary:hover {
            background: linear-gradient(to left, #6c757d, #5a6268);
            transform: translateY(-2px);
            color: #fff;
        }

        .btn-danger-custom { /* Custom class for the delete button */
            background: var(--danger-red);
            border: none;
            color: #fff;
            padding: 0.5rem 1rem;
            font-weight: 500;
            border-radius: 0.3rem;
            transition: background-color 0.2s ease, transform 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
        }
        .btn-danger-custom:hover {
            background-color: #c82333;
            transform: translateY(-1px);
            color: #fff;
        }

        /* Flash Message Styling (copied from other admin pages) */
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
            background-color: rgba(40, 167, 69, 0.7); /* Success */
            transition: opacity 0.5s ease-out, transform 0.5s ease-out;
        }

        .flash .alert-danger {
            background-color: rgba(220, 53, 69, 0.7); /* Danger */
        }

        .flash .alert.hide {
            opacity: 0;
            transform: translateY(-20px);
            transition: opacity 0.5s ease-out, transform 0.5s ease-out;
        }

        /* Modal Styling */
        .modal-content {
            background: rgba(15, 32, 39, 0.9); /* Darker glass background for modal */
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 1rem;
            color: #fff;
        }

        .modal-header {
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .modal-footer {
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        .modal-title {
            color: var(--gold);
        }

        .btn-close {
            filter: invert(1) grayscale(100%) brightness(200%); /* Make close button white */
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

            .table {
                font-size: 0.9rem;
            }

            .table th,
            .table td {
                padding: 0.75rem;
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
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark px-4 py-3">
        <div class="container-fluid">
            <a class="navbar-brand" href="admin.php">📚 Admin Panel</a>
            <button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#nav" aria-controls="nav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="nav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="admin.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="index.php">User view</a></li>
                    <li class="nav-item"><a class="nav-link" href="add-book.php">Add Book</a></li>
                    <li class="nav-item"><a class="nav-link" href="add-category.php">Add Category</a></li>
                    <li class="nav-item"><a class="nav-link" href="add-author.php">Add Author</a></li>
                    <li class="nav-item"><a class="nav-link active" aria-current="page" href="feedbacks.php">Feedbacks</a></li>
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

    <div class="container mt-5">
        <h2 class="text-center mb-4" style="color: var(--gold);">User Feedbacks</h2>

        <?php if (!$feedbacks): ?>
            <div class="alert alert-info text-center mt-4">No feedback received yet.</div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Rating</th>
                            <th>Message</th>
                            <th>Submitted At</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i = 0;
                        foreach ($feedbacks as $fb): $i++; ?>
                            <tr>
                                <td><?= $i ?></td>
                                <td><?= htmlspecialchars($fb['name']) ?></td>
                                <td><?= htmlspecialchars($fb['email']) ?></td>
                                <td><?= htmlspecialchars($fb['rating']) ?></td>
                                <td><?= nl2br(htmlspecialchars($fb['message'])) ?></td>
                                <td><?= $fb['created_at'] ?></td>
                                <td>
                                    <button type="button" class="btn btn-danger-custom btn-sm"
                                        data-bs-toggle="modal" data-bs-target="#deleteFeedbackModal"
                                        data-feedback-id="<?= $fb['id'] ?>">
                                        <i class="fas fa-trash-alt"></i> Delete
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
        <a href="admin.php" class="btn btn-secondary mt-4">Back to Dashboard</a>
    </div>

    <div class="modal fade" id="deleteFeedbackModal" tabindex="-1" aria-labelledby="deleteFeedbackModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteFeedbackModalLabel">Confirm Deletion</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this feedback? This action cannot be undone.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <form id="deleteFeedbackForm" action="php/delete-feedback.php" method="POST" style="display: inline;">
                        <input type="hidden" name="feedback_id" id="feedback-to-delete-id">
                        <button type="submit" class="btn btn-danger-custom">Delete Feedback</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

    <script>
        // JavaScript for Delete Confirmation Modal
        document.addEventListener('DOMContentLoaded', function () {
            var deleteFeedbackModal = document.getElementById('deleteFeedbackModal');
            deleteFeedbackModal.addEventListener('show.bs.modal', function (event) {
                // Button that triggered the modal
                var button = event.relatedTarget;
                // Extract info from data-bs-* attributes
                var feedbackId = button.getAttribute('data-feedback-id');

                // Update the modal's content.
                var modalForm = deleteFeedbackModal.querySelector('#deleteFeedbackForm');
                var hiddenInput = modalForm.querySelector('#feedback-to-delete-id');
                hiddenInput.value = feedbackId;
            });

            // Flash message auto-hide logic
            setTimeout(() => {
    const flashAlert = document.querySelector('.flash .alert');
    if (flashAlert) {
        flashAlert.classList.add('hide');
        flashAlert.addEventListener('transitionend', function () {
            flashAlert.parentElement.remove(); // remove the whole flash wrapper
        });
    }
}, 1500);


            // Auto-scroll to top for flash message on small screens
            if (window.innerWidth < 768 && document.querySelector('.flash')) {
                window.scrollTo({ top: 0, behavior: 'smooth' });
            }
        });
    </script>
</body>

</html>