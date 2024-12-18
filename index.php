<?php
session_start();
require('Config.php');

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


// Redirection si l'utilisateur n'est pas connecté
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

// Récupération des informations utilisateur
$username = $_SESSION['username'];
$query = "SELECT * FROM users WHERE username='$username'";
$result = mysqli_query($conn, $query);
$user = mysqli_fetch_assoc($result);

// Chemin de l'image de profil
$imagePath = !empty($user['image']) ? $user['image'] : 'default-profile.png';

// Récupération des votes depuis la base de données
$votesQuery = "SELECT * FROM votes";
$votesResult = mysqli_query($conn, $votesQuery);
$votes = mysqli_fetch_all($votesResult, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CDP Votes</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.12.0/dist/cdn.min.js" defer></script>
    <style>
        body {
            background: linear-gradient(135deg, #fff7d6, #ffffff);
            animation: gradientBackground 6s infinite alternate;
        }

        @keyframes gradientBackground {
            0% {
                background: linear-gradient(135deg, #fff7d6, #ffffff);
            }
            100% {
                background: linear-gradient(135deg, #ffffff, #fff7d6);
            }
        }
    </style>
</head>

<body class="min-h-screen flex flex-col font-sans" x-data="{
    showModal: false,
    selectedVote: null,
    showLoader: false,
    showThankYou: false,
    submitVote() {
        this.showModal = false;
        this.showLoader = true;
        setTimeout(() => {
            this.showLoader = false;
            this.showThankYou = true;
            setTimeout(() => {
                this.showThankYou = false;
            }, 3000);
        }, 2000);
    }
}">
    <!-- Navbar -->
    <nav class="bg-white shadow-lg">
        <div class="max-w-6xl mx-auto px-4 py-4 flex justify-between items-center">
            <h1 class="text-2xl font-bold text-yellow-600">CDP Votes</h1>
            <div class="flex items-center space-x-4">
                <a href="profil.php" class="flex items-center text-gray-700 hover:text-yellow-500">
                    <img src="<?php echo htmlspecialchars($imagePath); ?>" alt="Profile Image" class="w-10 h-10 rounded-full mr-2">
                    <span><?php echo htmlspecialchars($username); ?></span>
                </a>
                <a href="logout.php" class="text-gray-700 hover:text-red-500">Déconnexion</a>
            </div>
        </div>
    </nav>

    <!-- Liste des votes -->
    <section class="py-12">
        <div class="max-w-6xl mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <?php foreach ($votes as $vote): ?>
                    <div
                        class="card flex flex-col bg-white shadow-lg rounded-lg transform transition-transform duration-300 hover:scale-105 hover:shadow-xl">
                        <img src="data:image/jpeg;base64,<?php echo htmlspecialchars($vote['image']); ?>" alt="<?php echo htmlspecialchars($vote['name']); ?>"
                            class="h-60 object-cover rounded-t-lg">
                        <div class="p-4 flex-grow">
                            <h2 class="text-2xl font-bold text-gray-800">
                                <?php echo htmlspecialchars($vote['name']); ?>
                            </h2>
                            <p class="text-gray-600 mt-2">
                                <?php echo htmlspecialchars($vote['description']); ?>
                            </p>
                            <div class="flex justify-between mt-4 items-center">
                                <button @click="selectedVote = <?php echo htmlspecialchars(json_encode($vote)); ?>; showModal = true;"
                                    class="bg-yellow-500 text-white px-4 py-2 rounded-lg hover:bg-yellow-400">
                                    Voter
                                </button>
                                <span class="text-gray-600">
                                    <?php echo htmlspecialchars($vote['votes'] ?? '0'); ?> votes
                                </span>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Modal -->
        <div x-show="showModal" x-cloak
            class="fixed inset-0 bg-gray-800 bg-opacity-50 flex justify-center items-center z-50">
            <div class="bg-white p-6 rounded-lg shadow-lg w-96" @click.away="showModal = false">
                <h3 class="text-2xl font-bold mb-4" x-text="selectedVote.name"></h3>
                <p class="text-lg text-gray-600 mb-4" x-text="selectedVote.description"></p>
                <form @submit.prevent="submitVote">
                    <input type="hidden" name="vote_id" :value="selectedVote.id">
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Options</label>
                        <template x-for="(option, index) in selectedVote.options.split(',')" :key="index">
                            <div class="mb-2">
                                <input type="radio" :id="'option-' + index" name="option_id" :value="index + 1" class="mr-2">
                                <label :for="'option-' + index" class="text-gray-700" x-text="option"></label>
                            </div>
                        </template>
                    </div>
                    <div class="mt-4">
                        <button type="submit" class="w-full bg-yellow-500 text-white py-2 rounded-lg hover:bg-yellow-400">
                            Soumettre
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Loader -->
        <div x-show="showLoader" x-cloak
            class="fixed inset-0 bg-white flex justify-center items-center z-50 transition-opacity duration-300">
            <svg class="animate-spin h-12 w-12 text-yellow-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
            </svg>
        </div>

        <!-- Thank You Message -->
        <div x-show="showThankYou" x-cloak
            class="fixed inset-0 bg-white flex flex-col justify-center items-center z-50 transition-opacity duration-300">
            <h2 class="text-3xl font-bold text-gray-800 animate-bounce">Merci pour votre vote !</h2>
        </div>
    </section>
</body>

</html>
