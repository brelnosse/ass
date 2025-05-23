<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réinitialisation de mot de passe</title>
    <link rel="stylesheet" href="../../ressources/css/common.css">

    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="container">
        <div class="card">
            <h1>Réinitialisation de mot de passe</h1>
            <p>Entrez votre adresse email pour recevoir un lien de réinitialisation.</p>
            
            <form action="p_index.php?action=sendResetLink" method="post" id="myForm">
                <div class="form-group">
                    <label for="email">Adresse email</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <button type="submit" id="submitbtn" class="btn">Envoyer le lien de réinitialisation</button>
                </div>
            </form>
            <div class="links">
                <a href="../../assurance/vues/auth.php">Retour à la connexion</a>
            </div>
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