<?php
session_start();
require('Config.php');

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

// Récupérer les informations de l'utilisateur depuis la base de données
$username = $_SESSION['username'];
$query = "SELECT * FROM users WHERE username='$username'";
$result = mysqli_query($conn, $query);
$user = mysqli_fetch_assoc($result);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Récupérer les nouvelles informations depuis le formulaire
    $new_username = mysqli_real_escape_string($conn, stripslashes($_POST['username']));
    $new_email = mysqli_real_escape_string($conn, stripslashes($_POST['email']));
    $new_password = mysqli_real_escape_string($conn, stripslashes($_POST['password']));
    $new_ville = mysqli_real_escape_string($conn, stripslashes($_POST['ville']));
    $new_nom = mysqli_real_escape_string($conn, stripslashes($_POST['nom']));
    $new_prenom = mysqli_real_escape_string($conn, stripslashes($_POST['prenom']));

    // Hash le mot de passe si il a été changé
    if (!empty($new_password)) {
        $new_password = hash('sha256', $new_password);
    } else {
        // Si le mot de passe n'a pas été changé, garder l'ancien
        $new_password = $user['password'];
    }

    // Traitement de l'image de profil
    if (!empty($_FILES['image']['name'])) {
        $image_name = $_FILES['image']['name'];
        $image_temp = $_FILES['image']['tmp_name'];
        $image_folder = 'uploads/' . $image_name;
        move_uploaded_file($image_temp, $image_folder);
    } else {
        $image_folder = $user['image'];
    }

    // Mettre à jour les informations de l'utilisateur dans la base de données
    $update_query = "UPDATE users SET username='$new_username', email='$new_email', password='$new_password', ville='$new_ville', nom='$new_nom', prenom='$new_prenom', image='$image_folder' WHERE username='$username'";

    if (mysqli_query($conn, $update_query)) {
        $_SESSION['username'] = $new_username; // Mettre à jour le nom d'utilisateur dans la session
        $message = "Profil mis à jour avec succès";
    } else {
        $message = "Erreur lors de la mise à jour du profil: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier le profil</title>
   
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="Style-Airshine/profil.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" integrity="sha512-................." crossorigin="anonymous" />
</head>

<body>
    <nav>
        <div class="logo">
            <img class="logo" src="img/Design sans titre (3).png" alt="Logo">
        </div>

        <h5 class="brand">SHINE</h5>
        <div class="onglets">
            <a href="airshine.php">Air Shine's</a>
            <a href="Airshinexarl.php">Air Shine's X ARL</a>
            <a href="commandes.php">Mes commandes</a>
            <a href="logout.php">Déconnexion</a>
        </div>
    </nav>

    <div class="profile-container">
        <div class="profile-header">
            <img src="<?php echo htmlspecialchars($user['image']); ?>" alt="Image de profil">
            <div class="profile-info">
                <h2><?php echo htmlspecialchars($user['username']); ?></h2>
                <p><?php echo htmlspecialchars($user['email']); ?></p>
            </div>
            <a href="info_banque.php">
                <i class="fas fa-credit-card carte-bancaire-icon"></i>
            </a>
        </div>
        <form method="post" action="upload.php" enctype="multipart/form-data">
            <div class="profile-section">
                <label for="profileImage">Changer l'image de profil:</label>
                <input type="file" name="profileImage" accept="image/*">
                <input type="submit" name="submit" value="Mettre à jour l'image">
            </div>
        </form>

        <div class="profile-content">
            <?php if (isset($message)) {
                echo "<p class='message'>$message</p>";
            } ?>
            <form method="post" action="" enctype="multipart/form-data">
                <div class="profile-section">
                    <h3>Informations personnelles</h3>
                    <label for="username">Nom d'utilisateur:</label>
                    <input type="text" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required><br>
                    <label for="email">Email:</label>
                    <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required><br>
                    <label for="password">Nouveau mot de passe (laisser vide pour ne pas changer):</label>
                    <input type="password" name="password"><br>
                </div>
                <div class="profile-section">
                    <h3>Informations supplémentaires</h3>
                    <label for="ville">Ville:</label>
                    <input type="text" name="ville" value="<?php echo htmlspecialchars($user['ville']); ?>"><br>
                    <label for="nom">Nom:</label>
                    <input type="text" name="nom" value="<?php echo htmlspecialchars($user['nom']); ?>"><br>
                    <label for="prenom">Prénom:</label>
                    <input type="text" name="prenom" value="<?php echo htmlspecialchars($user['prenom']); ?>"><br>
                    <label for="image">Image de profil:</label>
                    <input type="file" name="image" accept="image/*"><br>
                </div>
                <input type="submit" value="Mettre à jour le profil">
            </form>
        </div>
    </div>
</body>

</html>
