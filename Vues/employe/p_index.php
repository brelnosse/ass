<?php
session_start();
require_once '../../Models/db.php';
// require_once '../../Models/register.php';
require_once '../../Controller/passwordController.php';
$action = isset($_GET['action']) ? $_GET['action'] : 'resetForm';

switch ($action) {
    case 'resetForm':
        include 'resetPasswordForm.php';
        break;
    case 'sendResetLink':
        $email = isset($_POST['email']) ? trim($_POST['email']) : '';
        sendResetLink($email);
        break;
    case 'newPasswordForm':
        $token = isset($_GET['token']) ? $_GET['token'] : '';
        $email = isset($_GET['email']) ? $_GET['email'] : '';
        if (validateResetToken($token, $email)) {
            include 'newPasswordForm.php';
        } else {
            $_SESSION['message'] = "Ce lien n'est plus valide ou a expiré.";
            include 'message.php';
        }
        break;
    case 'updatePassword':
        $token = isset($_POST['token']) ? $_POST['token'] : '';
        $email = isset($_POST['email']) ? $_POST['email'] : '';
        $password = isset($_POST['password']) ? $_POST['password'] : '';
        $confirm_password = isset($_POST['confirm_password']) ? $_POST['confirm_password'] : '';
        
        updatePassword($token, $email, $password, $confirm_password);
        break;
    default:
        include 'resetPasswordForm.php';
        break;
}