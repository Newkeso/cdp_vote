<?php
require('Config.php');

if (isset($_POST['id'])) {
    $id = $_POST['id'];

    $requete = "DELETE FROM produits WHERE id = ?";
    if ($stmt = mysqli_prepare($conn, $requete)) {
        mysqli_stmt_bind_param($stmt, 'i', $id);

        if (mysqli_stmt_execute($stmt)) {
            echo "Produit supprimé avec succès.";
        } else {
            echo "Erreur lors de la suppression du produit.";
        }
    } else {
        echo "Erreur de préparation de la requête.";
    }

    mysqli_stmt_close($stmt);
} else {
    echo "ID du produit non spécifié.";
}

mysqli_close($conn);
?>
