<?php
    use src\classes\user;
// auteur: mo

// CSRF-bescherming
session_start();
if (empty($_SESSION['token'])) {
    $_SESSION['token'] = bin2hex(random_bytes(32));
}
$token = $_SESSION['token'];
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Vernieuwde Registratie</title>
    <!-- Aangepaste Stijlen -->
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h3>Vernieuwde PHP - PDO Registratie</h3>
    <hr/>

    <form id="registrationForm" action="" method="POST">    
        <h4>Word lid van onze community...</h4>
        <hr>
        
        <!-- CSRF-token -->
        <input type="hidden" name="token" value="<?php echo $token; ?>">

        <label>Naam</label>
        <input type="text" name="naam" required />
        <br>
        <label>Email</label>
        <input type="email" name="email" required />
        <br>
        <label>Gebruikersnaam</label>
        <input type="text" name="gebruikersnaam" required />
        <br>
        <label>Wachtwoord</label>
        <input type="password" name="wachtwoord" required />
        <br>
        <button type="submit" name="register-btn">Registreren</button>
        <br>
        <a href="login_form.php">Al lid? Log in hier</a>
    </form>

    <!-- AJAX-script voor formulierindiening en validatie -->
    <script src="script.js"></script>
        
</body>
</html>

<?php

if(isset($_POST['register-btn'])){
    require_once('user.php');
    require_once('db_connection.php'); // Veronderstel dat je hier de databaseverbinding hebt gemaakt en de naam van het bestand db_connection.php is.

    // CSRF-tokenvalidatie
    if (!isset($_POST['token']) || ($_POST['token'] !== $_SESSION['token'])) {
        echo "<script>alert('CSRF-token komt niet overeen');</script>";
        exit;
    }

    $naam = trim($_POST['naam']);
    $email = trim($_POST['email']);
    $gebruikersnaam = trim($_POST['gebruikersnaam']);
    $wachtwoord = $_POST['wachtwoord'];

    // Maak een nieuwe gebruiker aan met de databaseverbinding
    $user = new User($db); // Zorg ervoor dat $db de juiste databaseverbinding is

    // Voer verdere validaties en verwerkingen uit...
}

?>
