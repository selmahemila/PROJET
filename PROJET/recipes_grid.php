<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Grille des Recettes</title>
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
            position: relative; /* Ajout d'une position relative pour positionner l'image absolument */
            border: gray;
        }

        .search-bar input[type="text"] {
           /* width: calc(70% - 40px); /* Ajustement de la largeur du champ de saisie */
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
        .filter-bar {
            text-align: center;
            padding: 25px 0;
            display: flex;
            align-items: center;
            justify-content: space-around;
            border-radius: 20px;
            position: relative;
        }

        .filter-bar:before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url("hero-image.jpg");
            background-repeat: no-repeat;
            background-size: cover;
            filter: blur(6px);
            z-index: -1;
            border-radius: 20px;
        }

        .filter-dropdown {
            margin: 0 10px;
            border-radius: 20px;
        }

        .filter-dropdown input[type="text"],
        .filter-dropdown input[type="date"] {
        padding: 8px; /* Réduire la taille du rembourrage */
        border-radius: 15px; /* Ajuster le rayon de la bordure */
        border: 1px solid #ccc;
        width: 150px; /* Ajuster la largeur */
        font-family: cursive, Helvetica, sans-serif;
}


        .filter-bar a {
            text-decoration: none;
            color: #fdfcff;
            font-weight: 500;
            transition: 0.6s ease;
            background-color: #a77b04;
            border-radius: 30px;
            padding: 10px;
        }
        .grid-container {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            padding: 20px;
        }
        .grid-item {
            border: 1px solid #ccc;
            padding: 16px;
            text-align: center;
        }
        .grid-item img {
            max-width: 100%;
            height: auto;
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
                <li><a href="page accueil.html">Accueil</a></li>
                <li><a href="page recette.html">Recettes</a></li>
                <li><a href="page a propos.html">À propos</a></li>
                <li><a href="page contact.html">Contact</a></li>
            </ul>
        </div><div class="profile">
          <a href="profile.php">Mon profile</a>  
      </div>
    </header>

    <section class="filter-bar">
        <h3>Filtrer :</h3>
        <div class="filter-dropdown">
            <input type="text" name="nom-utilisateur" placeholder="Nom d'utilisateur">
        </div>
        <div class="filter-dropdown">
            <input type="date" name="date" placeholder="Date">
        </div>
        <div class="filter-dropdown">
            <input type="text" name="commentaire" placeholder="Commentaire">
        </div>
        <div class="filter-dropdown">
            <input type="text" name="légumes" placeholder="Légumes">
        </div>
        <div class="filter-dropdown">
            <input type="text" name="fruits" placeholder="Fruits">
        </div>
        <div class="filter-dropdown">
            <input type="text" name="produits-laitiers" placeholder="Produits laitiers">
        </div>
        
        <a href="#" onclick="filterRecipes()">Filtrer</a>
    </section>

    <h1>Grille des Recettes</h1>
    <div class="grid-container">
        <?php
        $con = new mysqli("localhost", "root", "", "cuisinerecette");

        if ($con->connect_error) {
            die("Connection failed: " . $con->connect_error);
        }

        $sql = "SELECT r.id, r.name, r.image_path, u.firstname, u.lastname FROM recipes r JOIN utilisateur u ON r.user_id = u.id";
        $result = $con->query($sql);

        if ($result === false) {
            die("Erreur SQL: " . $con->error);
        }

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<div class='grid-item'>";
                echo "<h2><a href='recipe_detail.php?id=" . $row['id'] . "'>" . htmlspecialchars($row['name']) . "</a></h2>";
                echo "<img src='" . htmlspecialchars($row['image_path']) . "' alt='" . htmlspecialchars($row['name']) . "'>";
                echo "<p>Par " . htmlspecialchars($row['firstname']) . " " . htmlspecialchars($row['lastname']) . "</p>";
                echo "</div>";
            }
        } else {
            echo "Aucune recette trouvée.";
        }

        $con->close();
        ?>
    </div>
</body>
</html>

