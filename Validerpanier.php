<!DOCTYPE html>
<html>
<head>
  <title>Validation du Panier</title>
  <link rel="stylesheet" type="text/css" href="Style-Airshine/validerpanier.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" integrity="sha512-................." crossorigin="anonymous" />
</head>
<body>
  <nav>
    <a href="profil.php" class="back-link"><i class="fas fa-arrow-left"></i> Retour</a>
    <!-- Autres éléments de navigation -->
  </nav>

  <h1>Validation du Panier</h1>

  <form action="traitement_formulaire.php" method="post" class="validation-form">
    <label for="nom"><i class="fas fa-user"></i> Nom :</label>
    <input type="text" id="nom" name="nom" required>
    
    <label for="prenom"><i class="fas fa-user"></i> Prénom :</label>
    <input type="text" id="prenom" name="prenom" required>
    
    <label for="ville"><i class="fas fa-map-marker-alt"></i> Ville :</label>
    <input type="text" id="ville" name="ville" required>
    
    <label for="adresse"><i class="fas fa-map-marked-alt"></i> Adresse de facturation :</label>
    <input type="text" id="adresse" name="adresse" required>
    
    <label for="code_postal"><i class="fas fa-mail-bulk"></i> Code Postal :</label>
    <input type="text" id="code_postal" name="code_postal" required>
    
    <label for="telephone"><i class="fas fa-phone-alt"></i> Numéro de téléphone :</label>
    <input type="text" id="telephone" name="telephone" required>
    
    <label for="carte_bleue"><i class="far fa-credit-card"></i> Numéro de carte bleue :</label>
    <input type="text" id="carte_bleue" name="carte_bleue" required>
    
    <label for="code_securite"><i class="fas fa-lock"></i> Code de sécurité :</label>
    <input type="text" id="code_securite" name="code_securite" required>
    
    <button type="button" class="cf" onclick="window.location.href = 'commandes.php';"><i class="fas fa-check-circle"></i> Valider le paiement</button>
  </form>

</body>
</html>
