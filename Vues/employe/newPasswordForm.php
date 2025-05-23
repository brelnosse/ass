<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nouveau mot de passe</title>
    <link rel="stylesheet" href="../../ressources/css/common.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="container">
        <div class="card">
            <h1>Définir un nouveau mot de passe</h1>
            
            <?php if(isset($_SESSION['error'])): ?>
                <div class="alert alert-danger">
                    <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                </div>
            <?php endif; ?>
            
            <form action="p_index.php?action=updatePassword" method="post" id="myForm">
                <input type="hidden" name="token" value="<?php echo htmlspecialchars($_GET['token']); ?>">
                <input type="hidden" name="email" value="<?php echo htmlspecialchars($_GET['email']); ?>">
                
                <div class="form-group">
                    <label for="password">Nouveau mot de passe</label>
                    <input type="password" id="password" name="password" required>
                    <small>Le mot de passe doit contenir au moins 8 caractères.</small>
                </div>
                
                <div class="form-group">
                    <label for="confirm_password">Confirmer le mot de passe</label>
                    <input type="password" id="confirm_password" name="confirm_password" required>
                </div>
                
                <div class="form-group">
                    <button type="submit" id="submitbtn" class="btn">Modifier le mot de passe</button>
                </div>
            </form>
        </div>
    </div>
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
    <script>
            document.getElementById('myForm').addEventListener('submit', function() {
            document.getElementById('loaderc').style.display = 'flex';
            document.getElementById('submitbtn').disabled = true;
            
        });
    </script>
</body>
</html>