<?php
    session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assurance</title>
    <link rel="stylesheet" href="ressources/css/common.css">
    <link rel="stylesheet" href="ressources/css/menu.css">
    <link rel="stylesheet" href="ressources/css/notif.css">
</head>
<body>
    <?php
        include('components/navbar.php');
        include('components/verticalmenu.php');
    ?>
    <div class="container">
        <h1 class="root"><button onclick="history.back()" class="btn btn-primary"><i class="fas fa-arrow-left" style="margin-right: 0"></i></button> Notifications (<?= count(getallnotif($_SESSION["user_id"])) ?>) </h1>
        <div class="notif-container">
            <?php
                foreach(getallnotif($_SESSION["user_id"]) as $notif){ 
                    ?>
                <div class="notif">
                    <div class="notif-head"><h3>Le contrat de référence <u><a href="liste_contrat.php#<?= getContractByIdn($notif['id_contrat'])['ref'] ?>"><?= getContractByIdn($notif['id_contrat'])['ref'] ?></a></u> a expiré</h3></div>
                    <p>
                        Ci-dessous les informations pour contacter le client: 
                        <ul>
                            <li>Numéro de téléphone: <b class="badge"><?=  getdemandebyrefcontrat(getContractByIdn($notif['id_contrat'])['ref'])[0]['telephone'] ?></u></b></li>
                            <li>Adresse mail: <a href="mailto:brelnosse2@gmail.com" class="badge"><?=  getdemandebyrefcontrat(getContractByIdn($notif['id_contrat'])['ref'])[0]['email'] ?></a></li>
                        </ul>
                    </p>
                    <span class="sent_date" style="font-size: 0.8em;display: flex; justify-content: flex-end">Message envoyé le :  <i style='margin-left: 5px'><?= $notif["date_env"]; ?></i></span>
                </div>
            <?php
                }
            ?>
        </div>
    </div>
    <script src="ressources/js/all.js"></script>
    <script src="ressources/js/menu.js"></script>
</body>
</html>