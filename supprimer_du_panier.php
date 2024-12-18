<?php
session_start();
require('Config.php');

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['produitCle'])) {
    $produitCle = mysqli_real_escape_string($conn, $_GET['produitCle']);

    // Supprimer le produit du panier en base de données
    $userId = $_SESSION['user_id'];
    $requete = "DELETE FROM panier WHERE user_id = $userId AND CONCAT(produit, '-', taille) = '$produitCle'";
    mysqli_query($conn, $requete);

    if (mysqli_affected_rows($conn) > 0) {
        echo "Produit supprimé du panier avec succès.";
    } else {
        echo "Erreur lors de la suppression du produit du panier.";
    }

    mysqli_close($conn);
} else {
    echo "Méthode de requête incorrecte ou données manquantes.";
}
?>
