<?php
    include("../controller/authController.php");
    if(!isset($_SESSION['user_id'])){
        header('location: auth.php?err=2');
    }
    include('../controller/func.php');
    addnotif($_SESSION['user_id']);
    if(basename($_SERVER['REQUEST_URI']) == 'notif.php'){
        updatenotif($_SESSION['user_id']);
    }
    setcookie("connection", "connected", time()+5*60);
    //updateConnectionStatus($_SESSION['user_id']);
    if(isset($_GET['showModal'])){ 
        if($_GET['showModal'] == 'true'){
        ?>
        <div class="modalContainer">
            <div class="modalfen">
                <p>&Egrave;tes-vous sur de vouloir vous déconnectez ?</p>
                <div class="commandButton">
                    <a href="../models/logout.php" class="btn red">Deconnexion</a>
                    <a href="?showModal=false" class="link-btn">Annuler</a>
                </div>
            </div>
        </div>
        <?php
        }
    }
?>
<nav class="horizontal-navbar">
    <span class="toggle-verticalMenu"><i class="fas fa-bars"></i></span>
    <div class="horizontal-navbar-items">
    <?php
    if (isset($_SESSION['user_poste']) && $_SESSION['user_poste'] == 'rasd') { ?>
        <a href="notif.php" class="btn btn-primary" style="position:relative;padding: 0.75rem 1.5rem;font-size: 1.2em"><i class="fas fa-bell" style='margin: 0'></i> <?= getunreadednotif($_SESSION['user_id']) ? "<span style='display: flex;height:10px; width: 10px;background-color: red;border-radius: 50%; position: absolute; top: 5px; left: calc(40% - 10px)'></span>" : ""?></a>
    <?php

        // if(){
        //     echo "no";
        // }else{
        //     echo "rien";
        // }
    }

    ?>
        <select name="" id="">
            <option value=""><?= getuserbyid($_SESSION['user_id'])['prenom'].' '.getuserbyid($_SESSION['user_id'])['nom'] ?></option>
        </select>
        <a href="?showModal=true" class="btn red"><i class="fas fa-sign-out-alt"></i></a>
    </div>
</nav>