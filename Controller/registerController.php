<?php
    include('../../Models/register.php');
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;
    
    require "../../vendor/autoload.php";

    if($_SERVER['REQUEST_METHOD'] == "POST"){
        $_SESSION['connection_ok'] = 0;
        $_SESSION['email_ok'] = 0;
        $_SESSION['nom_err'] = 0;
        $_SESSION['prenom_err'] = 0;
        $_SESSION['email_err'] = 0;
        $_SESSION['phone_err'] = 0;
        $_SESSION['bdate_err'] = 0;
        $_SESSION['poste_err'] = 0;
        $_SESSION['photo_err'] = 0;
        $_SESSION['email_ok_u'] = 0;
        $_SESSION['nom_err_u'] = 0;
        $_SESSION['prenom_err_u'] = 0;
        $_SESSION['email_err_u'] = 0;
        $_SESSION['phone_err_u'] = 0;
        $_SESSION['bdate_err_u'] = 0;
        $_SESSION['poste_err_u'] = 0;
        $_SESSION['photo_err_u'] = 0;
        $_SESSION['is_u_ok'] = 0;
        if(isset($_POST['resendmail'])){
            if(isset($_GET['id'])){
                $mail = getemployebyid($_GET['id'])[0]['email'];
                $prenom = getemployebyid($_GET['id'])[0]['prenom'];
                $code = getemployebyid($_GET['id'])[0]['auth_key'];

                if(sendEmail($mail, "Finalisation de la creation de compte" , $prenom, $code)['success']){
                    updateEmpMailstatus($mail);
                    addinjournal($_GET['id'], "Tentative d'envoie de code par email reussi !!!");
                    $_SESSION['mail_no_exist'] = 0;
                    $_SESSION['connection_ok'] = 0;
                    header('location: ../RH/mailAlert.php?id='.$_GET['id']);
                }else{
                    $_SESSION['email_ok'] = 1;
                    $_SESSION['mail_no_exist'] = 1;
                    addinjournal($_GET['id'], "Tentative d'envoie de code par email échouer !");
                    header('location: ../RH/mailAlert.php?id='.$_GET['id']);
                }
            }
        }
        if(isset($_POST['save-updates'])){
            if(isset($_GET['id'], $_POST['nom'],$_POST['prenom'],$_POST['email'],$_POST['phone'],$_POST['poste'],$_POST['bdate'], $_POST['pp'])){
                $nom = htmlspecialchars($_POST['nom']);
                $prenom = htmlspecialchars($_POST['prenom']);
                $mail = htmlspecialchars($_POST['email']);
                $phone = htmlspecialchars($_POST['phone']);
                $poste = htmlspecialchars($_POST['poste']);
                $bdate = htmlspecialchars($_POST['bdate']);
                $curDate = new DateTime(date('Y-m-d'));
                $nbdate = new DateTime($bdate);
                $photo = null;
                $isPhoto = false;

                if(isset($_FILES['photo']['name']) AND $_FILES['photo']['error'] == 0){
                    $filename = $_FILES['photo']['name'];
                    $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
                    if(in_array($extension, ['jpg','jpeg','png'])){
                        if($_FILES['photo']['size'] <= 500*1024){
                            if(move_uploaded_file($_FILES['photo']['tmp_name'], "../../ressources/upload/".time()."".$filename)){
                                $photo = "ressources/upload/".time()."".$filename;
                                $isPhoto = true;
                            }
                        }else{
                            $isPhoto = false;
                        }
                    }else{
                        $isPhoto = false;
                    }
                }
                if(empty($photo)){
                    $photo = htmlspecialchars($_POST['pp']);
                    $isPhoto = true;
                }
                $isVerified = [
                    "isNom" => !empty($nom) && strlen($nom) >= 3,
                    "isPrenom" => !empty($prenom) && strlen($prenom) >= 3,
                    "isEmail" => filter_var($mail, FILTER_VALIDATE_EMAIL),
                    "isPhone" => !empty($phone) && (strlen($phone) == 9) && ($phone[0] == 6),
                    "isPoste" => !empty($poste),
                    "isBdate" => ($curDate > $nbdate) && date_diff($curDate, $nbdate)->y >= 18,
                    "isPhoto" => $isPhoto
                ];
                if(!in_array(false, $isVerified)){
                    if(updateName(htmlspecialchars($_GET['id']), $nom) AND updatePrenom(htmlspecialchars($_GET['id']), $prenom) AND updateEmail(htmlspecialchars($_GET['id']), $mail) AND updatePoste(htmlspecialchars($_GET['id']), $poste) AND updatePhoto(htmlspecialchars($_GET['id']), $photo) AND updatePhone(htmlspecialchars($_GET['id']), $phone) AND updateBdate(htmlspecialchars($_GET['id']), $bdate)){
                        header('refresh: 0');
                    }
                }else{
                    if($isVerified['isNom'] == false){
                        $_SESSION['nom_err_u'] = 1;
                    }
                    if($isVerified['isPrenom'] == false){
                        $_SESSION['prenom_err_u'] = 1;
                    }
                    if($isVerified['isEmail'] == false){
                        $_SESSION['email_err_u'] = 1;
                    }
                    if($isVerified['isPhone'] == false){
                        $_SESSION['phone_err_u'] = 1;
                    }
                    if($isVerified['isPoste'] == false){
                        $_SESSION['poste_err_u'] = 1;
                    }
                    if($isVerified['isBdate'] == false){
                        $_SESSION['bdate_err_u'] = 1;
                    }
                    if($isVerified['isPhoto'] == false){
                        $_SESSION['photo_err_u'] = 1;
                    }
                }
            }
        }

        if(isset($_POST['desactivate_account'])){
            if(isset($_GET['id'])){
                updateaccountstate($_GET['id'], "unactive");
                header('refresh: 0.3');
            }
        }
        if(isset($_POST['activate_account'])){
            if(isset($_GET['id'])){
                updateaccountstate($_GET['id'], "active");
                header('refresh: 0.3');
            }            
        }
        if(isset($_POST['nom'],$_POST['prenom'],$_POST['email'],$_POST['phone'],$_POST['poste'],$_POST['bdate'])){
            include('../../Models/db.php');
            $nom = htmlspecialchars($_POST['nom']);
            $prenom = htmlspecialchars($_POST['prenom']);
            $mail = htmlspecialchars($_POST['email']);
            $phone = htmlspecialchars($_POST['phone']);
            $poste = htmlspecialchars($_POST['poste']);
            $bdate = htmlspecialchars($_POST['bdate']);
            $curDate = new DateTime(date('Y-m-d'));
            $nbdate = new DateTime($bdate);
            $code = auth_key($bdd);
            $photo;
            $isPhoto = false;
            if(isset($_FILES['photo']['name']) && $_FILES['photo']['error'] == 0){
                $filename = $_FILES['photo']['name'];
                $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
                if(in_array($extension, ['jpg','jpeg','png'])){
                    if($_FILES['photo']['size'] <= 500*1024){
                        if(move_uploaded_file($_FILES['photo']['tmp_name'], "../../ressources/upload/".time()."".$filename)){
                            $photo = "ressources/upload/".time()."".$filename;
                            $isPhoto = true;
                        }
                    }else{
                        $isPhoto = false;
                    }
                }else{
                    $isPhoto = false;
                }
            }
            $isVerified = [
                "isNom" => !empty($nom) && strlen($nom) >= 3,
                "isPrenom" => !empty($prenom) && strlen($prenom) >= 3,
                "isEmail" => filter_var($mail, FILTER_VALIDATE_EMAIL),
                "isPhone" => !empty($phone) && (strlen($phone) == 9) && ($phone[0] == 6),
                "isPoste" => !empty($poste),
                "isBdate" => ($curDate > $nbdate) && date_diff($curDate, $nbdate)->y >= 18,
                "isPhoto" => $isPhoto
            ];

            if(!in_array(false, $isVerified)){
                if(addEmploye($nom, $prenom, $mail, $phone, $poste, $bdate, $photo, $code)){
                    if(sendEmail($mail, "Finalisation de la creation de compte" , $prenom, $code)['success']){
                        updateEmpMailstatus($mail);
                        addinjournal(getemployebyemail($mail)[0]['id'], "Tentative d'envoie de code par email reussi !!!");
                        $_SESSION['mail_no_exist'] = 0;
                        $_SESSION['emp_email'] = $mail;
                        $_SESSION['emp_login'] = $code;
                        $_SESSION['connection_ok'] = 0;
                        header('location: mailAlert.php?id='.getemployebyemail($mail)[0]['id']);
                    }else{
                        $_SESSION['email_ok'] = 1;
                        $_SESSION['mail_no_exist'] = 1;
                        addinjournal(getemployebyemail($mail)[0]['id'], "Tentative d'envoie de code par échouer !");
                        header('location: mailAlert.php?id='.getemployebyemail($mail)[0]['id']);
                    }
                }else{
                    $_SESSION['connection_ok'] = 1;
                }
            }else{
                if($isVerified['isNom'] == false){
                    $_SESSION['nom_err'] = 1;
                }
                if($isVerified['isPrenom'] == false){
                    $_SESSION['prenom_err'] = 1;
                }
                if($isVerified['isEmail'] == false){
                    $_SESSION['email_err'] = 1;
                }
                if($isVerified['isPhone'] == false){
                    $_SESSION['phone_err'] = 1;
                }
                if($isVerified['isPoste'] == false){
                    $_SESSION['poste_err'] = 1;
                }
                if($isVerified['isBdate'] == false){
                    $_SESSION['bdate_err'] = 1;
                }
                if($isVerified['isPhoto'] == false){
                    $_SESSION['photo_err'] = 1;
                }
            }
        }
    }
    function isNomErr(){
        if(isset($_SESSION['nom_err']) && $_SESSION['nom_err'] == 1){
            echo "<p style='color: red'>Le nom doit contenir entre 03 et 20 caracteres.</p>";   
        }
    }
    function isPrenomErr(){
        if(isset($_SESSION['prenom_err']) && $_SESSION['prenom_err'] == 1){
            echo "<p style='color: red'>Le nom doit contenir entre 03 et 20  caracteres.</p>";   
        }
    }
    function isEmailErr(){
        if(isset($_SESSION['email_err']) && $_SESSION['email_err'] == 1){
            echo "<p style='color: red'>L'email est incorrect</p>";   
        }
    }
    function isPhoneErr(){
        if(isset($_SESSION['phone_err']) && $_SESSION['phone_err'] == 1){
            echo "<p style='color: red'>Le numero doit commence par 6 et contenir exactement 9 chiffres.</p>";   
        }
    }
    function isPosteErr(){
        if(isset($_SESSION['poste_err']) && $_SESSION['poste_err'] == 1){
            echo "<p style='color: red'>Le poste doit etre renseigner.</p>";   
        }
    }
    function isBdateErr(){
        if(isset($_SESSION['bdate_err']) && $_SESSION['bdate_err'] == 1){
            echo "<p style='color: red'>Cet employe n'est pas en age de travailler (18 ans minimum)</p>";   
        }
    }
    function isPhotoErr(){
        if(isset($_SESSION['photo_err']) && $_SESSION['photo_err'] == 1){
            echo "<p style='color: red'>Choisissez une image .jgp, .jpeg ou .png de taille inferieure a 500ko</p>";   
        }
    }
    function isAuthErr(){
        if(isset($_SESSION['connection_ok']) && $_SESSION['connection_ok'] == 1){
            echo "<div class='alert danger' style='margin-top: 20px'><p style='color: red'><i class='fas fa-exclamation-triangle'></i>Il se pourrait qu'un employer avec la meme adresse email existe deja.</p></div>";   
        }
    }

    function isNomErrU(){
        if(isset($_SESSION['nom_err_u']) && $_SESSION['nom_err_u'] == 1){
            echo "<p style='color: red'>Le nom doit contenir entre 03 et 20 caracteres.</p>";   
        }
    }
    function isPrenomErrU(){
        if(isset($_SESSION['prenom_err_u']) && $_SESSION['prenom_err_u'] == 1){
            echo "<p style='color: red'>Le nom doit contenir entre 03 et 20  caracteres.</p>";   
        }
    }
    function isEmailErrU(){
        if(isset($_SESSION['email_err_u']) && $_SESSION['email_err_u'] == 1){
            echo "<p style='color: red'>L'email est incorrect</p>";   
        }
    }
    function isPhoneErrU(){
        if(isset($_SESSION['phone_err_u']) && $_SESSION['phone_err_u'] == 1){
            echo "<p style='color: red'>Le numero doit commence par 6 et contenir exactement 9 chiffres.</p>";   
        }
    }
    function isPosteErrU(){
        if(isset($_SESSION['poste_err_u']) && $_SESSION['poste_err_u'] == 1){
            echo "<p style='color: red'>Le poste doit etre renseigner.</p>";   
        }
    }
    function isBdateErrU(){
        if(isset($_SESSION['bdate_err_u']) && $_SESSION['bdate_err_u'] == 1){
            echo "<p style='color: red'>Cet employe n'est pas en age de travailler (18 ans minimum)</p>";   
        }
    }
    function isPhotoErrU(){
        if(isset($_SESSION['photo_err_u']) && $_SESSION['photo_err_u'] == 1){
            echo "<p style='color: red'>Choisissez une image .jgp, .jpeg ou .png de taille inferieure a 500ko</p>";   
        }
    }
    function isUpdated(){
        if(isset($_SESSION['is_u_ok']) && $_SESSION['is_u_ok'] == 1){
            echo "<div class='alert success'>Modification reussi !!</div>";   
        }       
    }
    function auth_key($bdd){
        $arr1 = preg_match_all('/[a-zA-Z]/','abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', $matches);
        $letters = $matches[0];
        $arr2 = preg_match_all('/\d/','0123456789', $matches);
        $numbers = $matches[0];
        $fulltab = array_merge($letters, $numbers);
        $code = "";
        for($i = 0; $i < 6; $i++){
            $code .= $fulltab[rand(0, count($fulltab)-1)];
        }

        if(checkIfCodeExist($code)){
            return $code;
        }else{
            auth_key();
        }
    }
    //Fonction d'envoi d'email
    function sendEmail($to, $subject, $prenom, $login) {
        if(getemailstatus($login)){
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
                $mail->addAddress($to);

                //Configuration du contenu
                $mail->isHTML(true);
                $mail->Subject = $subject;
                $mail->Body    = '
                    <!DOCTYPE html>
                    <html lang="en">
                    <head>
                        <meta name="viewport" content="width=device-width, initial-scale=1.0">
                        <title>Infos</title>
                    </head>
                    <body>
                        <p>Bonjour/bonsoir Mr. '.$prenom.',</p>
                        <p>Pour finalisez votre inscription, vous allez devoir definir un mot de passe (lien vers la page de definition du mot de passe <a href="http://localhost/ass/Vues/employe/index.php?id='.sha1($login).'">Definition du mot de passe</a>)</p>
                        <p>Votre login est: <b>'.$login.'</b></p>
                        <p style="color: red;font-weight: bold">NB: Ne partager votre login a personne.</p>
                    </body>
                    </html>
                ';
                $mail->CharSet = 'UTF-8';

                //Gestion des erreurs SMTP en production
                $mail->SMTPDebug = 0;  // 0 = pas de debug, 2 = debug complet
                
                //Ajout d'un timeout
                $mail->Timeout = 30;

                //Envoi de l'email
                $mail->send();
                $_SESSION['email_ok'] = 1;
                return ['success' => true, 'message' => "L'email a été envoyé avec succès"];
            } catch (Exception $e) {
                //Log l'erreur dans un fichier
                error_log("Erreur d'envoi d'email: " . $mail->ErrorInfo);
                return ['success' => false, 'message' => "L'email n'a pas pu être envoyé. Erreur: " . $mail->ErrorInfo];
            }
        }
    }
    
    function isLoginAndEmailExist(){
        if (basename($_SERVER['PHP_SELF']) == "index.php") {
            // Si le formulaire a été soumis
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                // Récupérer et stocker les données en session              
                if(checkIfEmployeExist(trim($_POST['email']), trim($_POST['login']))){
                    $_SESSION['login'] = trim($_POST['login']);
                    $_SESSION['email'] = trim($_POST['email']);  
                    header("Location: mot-de-passe.php");
                }else{
                    echo "<div class='alert danger' style='margin: 10px 0px '><p style='color: red'><i class='fas fa-exclamation-triangle'></i> Login ou mot de passe incorrect.</p></div>";
                }
            }
        }
    }
    function completeRegister(){
        if (basename($_SERVER['PHP_SELF']) == "mot-de-passe.php") {
            // Vérifier si les données de l'étape 1 sont présentes
            if (!isset($_SESSION['login']) || !isset($_SESSION['email'])) {
                // Rediriger vers la première étape si les données manquent
                header("Location: index.php");
                exit();
            }
            
            // Si le formulaire a été soumis
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                // Vérifier que les mots de passe correspondent
                if ($_POST['password'] == $_POST['confirm_password']) {
                    // Stocker le mot de passe en session
                    $_SESSION['password'] = password_hash($_POST['password'], PASSWORD_DEFAULT);
                    if(updateMdp($_SESSION['password'], $_SESSION['email'], $_SESSION['login'])){
                        addinjournal(getemployebyemail($_SESSION['email'])[0]['id'], "Définition du mot de passe");
                        // echo "<div class='alert success' style='margin: 10px 0px '><p style='color: red'><i class='fas fa-check-circle'></i> Vous avez finaliser votre compte. <a href='../../assurance/'>connectez-vous</a> pour pouvoir acceder a votre tableau de bord.</p></div>";
                        header('location: ../../assurance/');
                    }else{
                        echo "<div class='alert danger' style='margin: 10px 0px '><p style='color: red'><i class='fas fa-exclamation-triangle'></i> Une erreur est survenue lors de la modification du mot de passe. Reessayer et si l'erreur persiste, contacter le responsable du site.</p></div>";
                    }
                    // Rediriger vers le tableau de bord
                } else {
                    echo "<div class='alert danger' style='margin: 10px 0px '><p style='color: red'><i class='fas fa-exclamation-triangle'></i> Les mots de passe ne correspondent pas.</p></div>";
                }
            }
        }
    }