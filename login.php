<?php
session_start();
require('config.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Débogage : Afficher les données reçues
    error_log("Données reçues : " . print_r($_POST, true)); // Log des données reçues
    $username = stripslashes($_POST['username']);
    $username = mysqli_real_escape_string($conn, $username);
    $password = stripslashes($_POST['password']);
    $password = mysqli_real_escape_string($conn, $password);

    // Débogage : Vérifier les variables après nettoyage
    error_log("Nom d'utilisateur après nettoyage : " . $username);
    error_log("Mot de passe après nettoyage : " . $password); // Attention : Ne jamais logguer des mots de passe en production

    $hashed_password = hash('sha256', $password);

    // Débogage : Afficher le mot de passe haché
    error_log("Mot de passe haché : " . $hashed_password);

    $query = "SELECT * FROM users WHERE username='$username' AND password='$hashed_password'";
    // Débogage : Afficher la requête SQL générée
    error_log("Requête SQL : " . $query);

    $result = mysqli_query($conn, $query);

    // Vérifier si la requête a échoué
    if (!$result) {
        error_log("Erreur de la requête SQL : " . mysqli_error($conn));
    }

    if (mysqli_num_rows($result) == 1) {
        $user = mysqli_fetch_assoc($result);
        
        // Débogage : Afficher les informations utilisateur
        error_log("Session : " . print_r($_SESSION, true)); // Affiche les données de session


        $_SESSION['username'] = $user['username'];
        $_SESSION['user_id'] = $user['id'];

        if ($user['type'] === 'admin') {
            error_log("Redirection vers le panneau admin");
            header('Location: admin/pannel.php');
        } else {
            error_log("Redirection vers la page d'accueil");
            error_log("Session définie, redirection en cours...");
            header('Location: index.php');
        }
        exit();
    } else {
        // Si aucune ligne n'est retournée, afficher l'erreur
        $error = "Nom d'utilisateur ou mot de passe incorrect.";
        error_log("Erreur de connexion : " . $error);
    }
}

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - AirShine</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <style>
        body {
            background: linear-gradient(45deg, #ffffff, #ffd700);
            animation: gradientBG 8s ease infinite;
            background-size: 400% 400%;
        }

        @keyframes gradientBG {
            0% {
                background-position: 0% 50%;
            }

            50% {
                background-position: 100% 50%;
            }

            100% {
                background-position: 0% 50%;
            }
        }
    </style>
</head>

<body class="flex items-center justify-center h-screen">
    <div class="w-full max-w-lg p-6 bg-white rounded-lg shadow-lg relative"
        id="main-container">
        <!-- Section de sélection des rôles -->
        <div id="role-selection" class="flex flex-col items-center">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">Bienvenue sur CDP Votes</h2>
            <p class="text-gray-600 mb-4">Choisissez votre rôle pour continuer</p>
            <div class="grid grid-cols-2 gap-6">
                <!-- Icône Élève -->
                <div onclick="showForm('student')" class="cursor-pointer flex flex-col items-center text-center">
                    <div class="w-24 h-24 bg-yellow-500 rounded-full flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 text-white" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 14l9-5-9-5-9 5 9 5zm0 7l-9-5 9 5 9-5-9 5z" />
                        </svg>
                    </div>
                    <p class="mt-2 text-gray-700">Élève</p>
                </div>
                <!-- Icône SuperAdmin -->
                <div onclick="showForm('admin')" class="cursor-pointer flex flex-col items-center text-center">
                    <div class="w-24 h-24 bg-red-500 rounded-full flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 text-white" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M18 9a6 6 0 00-12 0m12 0a6 6 0 01-12 0m12 0v4m0 4H6m6 4H6m12-4h-6" />
                        </svg>
                    </div>
                    <p class="mt-2 text-gray-700">SuperAdmin</p>
                </div>
            </div>
        </div>

        <!-- Section de formulaire -->
        <div id="login-form" class="hidden">
            <button onclick="hideForm()" class="text-sm text-blue-500 hover:underline mb-4">
                &larr; Retour
            </button>
            <h2 id="form-title" class="text-2xl font-bold text-gray-800 mb-6"></h2>
            <form method="POST" action="">
                <div class="mb-4">
                    <label for="username" class="block text-sm font-medium text-gray-700">Nom d'utilisateur</label>
                    <input type="text" name="username" id="username"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-yellow-500 focus:ring-yellow-500"
                        placeholder="Votre nom d'utilisateur" required>
                </div>
                <div class="mb-4">
                    <label for="password" class="block text-sm font-medium text-gray-700">Mot de passe</label>
                    <input type="password" name="password" id="password"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-yellow-500 focus:ring-yellow-500"
                        placeholder="Votre mot de passe" required>
                </div>
                <input type="hidden" name="role" id="user-role">
                <button type="submit"
                    class="w-full py-2 px-4 bg-yellow-500 text-white rounded-md shadow hover:bg-yellow-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-400">
                    Connexion
                </button>
                <p class="text-sm text-gray-600 mt-4 text-center">
                    Vous êtes nouveau ici ? <a href="register.php" class="text-yellow-500 font-medium hover:underline">S'inscrire</a>
                </p>
            </form>
        </div>
    </div>

    <script>
        const showForm = (role) => {
            const roleSelection = document.getElementById('role-selection');
            const loginForm = document.getElementById('login-form');
            const formTitle = document.getElementById('form-title');
            const userRoleInput = document.getElementById('user-role');

            formTitle.innerText = role === 'student' ? 'Connexion Élève' : 'Connexion SuperAdmin';
            userRoleInput.value = role;

            gsap.to(roleSelection, { opacity: 0, duration: 0.5, onComplete: () => roleSelection.classList.add('hidden') });
            gsap.to(loginForm, { opacity: 1, duration: 0.5, onStart: () => loginForm.classList.remove('hidden') });
        };

        const hideForm = () => {
            const roleSelection = document.getElementById('role-selection');
            const loginForm = document.getElementById('login-form');

            gsap.to(loginForm, { opacity: 0, duration: 0.5, onComplete: () => loginForm.classList.add('hidden') });
            gsap.to(roleSelection, { opacity: 1, duration: 0.5, onStart: () => roleSelection.classList.remove('hidden') });
        };
    </script>
</body>

</html>
