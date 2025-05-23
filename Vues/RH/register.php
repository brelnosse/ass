<?php
    session_start();
    include('../../Controller/registerController.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../../ressources/css/common.css">
    <link rel="stylesheet" href="../../ressources/css/menu.css">
    <link rel="stylesheet" href="../../ressources/css/registerRH.css">
</head>
<body>
    <?php
        include('components/navbar.php');    
    ?>
    <div class="alert">
        <p><i class="fas fa-info-circle"></i>Lorsque vous ajouterez un nouvel employ&eacute;, un code de connexion g&eacute;ner&eacute; automatiquement est envoy&eacute; par mail afin qu'il puisse se connecter. Vous pourrez voir ce code dans la page <a href="employes.php" class="btn" style="background-color: white"><i class="fas fa-users" style="margin-right: 0"></i> employ&eacute;s</a>.</p>
    </div>
    <form class="form" method="post" id="myForm" enctype="multipart/form-data">
        <h1 style="grid-column: span 2">Nouvel employ&eacute;</h1>
        <div class="inputContainer">
            <label for="nom">Nom <b style="color: red">*</b></label>
            <input type="text" name="nom" id="nom" class="input" value="<?= isset($_POST['nom']) ? htmlspecialchars($_POST['nom']) : ""?>" required>
            <?php isNomErr(); ?>
        </div>
        <div class="inputContainer">
            <label for="prenom">Pr&eacute;nom <b style="color: red">*</b></label>
            <input type="text" name="prenom" id="prenom" class="input" value="<?= isset($_POST['prenom']) ? htmlspecialchars($_POST['prenom']) : ""?>" required>
            <?php isPrenomErr(); ?>
        </div>
        <div class="inputContainer">
            <label for="mail">Adresse mail <b style="color: red">*</b></label>
            <input type="email" name="email" id="email" class="input" value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ""?>" required>
            <?php isEmailErr(); ?>
        </div>
        <div class="inputContainer">
            <label for="phone">Num&eacute;ro de t&eacute;l&eacute;phone <b style="color: red">*</b></label>
            <input type="number" name="phone" id="phone" class="input" value="<?= isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : ""?>" required>
            <?php isPhoneErr(); ?>

        </div>
        <div class="inputContainer group">
            <label for="poste">Poste de l'employ&eacute; <b style="color: red">*</b></label>
            <select name="poste" id="poste" required>
                <option value="">S&eacute;lectionner un poste</option>
                <option value="agts" <?= isset($_POST['poste']) && $_POST['poste'] == 'agts'  ? "selected" : ""?>>AGTS - Agent assurance site</option>
                <option value="rasd" <?= isset($_POST['poste']) && $_POST['poste'] == 'rasd'  ? "selected" : ""?>>RASD - Responsable assurance direction</option>
            </select>
            <?php isPosteErr(); ?>
        </div>
        <div class="inputContainer group">
            <label for="birthdate">Date de naissance <b style="color: red">*</b></label>
            <input type="date" name="bdate" id="bdate" class="input" value="<?= isset($_POST['bdate']) ? htmlspecialchars($_POST['bdate']) : ""?>" required>
            <?php isBdateErr(); ?>
        </div>
        <div class="inputContainer group">
            <label for="photo">Photo d'identification <i>(JPEG, JPG, PNG)</i><b style="color: red">*</b></label>
            <input type="file" name="photo" id="photo" class="input" required>
            <?php isPhotoErr(); ?>
        </div>
        <div>
        <?php isAuthErr(); ?>
        </div>
        <input type="submit" class="submitbtn" id="submitbtn" name="send" value="Ajouter l'employ&eacute;">
    </form>
    <div class="loaderc" id="loaderc">
        <div class="loadercontainer">
        <div class="cercle"></div>
        <div class="enfant">
            <div class="tete">
            <div class="oeil-gauche"></div>
            <div class="oeil-droit"></div>
            <div class="sourire"></div>
            </div>
            <div class="corps"></div>
            <div class="bras-gauche"></div>
            <div class="bras-droit"></div>
            <div class="jambe-gauche"></div>
            <div class="jambe-droite"></div>
        </div>
        </div> 
    </div>  
    <script src="../../ressources/js/all.js"></script>
    <script>
        document.getElementById('myForm').addEventListener('submit', function() {
            document.getElementById('loaderc').style.display = 'flex';
            document.getElementById('submitbtn').disabled = true;
        });
    </script>
</body>
</html>