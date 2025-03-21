
<?php
 
 try {
   
    $username = "Dooms";
    $password = "Mahamadou78+";
 
    $dsn = "mysql:dbname=books_app;host=localhost;charset=utf8";
 
   
    $options = [PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC];
 
    $pdo = new PDO($dsn, $username, $password, $options);
 

} catch (PDOException $error) {
 
 s
    die("  erreur: " . $error->getMessage());
}
 
?>