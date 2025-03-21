<?php include 'db.php';
session_start(); 

require_once "db.php";
 
if ($_SERVER["REQUEST_METHOD"] == "POST") {
   
    // Récupére les info de l'utilisateur
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];
   
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Email invalide";
        exit;
    }
   
    try {
        // Prépare la requête sql pour récupérer les info de l'utilisateur
        $stmt = $pdo->prepare("SELECT id, name, password FROM users WHERE email = :email");
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC); // Récupére les résultats sous forme de tableau associatif
       
        // Vérifie si l'utilisateur existe et si le mot de passe correspond au mot de passe haché de la bdd
        if ($user && password_verify($password, $user['password'])) {
            // Stocke les informations de l'utilisateur dans la session pour maintenir la connexion
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
           
            echo "Connexion réussie ! Bienvenue, " . $_SESSION['user_name'] . ".";
            // Redirection vers la homepage
            header("Location: search.php");
            exit;
        } else {
            echo "Email ou mot de passe incorrect.";
        }
    } catch (PDOException $e) {
        echo "Erreur lors de la connexion : " . $e->getMessage();
    }
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
</head>
<body>
    <h2>Connexion</h2>
    <form action="login.php" method="POST">
        <label for="email">Email :</label>
        <input type="email" name="email" required><br>
       
        <label for="password">Mot de passe :</label>
        <input type="password" name="password" required><br>
       
        <button type="submit">Se connecter</button>
    </form>
    <p>Pas encore de compte ? <a href="signup.php">Inscrivez-vous ici</a></p>
</body>
</html>
<head>
    <link rel="stylesheet" href="index.css">
    <script src="script.js" defer></script>
</head>
<style>body {
    font-family: 'Bangers', cursive;
    background-color: #ffde00;
    margin: 0;
    padding: 20px;
    text-align: center;
}

h1 {
    font-size: 40px;
    color: #ff0000;
    text-shadow: 3px 3px 0px black;
}

.container {
    max-width: 1200px;
    margin: auto;
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 20px;
}

/* Style des cartes façon BD */
.card {
    width: 260px;
    background: white;
    border: 5px solid black;
    border-radius: 15px;
    box-shadow: 5px 5px 0px black, 10px 10px 0px rgba(0, 0, 0, 0.2);
    overflow: hidden;
    transition: transform 0.2s, box-shadow 0.2s;
    position: relative;
    text-align: center;
}

.card:hover {
    transform: scale(1.05);
    box-shadow: 7px 7px 0px black, 12px 12px 0px rgba(0, 0, 0, 0.2);
}

/* Effet BD : explosion en arrière-plan */
.card::after {
    content: "";
    position: absolute;
    width: 120%;
    height: 120%;
    background: radial-gradient(circle, rgba(255, 0, 0, 0.6) 0%, rgba(0, 0, 0, 0) 70%);
    top: -10%;
    left: -10%;
    z-index: -1;
    opacity: 0;
    transition: opacity 0.3s;
}

.card:hover::after {
    opacity: 1;
}

/* Images des films/livres */
.card img {
    width: 100%;
    height: 350px;
    object-fit: cover;
    border-bottom: 5px solid black;
}

/* Contenu des cartes */
.card-content {
    padding: 15px;
}

.card h3 {
    font-size: 20px;
    margin: 10px 0;
    color: #000;
    text-shadow: 2px 2px 0px red;
}

.card p {
    color: #222;
    font-size: 16px;
    font-weight: bold;
}


/* Gestion des erreurs */
.error-message {
    color: red;
    font-size: 1.1rem;
    margin-bottom: 15px;
}
</style>