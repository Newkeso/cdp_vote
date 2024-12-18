<?php
session_start();
require('config.php');

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

if (isset($_POST['submit'])) {
    $target_dir = "uploads/";
    // Assurez-vous que le répertoire 'uploads' existe
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    $target_file = $target_dir . basename($_FILES["profileImage"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Vérifier si le fichier est une image réelle ou une fausse image
    $check = getimagesize($_FILES["profileImage"]["tmp_name"]);
    if ($check !== false) {
        $uploadOk = 1;
    } else {
        echo "Le fichier n'est pas une image.<br>";
        $uploadOk = 0;
    }

    // Vérifier si le fichier existe déjà
    if (file_exists($target_file)) {
        echo "Désolé, le fichier existe déjà.<br>";
        $uploadOk = 0;
    }

    // Vérifier la taille du fichier
    if ($_FILES["profileImage"]["size"] > 500000) {
        echo "Désolé, votre fichier est trop volumineux.<br>";
        $uploadOk = 0;
    }

    // Autoriser certains formats de fichier
    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
        echo "Désolé, seuls les fichiers JPG, JPEG, PNG et GIF sont autorisés.<br>";
        $uploadOk = 0;
    }

    // Vérifier si $uploadOk est défini à 0 par une erreur
    if ($uploadOk == 0) {
        echo "Désolé, votre fichier n'a pas été téléchargé.<br>";
    } else {
        // Essayez de déplacer le fichier téléchargé vers le répertoire cible
        if (move_uploaded_file($_FILES["profileImage"]["tmp_name"], $target_file)) {
            echo "Le fichier " . htmlspecialchars(basename($_FILES["profileImage"]["name"])) . " a été téléchargé.<br>";

            // Mettre à jour le chemin de l'image dans la base de données
            $userId = $user['id']; // Récupérer l'ID utilisateur depuis la session ou la requête précédente
            $sql = "UPDATE users SET image='$target_file' WHERE id=$userId";

            if ($conn->query($sql) === TRUE) {
                echo "Image de profil mise à jour avec succès.<br>";
            } else {
                echo "Erreur lors de la mise à jour de l'image de profil: " . $conn->error . "<br>";
            }
        } else {
            echo "Désolé, une erreur s'est produite lors du téléchargement de votre fichier.<br>";
            // Ajout d'un message d'erreur pour move_uploaded_file
            echo "Erreur: " . error_get_last()['message'] . "<br>";
        }
    }
}

$conn->close();
?>
