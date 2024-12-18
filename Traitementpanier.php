    <?php
// Informations d'identification
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'formulaire_inscription');
 
// Connexion à la base de données MySQL 
$conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
 
// Vérifier la connexion
if($conn === false){
    die("ERREUR : Impossible de se connecter. " . mysqli_connect_error());
}
?>

// Récupération des données du formulaire
$nom = $_POST['nom'];
$prenom = $_POST['prenom'];
$ville = $_POST['ville'];
$adresse = $_POST['adresse'];
$codePostal = $_POST['code_postal'];
$telephone = $_POST['telephone'];
$carteBleue = $_POST['carte_bleue'].

<?php
// Traitement des données du formulaire...

// Redirection vers la page des commandes
header("Location: commandes.php");
exit;
?>


