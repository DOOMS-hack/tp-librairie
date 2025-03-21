<?php

require_once 'db.php'; // Inclusion de la connexion à la base de données

// Vérifier si le paramètre 'query' est présent
if (!isset($_GET['query']) || empty(trim($_GET['query']))) {
    // Si aucun livre n'est recherché, on affiche une sélection de films populaires

    "movies": [
        {
            "title": "Black Panther",
            "director": "Ryan Coogler",
            "year": 2018,
            "poster": "https://image.tmdb.org/t/p/w500/uxzzxijgPIY7slzFvMotPv8wjKA.jpg"
        },
        {
            "title": "Iron Man",
            "director": "Jon Favreau",
            "year": 2008,
            "poster": "https://image.tmdb.org/t/p/w500/78lPtwv72eTNqFW9COBYI0dWDJa.jpg"
        },
        {
            "title": "Spiderman: No Way Home",
            "director": "Jon Watts",
            "year": 2021,
            "poster": "https://image.tmdb.org/t/p/w500/5vHssUeVe25bMrof1HyaPyWgaP.jpg"
        },
        {
            "title": "Harry Potter à l'école des sorciers",
            "director": "Chris Columbus",
            "year": 2001,
            "poster": "https://image.tmdb.org/t/p/w500/wuMc08IPKEatf9rnMNXvIDxqP4W.jpg"
        },
        {
            "title": "Venom",
            "director": "Ruben Fleischer",
            "year": 2018,
            "poster": "https://image.tmdb.org/t/p/w500/2uNW4WbgBXL25BAbXGLnLqX71Sw.jpg"
        }
    ]
}
    
    
}

// Recherche d'un livre via l'API Google Books
$query = urlencode($_GET['query']);
$apiKey = "AIzaSyCjwc3S5Yrx2daLdeAJMDaVnBpE4G0cTmw"; // Remplace par ta clé API Google Books
$url = "https://www.googleapis.com/books/v1/volumes?q=". urlencode($search) . "&key=" . $apiKey;
// // // Initialisation de cURL

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
curl_close($ch);

// Affichage des résultats
echo $response;

?>
Ajout d'un livre aux favoris
php
Copier
Modifier
<?php

require_once "../includes/config.php"; // Connexion à la base de données

session_start();

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["error" => "Utilisateur non connecté."]);
    exit;
}

// Récupérer les données JSON envoyées
$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['book_id'], $data['title'], $data['authors'])) {
    echo json_encode(["error" => "Données invalides."]);
    exit;
}

$user_id = $_SESSION['user_id'];
$book_id = $data['book_id'];
$title = $data['title'];
$authors = is_array($data['authors']) ? implode(", ", $data['authors']) : $data['authors'];
$thumbnail = $data['thumbnail'] ?? "public/images/no_cover.jpg";

try {
    $stmt = $pdo->prepare("INSERT INTO favorites (user_id, book_id, title, authors, thumbnail) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$user_id, $book_id, $title, $authors, $thumbnail]);

    echo json_encode(["success" => "Livre ajouté aux favoris."]);
} catch (PDOException $e) {
    echo json_encode(["error" => "Erreur SQL : " . $e->getMessage()]);
}

?>
Suppression d'un livre des favoris
php
Copier
Modifier
<?php

require_once "../includes/config.php"; // Connexion à la base de données

session_start();

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["error" => "Utilisateur non connecté."]);
    exit;
}

// Récupérer les données JSON envoyées
$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['book_id'])) {
    echo json_encode(["error" => "Données invalides."]);
    exit;
}

$user_id = $_SESSION['user_id'];
$book_id = $data['book_id'];

try {
    $stmt = $pdo->prepare("DELETE FROM favorites WHERE user_id = ? AND book_id = ?");
    $stmt->execute([$user_id, $book_id]);

    echo json_encode(["success" => "Livre supprimé des favoris."]);
} catch (PDOException $e) {
    echo json_encode(["error" => "Erreur SQL : " . $e->getMessage()]);
}

?>
//A quoi sert ce code?
🔹 Si aucune recherche n'est faite, la page affiche une sélection de films populaires sous forme de JSON.
🔹 Si une recherche est faite, l'API Google Books est utilisée pour récupérer des livres en lien avec la recherche.
🔹 Ajout/Suppression de livres en favoris fonctionne comme prévu pour les utilisateurs connectés.

//Exemple de sortie JSON sans recherche :
json

<?php{    "movies": [
        {
            "title": "Black Panther",
            "director": "Ryan Coogler",
            "year": 2018,
            "poster": "https://image.tmdb.org/t/p/w500/uxzzxijgPIY7slzFvMotPv8wjKA.jpg"
        },
        {
            "title": "Iron Man",
            "director": "Jon Favreau",
            "year": 2008,
            "poster": "https://image.tmdb.org/t/p/w500/78lPtwv72eTNqFW9COBYI0dWDJa.jpg"
        },
        {
            "title": "Spiderman: No Way Home",
            "director": "Jon Watts",
            "year": 2021,
            "poster": "https://image.tmdb.org/t/p/w500/5vHssUeVe25bMrof1HyaPyWgaP.jpg"
        },
        {
            "title": "Harry Potter à l'école des sorciers",
            "director": "Chris Columbus",
            "year": 2001,
            "poster": "https://image.tmdb.org/t/p/w500/wuMc08IPKEatf9rnMNXvIDxqP4W.jpg"
        },
        {
            "title": "Venom",
            "director": "Ruben Fleischer",
            "year": 2018,
            "poster": "https://image.tmdb.org/t/p/w500/2uNW4WbgBXL25BAbXGLnLqX71Sw.jpg"
        }
    ]
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rechercher </title>
    <form id = "formulaire" action="search.php" method="POST">
    <input type="text" name="query" placeholder=" 🔍 Nom du livre ou genre..." required>
    <button type="submit">Rechercher</button>
</form>

</head>
<body>
    
</body>
</html>

