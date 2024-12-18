<?php
session_start();
require('Config.php');

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

// Récupérer l'utilisateur connecté
$username = $_SESSION['username'];
$userQuery = "SELECT id FROM users WHERE username = '$username'";
$userResult = mysqli_query($conn, $userQuery);
$user = mysqli_fetch_assoc($userResult);

if (!$user) {
    $_SESSION['error_message'] = "Utilisateur non trouvé.";
    header('Location: index.php');
    exit();
}

$userId = $user['id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['vote_id'], $_POST['option_id'])) {
    $voteId = intval($_POST['vote_id']); // ID du vote
    $optionId = intval($_POST['option_id']); // Option choisie par l'utilisateur

    // Vérifier si l'utilisateur a déjà voté pour ce vote
    $checkQuery = "SELECT * FROM user_votes WHERE user_id = $userId AND vote_id = $voteId";
    $checkResult = mysqli_query($conn, $checkQuery);

    if (mysqli_num_rows($checkResult) > 0) {
        $_SESSION['error_message'] = "Vous avez déjà voté pour cet élément.";
        header('Location: index.php');
        exit();
    }

    // Enregistrer le vote
    $insertQuery = "INSERT INTO user_votes (user_id, vote_id, option_id) VALUES ($userId, $voteId, $optionId)";
    $insertResult = mysqli_query($conn, $insertQuery);

    if ($insertResult) {
        $_SESSION['success_message'] = "Merci pour votre vote !";
        header('Location: index.php');
        exit();
    } else {
        $_SESSION['error_message'] = "Une erreur est survenue lors de l'enregistrement de votre vote.";
        header('Location: index.php');
        exit();
    }
}

?>
