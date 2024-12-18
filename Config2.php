<?php
// Informations d'identification
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'sc2bepe8974_root');
define('DB_PASSWORD', 'Rayan2004');
define('DB_NAME', 'sc2bepe8974_formulaire_inscription');
 
// Connexion à la base de données MySQL 
$conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
 
// Vérifier la connexion
if($conn === false){
    die("ERREUR : Impossible de se connecter. " . mysqli_connect_error());
}
?>