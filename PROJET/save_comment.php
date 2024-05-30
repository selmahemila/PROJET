<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Vérifiez si l'utilisateur est connecté
    if (!isset($_SESSION['user_id'])) {
        exit("Vous devez être connecté pour laisser un commentaire.");
    }

    $user_id = $_SESSION['user_id'];
    $recipe_id = $_POST['recipe_id'];
    $comment = $_POST['comment'];

    $con = new mysqli("localhost", "root", "", "cuisinerecette");

    if ($con->connect_error) {
        die("Connection failed: " . $con->connect_error);
    }

    // Insertion du commentaire
    $stmt = $con->prepare("INSERT INTO comments (recipe_id, user_id, comment) VALUES (?, ?, ?)");
    $stmt->bind_param("iis", $recipe_id, $user_id, $comment);
    $stmt->execute();
    $stmt->close();

    // Fermeture de la connexion
    $con->close();

    // Redirection vers les détails de la recette
    header("Location: recipe_detail.php?id=" . $recipe_id);
    exit();
}
?>
