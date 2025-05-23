<?php
// include_once '../../Models/register.php';
include_once '../../Controller/registerController.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
function sendResetLink($email) {

    require "../../vendor/autoload.php";
    // Vérifier si l'email existe
    $user = getUserByEmail($email);
    
    if (!$user) {
        $_SESSION['message'] = "Si cette adresse existe dans notre système, un email de réinitialisation a été envoyé.";
        include 'message.php';
        return;
    }
    
    // Créer un token de réinitialisation
    $token = createPasswordResetToken($email);
    
    // Construire le lien de réinitialisation
    $resetLink = 'http://' . $_SERVER['HTTP_HOST'] . '/ass/Vues/employe/p_index.php?action=newPasswordForm&token=' . $token . '&email=' . urlencode($email);
    try {
        //Créer une nouvelle instance
        $mail = new PHPMailer(true);

        //Configuration du serveur
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';         // Serveur SMTP Gmail
        $mail->SMTPAuth   = true;
        $mail->Username   = 'brelnosse2@gmail.com';        // Votre adresse Gmail
        $mail->Password   = 'uomo yvbi igkh umte'; // Mot de passe d'application
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Enable TLS encryption
        $mail->Port       = 587;                      // Port Gmail TLS

        //Configuration de l'expéditeur et du destinataire
        $mail->setFrom('brelnosse2@gmail.com', 'ne.pasrepondre');
        $mail->addAddress($email); // Adresse du destinataire

        //Configuration du contenu
        $mail->isHTML(true);
        $mail->Subject = "Reinitialisation de mot de passe";
        $message = "Bonjour,\n\nVous avez demandé la réinitialisation de votre mot de passe. Veuillez cliquer sur le lien suivant pour définir un nouveau mot de passe :\n\n";
        $message .= $resetLink . "\n\n";
        $message .= "Ce lien expirera dans 1 heure.\n\n";
        $message .= "Si vous n'avez pas demandé cette réinitialisation, veuillez ignorer cet email.";
        
        $mail->Body = $message;
        $mail->CharSet = 'UTF-8';

        //Gestion des erreurs SMTP en production
        $mail->SMTPDebug = 0;  // 0 = pas de debug, 2 = debug complet
        
        //Ajout d'un timeout
        $mail->Timeout = 30;

        //Envoi de l'email
        $mail->send();
        $_SESSION['email_ok'] = 1;
        $_SESSION['message'] = "Si cette adresse existe dans notre système, un email de réinitialisation a été envoyé.";
        include 'message.php';
    } catch (Exception $e) {
        //Log l'erreur dans un fichier
        error_log("Erreur d'envoi d'email: " . $mail->ErrorInfo);
        $_SESSION['message'] = "Une erreur est survenue lors de l'envoi de l'email. Veuillez réessayer.";
        include 'message.php';
    }    
}

function updatePassword($token, $email, $password, $confirm_password) {
    // Vérifier que le token est valide
    if (!validateResetToken($token, $email)) {
        $_SESSION['message'] = "Ce lien n'est plus valide ou a expiré.";
        include 'message.php';
        return;
    }
    
    // Vérifier que les mots de passe correspondent
    if ($password !== $confirm_password) {
        $_SESSION['error'] = "Les mots de passe ne correspondent pas.";
        include 'newPasswordForm.php';
        return;
    }
    
    // Vérifier la complexité du mot de passe
    if (strlen($password) < 8) {
        $_SESSION['error'] = "Le mot de passe doit contenir au moins 8 caractères.";
        include 'newPasswordForm.php';
        return;
    }
    
    // Mettre à jour le mot de passe
    if (saveNewPassword($email, $password)) {
        $_SESSION['message'] = "Votre mot de passe a été réinitialisé avec succès. Vous pouvez maintenant vous connecter avec votre nouveau mot de passe.";
        header("Location: ../../assurance/vues/auth.php");
        exit;
    } else {
        $_SESSION['message'] = "Une erreur est survenue. Veuillez réessayer.";
        include 'message.php';
    }
}
