<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Projet final</title>
  <meta charset="utf-8">
  <link rel="stylesheet" href="style.css">
  <link rel="stylesheet" href="Style-Airshine\register.css">
  <script src="Js_airshine\register.js" defer></script>
  <link rel="icon" type="C:\wamp64\www\img\logoicone.ico.png" href="favicon.ico">
</head>

<head>

  <nav>
    <div class="tete">
      <div class="logo">
        <img class="logo" src="img/Design sans titre (3).png" alt="Logo">
      </div>
    </div>



    <div class="tete2">
      <h5 class="brand">SHINE</h5>
    </div>

    <div class="onglets">

    </div>

  </nav>
</head>


<body>

<div class="product-container">
        <form class="box" action="" method="post" name="login" onsubmit="return validatePassword()">
            <h1 class="box-logo box-title"></h1>
            <h1 class="box-title">S'inscrire</h1>
            <div class="form-group">
                <input type="text" class="box-input" name="username" placeholder="Nom d'utilisateur" required />
            </div>
            <div class="form-group">
                <input type="text" class="box-input" name="email" placeholder="Email" required />
            </div>
            <div class="form-group">
                <input type="password" class="box-input" id="password" name="password" placeholder="Mot de passe" required />
                <div id="error-message" class="error-message">Le mot de passe doit contenir au moins 8 caractères, une majuscule et un chiffre.</div>
            </div>
            <div class="form-group">
                <input type="submit" name="submit" value="S'inscrire" class="box-button" />
            </div>
        </form>
    </div>


  </p>
  </div>
  <?php
require('config.php');

if (isset($_REQUEST['username'], $_REQUEST['email'], $_REQUEST['password'])) {
    // Récupérer le nom d'utilisateur
    $username = stripslashes($_REQUEST['username']);
    $username = mysqli_real_escape_string($conn, $username);

    // Récupérer l'email
    $email = stripslashes($_REQUEST['email']);
    $email = mysqli_real_escape_string($conn, $email);

    // Récupérer le mot de passe
    $password = stripslashes($_REQUEST['password']);
    $password = mysqli_real_escape_string($conn, $password);

    // Vérification des conditions du mot de passe
    if (!preg_match('/[A-Z]/', $password) || !preg_match('/\d/', $password) || strlen($password) < 8) {
        echo "<script>alert('Le mot de passe doit contenir au moins 8 caractères, une majuscule et un chiffre.');</script>";
        echo "<div class='product-container'>
                <h3>Le mot de passe ne respecte pas les conditions.</h3>
                <p><a href='register.php'>Retour à l'inscription</a></p>
              </div>";
        exit();
    }

    // Insérer l'utilisateur dans la base de données
    $query = "INSERT INTO users (username, email, type, password)
              VALUES ('$username', '$email', 'user', '" . hash('sha256', $password) . "')";
    $res = mysqli_query($conn, $query);

    if ($res) {
        echo "<div class='product-container'>
                <h3>Vous êtes inscrit avec succès.</h3>
                <p>Cliquez ici pour vous <a href='login.php'>connecter</a></p>
              </div>";
    } else {
        echo "Une erreur s'est produite lors de l'inscription.";
    }
}
?>







  <?php if (!empty($message)) { ?>
    <p class="errorMessage"><?php echo $message; ?></p>
  <?php } ?>
  </form>


  <footer>

    <h1>Nos services</h1>
    <div class="services">

      <div class="service">
        <h3>Livraison gratuite</h3>
        <p>Nos magasins sont présents dans la France entière, vous pouvez commander ou bien trouver la paire de vos rêves sur place dans nos magasins !</p>
      </div>

      <div class="service">
        <h3>Paiement en ligne</h3>
        <p>Visa, PaySafeCard, AmazonPay, Paypal, Klarna,... Nous acceptons tous types de paiements en ligne de façon sécurisée !</p>
      </div>

      <div class="service">
        <h3>Aimé ou remboursé</h3>
        <p>Bien qu'il n'y ait aucune chance que vous n'appréciez pas nos produits, nous acceptons tout de même de vous rembourser si vous n'êtes pas satisfait !</p>
      </div>

    </div>

    <p id="contact">Contact : 06 70 22 72 72 | &copy; 2023, Belaid, Rhazlaoui.</p>
  </footer>







</body>

</html>

</body>

</html>