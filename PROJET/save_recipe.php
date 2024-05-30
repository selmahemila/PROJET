<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Vérifiez si l'utilisateur est connecté
    if (!isset($_SESSION['user_id'])) {
        // Redirigez l'utilisateur vers une page de connexion ou affichez un message d'erreur
        exit("Vous devez être connecté pour soumettre une recette.");
    }

    // Récupérez l'ID de l'utilisateur à partir de la session
    $user_id = $_SESSION['user_id'];

    $con = new mysqli("localhost", "root", "", "cuisinerecette");

    // Vérification de la connexion
    if ($con->connect_error) {
        die("Connection failed: " . $con->connect_error);
    }

    // Préparation des données
    $name = $_POST['recipe-name'];
    $prep_time = $_POST['prep-time'];
    $cook_time = $_POST['cook-time'];
    $type = $_POST['recipe-type'];
    $publish_date = $_POST['recipe-date'];

    // Gestion de l'upload de fichier
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["recipe-image"]["name"]);
    move_uploaded_file($_FILES["recipe-image"]["tmp_name"], $target_file);

    // Insertion de la recette
    $stmt = $con->prepare("INSERT INTO recipes (name, prep_time, cook_time, type, publish_date, image_path, user_id) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssi", $name, $prep_time, $cook_time, $type, $publish_date, $target_file, $user_id);
    $stmt->execute();
    $recipe_id = $stmt->insert_id;
    $stmt->close();

    // Insertion des ingrédients
    $ingredients = $_POST['ingredients'];
    $stmt = $con->prepare("INSERT INTO ingredients (recipe_id, ingredient) VALUES (?, ?)");
    foreach ($ingredients as $ingredient) {
        $stmt->bind_param("is", $recipe_id, $ingredient);
        $stmt->execute();
    }
    $stmt->close();

    // Insertion des instructions
    $instructions = $_POST['instructions'];
    $stmt = $con->prepare("INSERT INTO instructions (recipe_id, instruction) VALUES (?, ?)");
    foreach ($instructions as $instruction) {
        $stmt->bind_param("is", $recipe_id, $instruction);
        $stmt->execute();
    }
    $stmt->close();

    // Fermeture de la connexion
    $con->close();

    // Redirection vers la grille des recettes
    header("Location: recipes_grid.php");
    exit();
}
?>
