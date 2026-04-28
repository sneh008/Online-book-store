<?php
function get_all_books($con){
   $sql  = "SELECT * FROM books ORDER bY id DESC";
   $stmt = $con->prepare($sql);
   $stmt->execute();

   if ($stmt->rowCount() > 0) {
   	  $books = $stmt->fetchAll();
   }else {
      $books = 0;
   }

   return $books;
}

function get_book($con, $id){
   $sql  = "SELECT * FROM books WHERE id=?";
   $stmt = $con->prepare($sql);
   $stmt->execute([$id]);

   if ($stmt->rowCount() > 0) {
   	  $book = $stmt->fetch();
   }else {
      $book = 0;
   }

   return $book;
}

function search_books($conn, $key) {
    $sql = "SELECT books.*, authors.name AS author_name, categories.name AS category_name
            FROM books
            LEFT JOIN authors ON books.author_id = authors.id
            LEFT JOIN categories ON books.category_id = categories.id
            WHERE books.title LIKE :key
               OR books.description LIKE :key
               OR authors.name LIKE :key
               OR categories.name LIKE :key
            ORDER BY books.id DESC";

    $stmt = $conn->prepare($sql);
    $searchKey = '%' . $key . '%';
    $stmt->bindValue(':key', $searchKey, PDO::PARAM_STR);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
   
function get_books_by_category($con, $id){
   $sql  = "SELECT * FROM books WHERE category_id=?";
   $stmt = $con->prepare($sql);
   $stmt->execute([$id]);

   if ($stmt->rowCount() > 0) {
        $books = $stmt->fetchAll();
   }else {
      $books = 0;
   }

   return $books;
}

function get_books_by_author($con, $id){
   $sql  = "SELECT * FROM books WHERE author_id=?";
   $stmt = $con->prepare($sql);
   $stmt->execute([$id]);

   if ($stmt->rowCount() > 0) {
        $books = $stmt->fetchAll();
   }else {
      $books = 0;
   }

   return $books;
}