<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="ressources/css/common.css">
    <title>RH panel</title>
</head>
<body>
    <?php
        include('Vues/RH/components/navbar.php');
        switch($_SERVER['REQUEST_URI']){
            case '/':
                header('location: Vues/RH/home.php');
            break;
        }
    ?>
    <script src="ressources/js/all.js"></script>
</body>
</html>