<?php
session_start();
require('Config.php');

if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

$username = $_SESSION['username'];
$productId = isset($_GET['product_id']) ? $_GET['product_id'] : null;
$query = "SELECT * FROM products WHERE id = '$productId'";
$result = mysqli_query($conn, $query);
$product = mysqli_fetch_assoc($result);

// Chemin de l'image de profil
$imagePath = !empty($user['image']) ? $user['image'] : 'default-profile.png';
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Choisir un Vote</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.1.2/dist/tailwind.min.css">
</head>

<body class="bg-gray-100">

    <div class="flex justify-center items-center h-screen">
        <div class="bg-white shadow-lg p-8 rounded-lg w-2/3">
            <h2 class="text-2xl font-bold text-center mb-6">Choisissez un Vote</h2>
            <form action="processVote.php" method="POST">
                <div class="mb-4">
                    <h3 class="text-xl font-bold"><?php echo $product['name']; ?></h3>
                    <p class="text-gray-600 mt-2"><?php echo $product['description']; ?></p>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700">Choisissez votre option:</label>
                    <select name="vote_option" class="mt-2 p-2 w-full border rounded-md">
                        <option value="Option 1">Option 1</option>
                        <option value="Option 2">Option 2</option>
                        <option value="Option 3">Option 3</option>
                    </select>
                </div>
                <button type="submit" class="w-full bg-blue-500 text-white py-2 rounded-lg hover:bg-blue-400">Voter</button>
            </form>
        </div>
    </div>

</body>

</html>
