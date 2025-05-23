<?php
    include('../../Models/sessionremove.php');
    include('../controller/authController.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <link rel="stylesheet" href="../../ressources/css/common.css">
    <link rel="stylesheet" href="./ressources/css/auth.css">
</head>
<body>
    <div class="container">
        <h1 class="title">Gestion des assurances</h1>
        <form action="" method="post" class="form">
            <?php isLoginError(); ?>
            <div class="inputContainer">
                <label for="nom">Login</label>
                <input type="text" id="username" class="input" placeholder="Login" name="username" style="width: 400px" required>
            </div>
            <div class="inputContainer">
                <label for="nom">Mot de passe</label>
                <input type="password" id="mdp" class="input" placeholder="Mot de passe" name="mdp" style="width: 400px" required>
            </div>
            <input type="submit" value="Se connecter" class="btn active">
            <a href="../../Vues/employe/p_index.php" class="link">- Mot de passe oublier ?</a>
        </form>
    </div>
    <script src="./ressources/js/all.js"></script>
</body>
</html>