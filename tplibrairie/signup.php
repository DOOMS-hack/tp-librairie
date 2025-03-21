<?php 

require_once 'db.php'; ?>
 <?php 
// Vérifie que le form ait été soumis en POST et que le bouton de submit ait bien été cliqué
if (($_SERVER["REQUEST_METHOD"] === "POST") && isset($_POST["submit"])) {
   
    if (!empty($_POST["username"]) && !empty($_POST["email"]) && !empty($_POST["password"])) {
    } else {
            $error = "Veuillez remplir tous les champs";
        }
 
    $name = htmlspecialchars(trim($_POST['name'])); // Supprime les espaces et protège contre les attaques XSS
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL); // Retire les caractères invalides
    $password = $_POST['password'];
   
    // Vérifie si l'email est valide
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Email invalide";
        exit;
    }
   
    if (strlen($password) < 6) {
        echo "Le mot de passe doit contenir au moins 6 caractères";
        exit;
    }
   
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
   
    try {
        // Préparation la requête avec des paramètres pour protéger contre les injections SQL
        $stmt = $pdo->prepare("INSERT INTO users (name, email, password) VALUES (:name, :email, :password)");
       
        // Exécution de la requête avec les valeurs sécurisées
        $stmt->execute([
            'name' => $name,
            'email' => $email,
            'password' => $hashed_password
        ]);
       
        echo "Inscription réussie ! Vous pouvez maintenant vous connecter.";
    } catch (PDOException $e) {
        // Gère les erreurs si l'email est déja utilisé
        if ($e->getCode() == 23000) {
            echo "Cet email est déjà utilisé.";
        } else {
            echo "Erreur lors de l'inscription : " . $e->getMessage();
        }
    }
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
</head>
<body>
   
<div class="outer-box">
        <div class="inner-box">
            <header class="signup-header">
 
            <h2>Inscription Ici !</h2>
 
            </header>
 
<main class="signup-body">
 
<form action="signup.php" method="POST">
    <label for="name">Nom :</label>
    <input type="text" name="name" placeholder="Prénom" required><br>
   
    <label for="email">Email :</label>
    <input type="email" name="email" placeholder="E-mail" required><br>
   
    <label for="password">Mot de passe :</label>
    <input type="password" name="password" placeholder="Mot de passe" required><br>
   
    <button class="inscriptionbtn"name='submit' type="submit">S'inscrire</button>
</form>
 
<footer class="signup-footer">
    <p>Déjà un compte ? <a href="login.php">Connectez-vous ici</a></p>
</footer>
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