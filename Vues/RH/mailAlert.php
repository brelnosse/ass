<?php
    session_start();
    include('../../Controller/registerController.php');
    if(!isset($_SESSION['email_ok']) || $_SESSION['email_ok'] == 0){
        header("location:register.php");
    }
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        unset($_SESSION['email_ok']);
        header('location: employes.php');
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../ressources/css/common.css">
    <link rel="stylesheet" href="../../ressources/css/mailAlert.css">
    <title>Document</title>
</head>
<body>
    <div class="container" method="post">
        <h1 class="title primary">Employ&eacute; ajout&eacute;</h1>
        <?php
            if(!isset($_SESSION['mail_no_exist']) || $_SESSION['mail_no_exist'] == 0){ ?>
                <p class="text">Une email a &eacute;t&eacute; envoyer &agrave; l'employ&eacute; a fin qu'il puisse definir son mot de passe de connexion.</p>
        <?php
            }else{ ?>
                <p class="text danger bold">Nous n'avons pas pu envoyer l'email contenant son login &agrave; cet nouvel employ&eacute;.</p>
        <?php
            }
        ?>
        <!-- <sub class="text danger small">Vous avez la possibilit&eacute; de ré-envoyer l'email en cas de pertubations r&eacute;seaux.</sub> -->
    
        <div class="btn-container">
            <button class="btn" onclick="history.back()"><i class="fas fa-angle-left"></i>Page pr&eacute;cedente</button>
            <a href="../employe/view_emp.php?id=<?= $_GET['id'] ?>" class="btn active" name="resendmail" id="mail"><i class="fas fa-user"></i>Voir l'employé</a>
        </div>
        </div>
    <script src="../../ressources/js/all.js"></script>
</body>
</html>