<?php
    function checkIfCodeExist($code){
        include('../../Models/db.php');
        $getcode = $bdd->query('SELECT * FROM employe WHERE auth_key = "'.$code.'"');
        if($getcode->rowCount() == 0){
            return $code;
        }else{
            return false;
        }
    }
    function checkIfEmailExist($email){
        include('../../Models/db.php');
        $getemail = $bdd->query('SELECT * FROM employe WHERE email = "'.$email.'" AND status_compte != "unactive"');
        return $getemail->rowCount() == 1;
    }
    function checkIfEmployeExist($email, $auth_key){
        include('../../Models/db.php');
        $getuser = $bdd->prepare("SELECT * FROM employe WHERE auth_key = ? AND email = ? AND status_compte != 'unactive'");
        $getuser->execute(array($auth_key, $email));
        if($getuser->rowCount() == 1){
            return $getuser->fetchAll(PDO::FETCH_ASSOC);
        }
        return false;        
    }
    function addEmploye($nom, $prenom, $email, $phone, $poste, $bdate, $photo, $auth_key){
        include('../../Models/db.php');
        if(checkIfEmailExist($email)){
            return false;
        }else{
            $addEmp = $bdd->prepare('INSERT INTO employe(nom, prenom, email, phone, poste, bdate, photo, auth_key, mdp, added_date, status) VALUES(?,?,?,?,?,?,?,?, null, CURDATE(),"disconnected")');
            return $addEmp->execute(array($nom, $prenom, $email, $phone, $poste, $bdate, $photo, $auth_key));
        }
    }

    function updateMdp($mdp, $email, $auth_key){
        include('../../Models/db.php');
        if(checkIfEmployeExist($email, $auth_key)){
            $setMdp = $bdd->prepare('UPDATE employe SET mdp = ? WHERE (email = ? and auth_key = ?) AND status_compte != "unactive"');
            return $setMdp->execute(array($mdp, $email, $auth_key));
        }
    }
    function updateEmpMailstatus($email){
        include('../../Models/db.php');
        $setMailstatus = $bdd->prepare('UPDATE employe SET received_email = ? WHERE email = ? AND status_compte != "unactive"');
        $setMailstatus->execute(array(1, $email));
    }
    function getemploye($search = null, $date = null, $date_crea = null, $ordre = 'ASC', $email_status = null, $status = null, $start = 0, $isAnd = null){
        include('../../Models/db.php');
        $query = 'SELECT * FROM employe ';
        $args = array();

        if($search != null || $date != null || $date_crea != null || $status != null || $email_status != null){
            $query .= ' WHERE ';
        }
        if($search != null){
            $args['search'] = ' (nom LIKE "'.$search.'%" OR prenom LIKE "'.$search.'%" OR poste LIKE "'.$search.'%" OR email LIKE "'.$search.'%")';
        }
        if($date != null){
            $args['date'] = ' bdate = "'.$date.'" ';
        }
        if($date_crea != null){
            $args['date_crea'] = ' added_date = "'.$date_crea.'" ';
        }
        if($email_status != null){
            if($email_status == 'sent') $args['email_status'] = ' received_email IS NOT NULL ';
            elseif ($email_status == 'not_sent') {
                $args['email_status'] = ' received_email IS NULL ';
            }else{}
        }
        if($status != null){
            $args['status'] = ' status = "'.$status.'" ';
        }
        $last = null;
        if($ordre != null){
            if(in_array('WHERE', explode(' ', $query))){
                $last = ' ORDER BY id '.$ordre;
            }else{
                $last = ' ORDER BY id ';
            }
        }
        if($isAnd != null){
            if($isAnd == 'true') $params = implode(' AND ', $args);
            else $params = implode(' OR ', $args);
        }else{
            $params = implode(' OR ', $args);
        }
        $query .= $params;

        if($last != null){
            $query.=$last;
        }

        $query .= " LIMIT 10 OFFSET ".$start;
        $getempl = $bdd->query($query);
        $arr = json_decode(json_encode($getempl->fetchAll(PDO::FETCH_ASSOC)));
        $arr['count'] = $getempl->rowCount();
        // $arr['test'] = $query;
        echo json_encode($arr);
    }
    function getemployenum(){
        include('../../Models/db.php');
        $getnum = $bdd->query("SELECT COUNT(*) AS num FROM employe");
        return $getnum->fetch()['num'];  
    }
    function getemployebyid($id){
        include('../../Models/db.php');
        $getuser = $bdd->prepare("SELECT * FROM employe WHERE id = ?");
        $getuser->execute(array($id));
        if($getuser->rowCount() == 1){
            return $getuser->fetchAll(PDO::FETCH_ASSOC);
        }
        return false;  
    }
    function updateName($user_id, $value){
        include('../../Models/db.php');
        if(getemployebyid($user_id)){
            $stmt = $bdd->prepare("SELECT nom FROM employe WHERE id = ?");
            $stmt->execute(array($user_id));
            $get = $stmt->fetch()['nom'];
            if($get == $value){
                return true;
            }
            $setusername = $bdd->prepare('UPDATE employe SET nom = ? WHERE id = ?');
            return $setusername->execute(array($value, $user_id));
        }
    }
    function updatePrenom($user_id, $value){
        include('../../Models/db.php');
        if(getemployebyid($user_id)){
            $stmt = $bdd->prepare("SELECT prenom FROM employe WHERE id = ?");
            $stmt->execute(array($user_id));
            $get = $stmt->fetch()['prenom'];
            if($get == $value){
                return true;
            }
            $setfirstname = $bdd->prepare('UPDATE employe SET prenom = ? WHERE id = ?');
            return $setfirstname->execute(array($value, $user_id));
        }
    }
    function updateEmail($user_id, $value){
        include('../../Models/db.php');
        if(getemployebyid($user_id)){
            $stmt = $bdd->prepare("SELECT COUNT(*) AS nbre FROM employe WHERE email = ? AND id != ?");
            $stmt->execute(array($value, $user_id));
            $get = $stmt->fetch()['nbre'];
            if($get > 0){
                return false;
            }
            $setemail = $bdd->prepare('UPDATE employe SET email = ? WHERE id = ?');
            // addinjournal($user_id, 'Modification du numéro de l\'email utilisateur.');
            return $setemail->execute(array($value, $user_id));
        }
    }
    function updatePoste($user_id, $value){
        include('../../Models/db.php');
        if(getemployebyid($user_id)){
            $stmt = $bdd->prepare("SELECT poste FROM employe WHERE id = ?");
            $stmt->execute(array($user_id));
            $get = $stmt->fetch()['poste'];
            if($get == $value){
                return true;
            }
            $setposte = $bdd->prepare('UPDATE employe SET poste = ? WHERE id = ?');
            addinjournal($user_id, 'Modification du poste');
            return $setposte->execute(array($value, $user_id));
        }
    }
    function updateBdate($user_id, $value){
        include('../../Models/db.php');
        if(getemployebyid($user_id)){
            $stmt = $bdd->prepare("SELECT bdate FROM employe WHERE id = ?");
            $stmt->execute(array($user_id));
            $get = $stmt->fetch()['bdate'];
            if($get == $value){
                return true;
            }
            $setbdate = $bdd->prepare('UPDATE employe SET bdate = ? WHERE id = ?');
            addinjournal($user_id, 'Modification de la date de naissance');
            return $setbdate->execute(array($value, $user_id));
        }
    }
    function updatePhone($user_id, $value){
        include('../../Models/db.php');
        if(getemployebyid($user_id)){
            $stmt = $bdd->prepare("SELECT phone FROM employe WHERE id = ?");
            $stmt->execute(array($user_id));
            $get = $stmt->fetch()['phone'];
            if($get == $value){
                return true;
            }
            $setphone = $bdd->prepare('UPDATE employe SET phone = ? WHERE id = ?');
            addinjournal($user_id, 'Modification du numéro de téléphone');
            return $setphone->execute(array($value, $user_id));
        }
    }
    function updatePhoto($user_id, $value){
        include('../../Models/db.php');
        if(getemployebyid($user_id)){
            $stmt = $bdd->prepare("SELECT photo FROM employe WHERE id = ?");
            $stmt->execute(array($user_id));
            $get = $stmt->fetch()['photo'];
            if($get == $value){
                return true;
            }
            $setphoto = $bdd->prepare('UPDATE employe SET photo = ? WHERE id = ?');
            addinjournal($user_id, 'Modification de la photo de profil');
            return $setphoto->execute(array($value, $user_id));
        }
    }
    function addinjournal($id_emp, $label){
        include('../../Models/db.php');
        $addnews = $bdd->prepare('INSERT INTO journal(id_emp, period, label) VALUES(?,NOW(),?)');
        return $addnews->execute(array($id_emp, $label));       
    }
    function getjournal($user_id){
        include('../../Models/db.php');
        if(getemployebyid($user_id)){
            $stmt = $bdd->prepare("SELECT * FROM journal WHERE id_emp = ?");
            $stmt->execute(array($user_id));
            if($stmt->rowCount() > 0){
                while($data = $stmt->fetch()){
                    $time = explode("-", $data['period']);
                    $time = implode("/", $time);
                    echo "<p>".$time." - ".$data['label']."</p>";
                }
            }else{
                echo "<p>Aucunes information dans le journal.</p>";
            }
        }
    }
    function updateaccountstate($user_id, $status){
        include('../../Models/db.php');
        if(getemployebyid($user_id)){
            $label = "";
            $date = "";
            if($status == "unactive"){
                $date = ", blocked_date = NOW()";
                $label = "désactivé";
            }else{
                $date = ", blocked_date = NULL";
                $label = "activé";
            }
            $stmt = $bdd->prepare('UPDATE employe SET status_compte = ?'.$date.' WHERE id = ?');
            addinjournal($user_id, 'Compte '.$label);
            return $stmt->execute(array($status, $user_id));
        }
    }
    function getemployebyemail($email){
        include('../../Models/db.php');
        $getuser = $bdd->prepare("SELECT * FROM employe WHERE email = ?");
        $getuser->execute(array($email));
        if($getuser->rowCount() == 1){
            return $getuser->fetchAll(PDO::FETCH_ASSOC);
        }
        return false;          
    }
    function decrypt($hashedLogin){
        include('../../Models/db.php');
        $getuser = $bdd->query("SELECT auth_key FROM employe");
        while($data = $getuser->fetch()){
            if(sha1($data['auth_key']) == $hashedLogin){
                $login = $data['auth_key'];
                return $login; 
                break;
            }
        } 
        return false;   
    }
    function getemailstatus($login){
        include('../../Models/db.php');
        $stmt = $bdd->prepare("SELECT received_email FROM employe WHERE auth_key = ?");
        $stmt->execute(array($login));
        $emailstate = $stmt->fetch()["received_email"];
        if($emailstate == null || empty($emailstate)){
            return true;
        }
        return false; 
    }
    function createPasswordResetToken($email) {
        include('../../Models/db.php');

        $token = bin2hex(random_bytes(32));
        $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));
        
        // Supprimer les anciens tokens pour cet email
        $stmt = $bdd->prepare('DELETE FROM password_resets WHERE email = ?');
        $stmt->execute([$email]);
        
        // Créer un nouveau token
        $stmt = $bdd->prepare('INSERT INTO password_resets (email, token, expires_at) VALUES (?, ?, ?)');
        $stmt->execute([$email, $token, $expires]);
        
        return $token;
    }
    function validateResetToken($token, $email) {
        include('../../Models/db.php');
        $stmt = $bdd->prepare('SELECT * FROM password_resets WHERE token = ? AND email = ? AND expires_at < NOW()');
        $stmt->execute([$token, $email]);
        return $stmt->fetch() ? true : false;
    }
    
    function saveNewPassword($email, $password) {
        include('../../Models/db.php');
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        
        $stmt = $bdd->prepare('UPDATE employe SET mdp = ? WHERE email = ?');
        $result = $stmt->execute([$hashedPassword, $email]);
        
        // Supprimer le token après utilisation
        $stmt = $bdd->prepare('DELETE FROM password_resets WHERE email = ?');
        $stmt->execute([$email]);
        
        return $result;
    }
    
    function getUserByEmail($email) {
        include('../../Models/db.php');

        $stmt = $bdd->prepare('SELECT * FROM employe WHERE email = ?');
        $stmt->execute([$email]);
        return $stmt->fetch();
    }