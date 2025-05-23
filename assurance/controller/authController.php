<?php 
    require_once '../models/dbs.php';
    function manageError($errorType){
        switch ($errorType) {
            case '1':
                return "Nom d'utilisateur ou mot de passe incorrect.";
                break;
            default:
                return false;
                break;
        }
    }
    function getUser($pseudo, $mdp){
        require '../models/db.php';
        $stmt = $bdd->prepare('SELECT * FROM employe WHERE auth_key = ?');
        $stmt->execute(array($pseudo));
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if($user && password_verify($mdp, $user['mdp'])){
            return $user['id'];       
        }
        return 0;
    }
    function userExist($pseudo, $mdp){
        require '../models/db.php';
        $stmt = $bdd->prepare('SELECT mdp FROM employe WHERE auth_key = ? AND status_compte != "unactive"');
        $stmt->execute(array($pseudo));
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if($user && password_verify($mdp, $user['mdp'])){
            $_SESSION['user_id'] = getUser($pseudo, $mdp);
            $_SESSION['user_poste'] = getrolebyid(getUser($pseudo, $mdp));
            setcookie("connection", "connected", time()+5*60);
            header('location: index.php');
        }else{
            header('location: ?err=1');
        }
    }
    function getrolebyid($id){
        require '../models/db.php';
        $stmt = $bdd->prepare('SELECT poste FROM employe WHERE id = ?');
        $stmt->execute(array($id));
        $role = $stmt->fetch(PDO::FETCH_ASSOC);
        return $role['poste'];	
    }
    function clear($str){
        $str = htmlspecialchars($str);
        return $str;
    }
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        if(isset($_POST['mdp'], $_POST['username'])){
            $mdp = clear($_POST['mdp']);
            $username = clear($_POST['username']);
            
            userExist($username, $mdp);
        }
    }
    function isLoginError(){
        if(isset($_GET['err'])){
            if(manageError(htmlspecialchars($_GET['err']))){
                echo "<span class='msg info'><p><i class='fas fa-exclamation-triangle'></i>".manageError(htmlspecialchars($_GET['err']))."</p></span>";   
            }
        }
    }
    function updateConnectionStatus($id){
        require '../models/db.php';
        if(isset($_COOKIE['connection'])){
            $stmt = $bdd->prepare("UPDATE employe SET status = ? WHERE id = ?");
            $stmt->execute(array('connected', $id));
            return true;
        }else{
            $stmt = $bdd->prepare("UPDATE employe SET status = ? WHERE id = ?");
            $stmt->execute(array('disconnected', $id));
            return true;
        }
    }
?>