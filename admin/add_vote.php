<?php
require('Config.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Vérifier si un fichier image a été téléchargé
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        // Détails du fichier image
        $imageTmpName = $_FILES['image']['tmp_name'];
        $imageType = $_FILES['image']['type'];

        // Vérification du type d'image
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        if (!in_array($imageType, $allowedTypes)) {
            die("Type de fichier non autorisé.");
        }

        // Lire le fichier image et convertir en base64
        $imageData = file_get_contents($imageTmpName);
        $imageBase64 = base64_encode($imageData);

        // Insérer les données dans la base de données
        $voteName = mysqli_real_escape_string($conn, $_POST['name']);
        $description = mysqli_real_escape_string($conn, $_POST['description']);

        // Gestion des options : les options sont envoyées sous forme de tableau
        if (isset($_POST['options']) && is_array($_POST['options'])) {
            $options = mysqli_real_escape_string($conn, implode(',', $_POST['options']));  // Options séparées par des virgules
        } else {
            $options = ''; // Valeur par défaut si aucune option n'est fournie
        }

        $query = "INSERT INTO votes (name, description, image, options) 
                  VALUES ('$voteName', '$description', '$imageBase64', '$options')";
        
        if (mysqli_query($conn, $query)) {
            echo "Vote ajouté avec succès.";
        } else {
            echo "Erreur lors de l'ajout du vote : " . mysqli_error($conn);
        }
    }
}

?>
