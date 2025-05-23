<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Message</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="container">
        <div class="card">
            <?php if(isset($_SESSION['message'])): ?>
                <div class="alert alert-info">
                    <?php echo $_SESSION['message']; unset($_SESSION['message']); ?>
                </div>
            <?php endif; ?>
            
            <div class="links">
                <a href="../../assurance/vues/auth.php">Retour à la connexion</a>
            </div>
        </div>
    </div>
</body>
</html>