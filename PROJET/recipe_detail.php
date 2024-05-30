<?php
session_start();
$con = new mysqli("localhost", "root", "", "cuisinerecette");

if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

if (!isset($_GET['id'])) {
    die("Recette non trouvée.");
}

$recipe_id = $_GET['id'];

// Préparer la requête pour obtenir les détails de la recette
$stmt = $con->prepare("SELECT r.name, r.prep_time, r.cook_time, r.type, r.publish_date, r.image_path, u.firstname, u.lastname FROM recipes r JOIN utilisateur u ON r.user_id = u.id WHERE r.id = ?");
if (!$stmt) {
    die("Erreur lors de la préparation de la requête : " . $con->error);
}
$stmt->bind_param("i", $recipe_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    die("Recette non trouvée.");
}

$recipe = $result->fetch_assoc();
$stmt->close();

// Préparer la requête pour obtenir les ingrédients
$ingredients_stmt = $con->prepare("SELECT ingredient FROM ingredients WHERE recipe_id = ?");
if (!$ingredients_stmt) {
    die("Erreur lors de la préparation de la requête pour les ingrédients : " . $con->error);
}
$ingredients_stmt->bind_param("i", $recipe_id);
$ingredients_stmt->execute();
$ingredients_result = $ingredients_stmt->get_result();
$ingredients = [];
while ($row = $ingredients_result->fetch_assoc()) {
    $ingredients[] = $row['ingredient'];
}
$ingredients_stmt->close();

// Préparer la requête pour obtenir les instructions
$instructions_stmt = $con->prepare("SELECT instruction FROM instructions WHERE recipe_id = ?");
if (!$instructions_stmt) {
    die("Erreur lors de la préparation de la requête pour les instructions : " . $con->error);
}
$instructions_stmt->bind_param("i", $recipe_id);
$instructions_stmt->execute();
$instructions_result = $instructions_stmt->get_result();
$instructions = [];
while ($row = $instructions_result->fetch_assoc()) {
    $instructions[] = $row['instruction'];
}
$instructions_stmt->close();

// Préparer la requête pour obtenir les commentaires
$comments_stmt = $con->prepare("SELECT c.comment, u.firstname, u.lastname FROM comments c JOIN utilisateur u ON c.user_id = u.id WHERE c.recipe_id = ?");
if (!$comments_stmt) {
    die("Erreur lors de la préparation de la requête pour les commentaires : " . $con->error);
}
$comments_stmt->bind_param("i", $recipe_id);
$comments_stmt->execute();
$comments_result = $comments_stmt->get_result();
$comments = [];
while ($row = $comments_result->fetch_assoc()) {
    $comments[] = $row;
}
$comments_stmt->close();

$con->close();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails de la recette</title>
</head>
<body>
    <h1><?php echo htmlspecialchars($recipe['name']); ?></h1>
    <img src="<?php echo htmlspecialchars($recipe['image_path']); ?>" alt="<?php echo htmlspecialchars($recipe['name']); ?>" style="max-width: 100%; height: auto;">
    <p>Par: <?php echo htmlspecialchars($recipe['firstname']) . ' ' . htmlspecialchars($recipe['lastname']); ?></p>
    <p>Temps de préparation: <?php echo htmlspecialchars($recipe['prep_time']); ?></p>
    <p>Temps de cuisson: <?php echo htmlspecialchars($recipe['cook_time']); ?></p>
    <p>Type: <?php echo htmlspecialchars($recipe['type']); ?></p>
    <p>Date de publication: <?php echo htmlspecialchars($recipe['publish_date']); ?></p>

    <h2>Ingrédients</h2>
    <ul>
        <?php foreach ($ingredients as $ingredient): ?>
            <li><?php echo htmlspecialchars($ingredient); ?></li>
        <?php endforeach; ?>
    </ul>

    <h2>Instructions</h2>
    <ol>
        <?php foreach ($instructions as $instruction): ?>
            <li><?php echo htmlspecialchars($instruction); ?></li>
        <?php endforeach; ?>
    </ol>

    <h2>Commentaires</h2>
    <ul>
        <?php foreach ($comments as $comment): ?>
            <li><?php echo htmlspecialchars($comment['comment']); ?> - <?php echo htmlspecialchars($comment['firstname']) . ' ' . htmlspecialchars($comment['lastname']); ?></li>
        <?php endforeach; ?>
    </ul>

    <?php if (isset($_SESSION['user_id'])): ?>
        <h2>Laisser un commentaire</h2>
        <form action="save_comment.php" method="post">
            <input type="hidden" name="recipe_id" value="<?php echo $recipe_id; ?>">
            <textarea name="comment" rows="4" required></textarea>
            <button type="submit">Envoyer</button>
        </form>
    <?php else: ?>
        <p><a href="login.php">Connectez-vous</a> pour laisser un commentaire.</p>
    <?php endif; ?>
</body>
</html>
