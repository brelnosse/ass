<?php
session_start();
include('../../Controller/registerController.php');
if(isset($_GET['id'])){
    if(decrypt($_GET['id'])){
        $_SESSION['id_connect'] = decrypt($_GET['id']);
    }else{
        header('location:user-not-found.php');
    }
}else{
    header('location:user-not-found.php');
}
// $pageName = explode('?', basename($_SERVER['REQUEST_URI']))[0];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription - Étape 1</title>
    <link rel="stylesheet" href="../../ressources/css/common.css">
    <link rel="stylesheet" href="../../ressources/css/loginEmp.css">
</head>
<body>
    <h1 class="title primary" style="margin: 15px 0px; text-align: center">Information de connexion</h1>
    
    <form id="etape1Form" method="post" action="">
        <?php 
            isLoginAndEmailExist();
        ?>
        <div class="form-group">
            <label for="login">Login <br><i style="font-weight: 100;color: red; font-size: 0.8em">(login envoy&eacute; par mail)</i></label>
            <input type="text" id="login" name="login" required>
        </div>
        
        <div class="form-group">
            <label for="email">Adresse email </label>
            <input type="email" id="email" name="email" required>
        </div>
        
        <input type="submit" name="etape1" class="btn active" id="submitBtn" value="continuer">
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
    </form>
    <script src="../../ressources/js/all.js"></script>
    <script>
        document.getElementById('etape1Form').addEventListener('submit', function() {
            document.getElementById('submitBtn').disabled = true;
            document.getElementById('loaderc').style.display = 'flex';
        });
    </script>
</body>
</html>

