<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = isset($_POST['email']) ? $_POST['email'] : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';

    $con = new mysqli("localhost", "root", "", "cuisinerecette");
    if ($con->connect_error) {
        die("Failed to connect: " . $con->connect_error);
    } else {
        $stmt = $con->prepare("SELECT id, firstname, lastname FROM utilisateur WHERE email = ? AND password = ?");
        $stmt->bind_param("ss", $email, $password);
        $stmt->execute();
        $stmt_result = $stmt->get_result();

        if ($stmt_result->num_rows > 0) {
            $data = $stmt_result->fetch_assoc();

            // Stocker l'ID de l'utilisateur dans la session
            $_SESSION['user_id'] = $data['id'];

            // Redirection vers la page de profil
            header("Location: profile.php");
            exit();
        } else {
            echo "<h2>Email ou mot de passe invalide</h2>";
        }
    }
}
?>
