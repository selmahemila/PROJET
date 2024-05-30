<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <style>
        body {
            font-family: cursive, Helvetica, sans-serif;
            margin: 0;
            padding: 0;
        }

        header {
            background-color: #fffdfde8;
            padding: 10px 0;
            text-align: center;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            margin-left: 20px;
        }

        .search-bar {
            flex: 1;
            margin: 0 20px;
            position: relative;
        }

        .search-bar input[type="text"] {
            width: 90%;
            padding: 10px;
            border-radius: 20px;
            border: 1px solid #ccc;
            font-family: cursive, Helvetica, sans-serif;
        }

        .search-bar .search-icon {
            position: absolute;
            top: 50%;
            right: 40px;
            transform: translateY(-50%);
            cursor: pointer;
        }

        .menu {
            margin-right: 20px;
        }

        .menu ul {
            list-style-type: none;
            margin: 0;
            padding: 0;
            display: flex;
        }

        .menu ul li {
            margin-left: 20px;
        }

        .menu ul li a {
            text-decoration: none;
            color: #140131;
            text-transform: uppercase;
            font-weight: 500;
            transition: 0.6s ease;
        }

        .menu ul li a:hover {
            color: #000;
        }

        .profile a {
            text-decoration: none;
            color: #fefdff;
            font-weight: 500;
            transition: 0.6s ease;
            background-color: #a77b04;
            border-radius: 30px;
            padding: 13px;
        }

        .profile a:hover {
            color: #887878;
        }

        #profile-info {
            padding: 20px;
        }

        #profile-info p {
            font-size: 18px;
        }
    </style>
</head>
<body>
<header>
    <div class="logo">
        <img src="LOGO1.png" alt="Logo" height="80px" width="80%">
    </div>
    <div class="search-bar">
        <input type="text" placeholder="Recherchez des recettes...">
        <div class="search-icon">
            <a href="#"><img src="chercher.png" alt="Rechercher"></a>
        </div>
    </div>
    <div class="menu">
        <ul>
            <li><a href="accueil profile.html">Accueil</a></li>
            <li><a href="recipes_grid.php">Recettes</a></li>
            <li><a href="page_a_propos.html">À propos</a></li>
            <li><a href="page_contact.html">Contact</a></li>
        </ul>
    </div>
    <div class="profile">
        <a href="profilelog.php">Mon profil</a>
    </div>
</header>
<h1>Informations personnelles</h1>
<div id="profile-info">
    <?php
    // Vérifiez si l'utilisateur est connecté en vérifiant s'il y a une session utilisateur
    if (isset($_SESSION['utilisateur'])) {
        $utilisateur = $_SESSION['utilisateur'];
        // Affichez les informations de l'utilisateur
        echo "<p><strong>ID Utilisateur: </strong>" . htmlspecialchars($utilisateur['id_utilisateur']) . "</p>";
        echo "<p><strong>Nom: </strong>" . htmlspecialchars($utilisateur['lastname']) . "</p>";
        echo "<p><strong>Prénom: </strong>" . htmlspecialchars($utilisateur['firstname']) . "</p>";
        echo "<p><strong>Email: </strong>" . htmlspecialchars($utilisateur['email']) . "</p>";
        echo "<p><strong>Mot de passe: </strong>" . htmlspecialchars($utilisateur['password']) . "</p>";
    } else {
        // Redirigez l'utilisateur vers la page de connexion s'il n'est pas connecté
        header("Location: login.php");
        exit(); // Assurez-vous de terminer le script après la redirection
    }
    ?>
    <!-- Vous pouvez ajouter d'autres informations ici si nécessaire -->
</div>
</body>
</html>
