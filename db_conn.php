<?php 

# server name
$sName = "sql211.infinityfree.com";
# user name
$uName = "if0_39400271";
# password
$pass = "s9FeYDGqkDGob";

# database name
$db_name = "if0_39400271_online_book_store_db_demo";


// creating database connection 
// useing The PHP Data Objects (PDO)

try {
    $conn = new PDO("mysql:host=$sName;dbname=$db_name", 
                    $uName, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}catch(PDOException $e){
  echo "Connection failed : ". $e->getMessage();
}