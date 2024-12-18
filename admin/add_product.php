<?php
require('Config.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $nom = $_POST['nom'];
  $prix = $_POST['prix'];
  
  $image = $_FILES['image']['tmp_name'];
  $imgContent = addslashes(file_get_contents($image));

  $requete = "INSERT INTO produits (nom, prix, image) VALUES ('$nom', '$prix', '$imgContent')";

  if (mysqli_query($conn, $requete)) {
    echo "Nouveau produit ajouté avec succès.";
  } else {
    echo "Erreur : " . $requete . "<br>" . mysqli_error($conn);
  }
  
  mysqli_close($conn);
}
?>
