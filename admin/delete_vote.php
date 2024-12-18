<?php
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Connexion à la base de données
    $pdo = new PDO('mysql:host=localhost;dbname=ton_database', 'username', 'password');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Supprimer le vote
    $stmt = $pdo->prepare("DELETE FROM votes WHERE id = ?");
    $stmt->execute([$id]);

    // Rediriger après suppression
    header("Location: admin_page.php");
    exit;
}
?>
