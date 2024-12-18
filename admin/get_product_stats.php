<?php
require('Config.php');

// Requête pour obtenir les statistiques des produits
$requeteStats = "
SELECT p.nom, COUNT(c.produits) as quantite_vendue 
FROM commandes c 
JOIN produits p ON JSON_CONTAINS(c.produits, CAST(p.id_produit AS CHAR), '$') 
GROUP BY p.nom 
ORDER BY quantite_vendue DESC
";
$resultatStats = mysqli_query($conn, $requeteStats);

$labels = [];
$quantities = [];

while ($row = mysqli_fetch_assoc($resultatStats)) {
    $labels[] = $row['nom'];
    $quantities[] = $row['quantite_vendue'];
}

echo json_encode(['labels' => $labels, 'quantities' => $quantities]);
?>
