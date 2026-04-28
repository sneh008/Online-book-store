<?php
session_start();
require_once "db_conn.php";

if (isset($_POST['book_id'], $_POST['vote'])) {
    $book_id = intval($_POST['book_id']);
    $vote = $_POST['vote'];

    // Check if user already voted in this session
    $previous_vote = $_SESSION["voted_$book_id"] ?? null;

    if ($previous_vote !== $vote) {
        if ($vote === 'like') {
            $sql = "UPDATE books SET likes = likes + 1" . 
                   ($previous_vote === 'dislike' ? ", dislikes = dislikes - 1" : "") . 
                   " WHERE id = ?";
        } elseif ($vote === 'dislike') {
            $sql = "UPDATE books SET dislikes = dislikes + 1" . 
                   ($previous_vote === 'like' ? ", likes = likes - 1" : "") . 
                   " WHERE id = ?";
        }

        $stmt = $conn->prepare($sql);
        $stmt->execute([$book_id]);

        // Save vote in session
        $_SESSION["voted_$book_id"] = $vote;
    }

    // Fetch updated counts
    $stmt = $conn->prepare("SELECT likes, dislikes FROM books WHERE id = ?");
    $stmt->execute([$book_id]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    header('Content-Type: application/json');
    echo json_encode($result);
}
