<?php
 
 define('DB_SERVER', 'localhost');
 define('DB_USERNAME', 'root');
 define('DB_PASSWORD', '');
 define('DB_NAME', 'formulaire_inscription');

 // Connexion à la base de données MySQL 
 $conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

 // Vérifier la connexion
 if ($conn === false) {
     die("ERREUR : Impossible de se connecter. " . mysqli_connect_error());
 }

if (isset($_POST['id']) && !empty($_POST['id'])) {
  $userId = $_POST['id'];

  // Préparez la requête SQL pour supprimer l'utilisateur
  $requete = "DELETE FROM users WHERE id = $userId";

  // Exécutez la requête
  if (mysqli_query($conn, $requete)) {
    echo "Utilisateur supprimé avec succès.";
  } else {
    echo "Erreur lors de la suppression de l'utilisateur : " . mysqli_error($conn);
  }
} else {
  echo "ID d'utilisateur non spécifié.";
}
?>
