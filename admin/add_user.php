<?php
require('Config.php');
require_once('vendor/autoload.php'); // Assurez-vous que Composer a été installé et que le fichier autoload est inclus

use SendinBlue\Client\Api\TransactionalEmailsApi;
use SendinBlue\Client\Model\SendSmtpEmail;
use SendinBlue\Client\Configuration;
use GuzzleHttp\Client;

// Configurez l'API de Brevo avec votre clé API
$config = Configuration::getDefaultConfiguration()->setApiKey('api-key', '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');
$apiInstance = new TransactionalEmailsApi(
    new Client(),
    $config
);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupération des données du formulaire
    $username = $_POST['username'];
    $email = $_POST['email'];
    $type = $_POST['type'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $ville = $_POST['ville'];
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];

    // Insérer l'utilisateur dans la base de données
    $requete = "INSERT INTO users (username, email, type, password, ville, nom, prenom) 
                VALUES ('$username', '$email', '$type', '$password', '$ville', '$nom', '$prenom')";

    if (mysqli_query($conn, $requete)) {
        // Créer le contenu de l'email avec animations et décorations de Noël
        $subject = "Bienvenue sur CDP Votes";
        $htmlMessage = "
        <html>
        <head>
            <style>
                /* Fond animé neige */
                body {
                    background: #fefefe;
                    font-family: 'Arial', sans-serif;
                    padding: 0;
                    margin: 0;
                    overflow-x: hidden;
                    position: relative;
                }

                .snowflakes {
                    position: absolute;
                    top: 0;
                    left: 0;
                    width: 100%;
                    height: 100%;
                    pointer-events: none;
                    z-index: 10;
                }

                .snowflake {
                    position: absolute;
                    font-size: 2rem;
                    opacity: 0.8;
                    animation: snow 10s linear infinite;
                }

                @keyframes snow {
                    0% {
                        transform: translateY(-100vh);
                        opacity: 1;
                    }
                    100% {
                        transform: translateY(100vh);
                        opacity: 0.5;
                    }
                }

                /* Animation du logo en haut */
                header {
                    background-color: #2d6a4f;
                    padding: 40px 20px;
                    text-align: center;
                    color: white;
                    position: relative;
                    z-index: 5;
                }

                header img {
                    margin-bottom: 20px;
                    max-width: 200px;
                    animation: logoAnimate 5s ease-in-out infinite;
                }

                @keyframes logoAnimate {
                    0% { transform: scale(1); }
                    50% { transform: scale(1.1); }
                    100% { transform: scale(1); }
                }

                /* Mise en forme du texte */
                h1 {
                    font-size: 36px;
                    color: #ffdfd3;
                }

                h2 {
                    color: #f56c6c;
                }

                /* En-tête et contenu */
                .content {
                    padding: 20px;
                    text-align: center;
                    z-index: 1;
                }

                /* Bouton d'action */
                .cta-button {
                    background-color: #ff4f6c;
                    padding: 12px 30px;
                    color: white;
                    text-decoration: none;
                    border-radius: 25px;
                    display: inline-block;
                    margin-top: 20px;
                    font-size: 18px;
                    transition: background-color 0.3s;
                }

                .cta-button:hover {
                    background-color: #ff3a4d;
                }

                /* Footer */
                footer {
                    background-color: #2d6a4f;
                    color: white;
                    padding: 15px;
                    text-align: center;
                }
            </style>
        </head>
        <body>
            <div class='snowflakes'>
                <span class='snowflake'>❄️</span>
                <span class='snowflake'>❄️</span>
                <span class='snowflake'>❄️</span>
                <span class='snowflake'>❄️</span>
                <span class='snowflake'>❄️</span>
            </div>
            
            <header>
                <img src='https://cdn.discordapp.com/attachments/1073409893668757626/1316738614360801330/Design_sans_titre_31.png?ex=675c23e5&is=675ad265&hm=020d706d844fd14c47e808445fa666d8d28294bf5fb1d0c874576a2239417258&' alt='Logo'>
                <h1>Joyeux Noël, $prenom $nom ! 🎄</h1>
                <p>Bienvenue sur notre plateforme CDP Votes !</p>
            </header>
            
            <div class='content'>
                <h2>Vos informations de compte :</h2>
                <p><strong>Nom d'utilisateur :</strong> $username</p>
                <p><strong>Mot de passe :</strong> $_POST[password] </p>
                <p>Merci de vous joindre à notre aventure festive ! 🎅</p>
                <a href='login.php' class='cta-button'>Connectez-vous ici</a>
            </div>

            <footer>
                <p>&copy; 2024 CDP Votes. Tous droits réservés.</p>
            </footer>
        </body>
        </html>";

        // Créer l'objet Email
        $sendSmtpEmail = new SendSmtpEmail([
            'sender' => ['email' => 'rhazlaouirayan@gmail.com'],
            'to' => [['email' => $email]],
            'subject' => $subject,
            'htmlContent' => $htmlMessage // Intégration du contenu HTML
        ]);

        try {
            // Envoi de l'email via l'API de Brevo
            $apiInstance->sendTransacEmail($sendSmtpEmail);
            echo "Nouvel utilisateur ajouté avec succès et email envoyé.";
        } catch (Exception $e) {
            echo 'Erreur lors de l\'envoi de l\'email : ', $e->getMessage(), PHP_EOL;
        }
    } else {
        echo "Erreur : " . $requete . "<br>" . mysqli_error($conn);
    }

    mysqli_close($conn);

    // Rediriger vers la page du panneau après 3 secondes pour permettre à l'utilisateur de voir le message
    header("Refresh: 20; url=pannel.php");
    exit();
}
?>
