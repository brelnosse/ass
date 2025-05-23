<?php
    include("../controller/authController.php");
    session_start();
    setcookie('connection','',time()-100);
    updateConnectionStatus($_SESSION['user_id']);
    session_destroy();
    header('location: ../vues/auth.php');