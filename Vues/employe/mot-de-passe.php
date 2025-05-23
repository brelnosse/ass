<?php
    session_start();
    include('../../Controller/registerController.php');
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mot de passe</title>
    <link rel="stylesheet" href="../../ressources/css/common.css">
    <link rel="stylesheet" href="../../ressources/css/loginEmp.css">
</head>
<body>
    <h2 class="title primary" style="margin: 15px 0px">Definition du mot de passe</h2>
    
    <?php if (isset($error)): ?>
        <div class="error"><?php echo $error; ?></div>
    <?php endif; ?>
    
    <form id="etape1Form" method="post">
    <?php completeRegister(); ?>
        <div class="form-group">
            <label for="password">Mot de passe :</label>
            <input type="password" id="password" name="password" required>
        </div>
        
        <div class="form-group">
            <label for="confirm_password">Confirmez le mot de passe :</label>
            <input type="password" id="confirm_password" name="confirm_password" required>
        </div>
        
        <input type="submit" name="etape2" class="btn active" value="Terminer l'inscription">       
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
    <script>
        document.getElementById('etape1Form').addEventListener('submit', function() {
            document.getElementById('submitBtn').disabled = true;
            document.getElementById('loaderc').style.display = 'flex';
        });
    </script>
</body>
</html>
