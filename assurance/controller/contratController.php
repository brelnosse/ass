<?php
require_once('../models/db.php');
$msg = "";
$page =  explode('?', basename($_SERVER['REQUEST_URI']));
$clientref = null;
if($page[0] == 'ajout_contrat.php'){
    if (!isset($_GET['ref']) OR empty($_GET['ref'])){
        header('location: index.php');
    }
    $clientref = htmlspecialchars($_GET['ref']);

}
function addContrat($ref, $clientref, $libelle, $bdate, $edate, $ass, $modpass, $typeCont, $natCont, $montant, $devise, $objCont){
    global $bdd; // Ajout de global $bdd
    
    $add = $bdd->prepare('INSERT INTO contrat(ref, client_ref, libelle, bdate, edate, ass, modpass, typeCont, natCont, montant, devise, objCont) VALUES(?,?,?,?,?,?,?,?,?,?,?,?)');
    return $add->execute(array($ref, $clientref, $libelle, $bdate, $edate, $ass, $modpass, $typeCont, $natCont, $montant, $devise, $objCont));
}

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    if(isset($_POST['save'])){
        // Vérification de l'existence des clés avant utilisation
        $ref = isset($_POST['ref']) ? htmlspecialchars($_POST['ref']) : '';
        $libelle = isset($_POST['libelle']) ? htmlspecialchars($_POST['libelle']) : '';
        $bdate = isset($_POST['bdate']) ? htmlspecialchars($_POST['bdate']) : '';
        $edate = isset($_POST['edate']) ? htmlspecialchars($_POST['edate']) : '';
        $ass = isset($_POST['ass']) ? htmlspecialchars($_POST['ass']) : '';
        $modpass = isset($_POST['modpass']) ? htmlspecialchars($_POST['modpass']) : '';
        $typeCont = isset($_POST['typeCont']) ? htmlspecialchars($_POST['typeCont']) : '';
        $natCont = isset($_POST['natCont']) ? htmlspecialchars($_POST['natCont']) : '';
        $montant = isset($_POST['montant']) ? (int)htmlspecialchars($_POST['montant']) : 0;
        $devise = isset($_POST['devise']) ? htmlspecialchars($_POST['devise']) : '';
        $objCont = isset($_POST['objCont']) ? htmlspecialchars($_POST['objCont']) : '';

        if(addContrat($ref, $clientref, $libelle, $bdate, $edate, $ass, $modpass, $typeCont, $natCont, $montant, $devise, $objCont)){
            $msg = "Ajout reussi !";
            header('refresh:1;url=liste_contrat.php');
        }
    }
}
    if(isset($_GET['search'])){
        foreach(getContratByRefOrByLabel(htmlspecialchars($_GET['search'])) as $key => $item){
            echo "<tr class='table-line'>
                    <td><span>".$item['ref']."</span></td>
                    <td><span>".$item['libelle']."</span></td>
                    <td><span>".$item['ass']."</span></td>
                    <td><span>".$item['montant']." ".$item['devise']."</span></td>
                    <td><span>".$item['bdate']."</span></td>
                    <td><span>".($item['edate'] < date('Y-m-d') ? 'Expiré' : 'En cours')."</span></td>
                    <td class='actionBtn'>
                        <button type='button' class='editItem' id='".$item['id']."'>
                            <i class='fas fa-pencil-alt'></i>
                        </button>
                        <button type='button' class='removeItem' id='".$item['id']."'>
                            <i class='fas fa-trash-alt'></i>
                        </button>
                    </td>
                  </tr>";
        }
    }
    
    function updateContract($id, $ref, $ass, $montant, $libelle, $modpass, $devise, $bdate, $edate, $typeCont, $objCont, $natCont) {
        global $bdd; // Utiliser $bdd au lieu de $db
        $query = $bdd->prepare("UPDATE contrat SET ref=?, ass=?, montant=?, libelle=?, modpass=?, devise=?, bdate=?, edate=?, typeCont=?, objCont=?, natCont=? WHERE id=?"); // Utiliser 'contrat' au lieu de 'contrats'
        return $query->execute([$ref, $ass, $montant, $libelle, $modpass, $devise, $bdate, $edate, $typeCont, $objCont, $natCont, $id]);
    }
    function getContractById($id) {
        global $bdd; // Utiliser $bdd au lieu de $db pour être cohérent
        $query = $bdd->prepare("SELECT * FROM contrat WHERE id = ?"); // Utiliser 'contrat' au lieu de 'contrats'
        $query->execute([$id]);
        return $query->fetch(PDO::FETCH_ASSOC);
    }
    function getContracts($start){
        require('../models/db.php');
        $get;
        if($start == 0){
            $get = $bdd->query('SELECT * FROM contrat ORDER BY id DESC LIMIT 10');
        }else{
            $get = $bdd->query('SELECT * FROM contrat ORDER BY id DESC LIMIT 10 OFFSET '.$start);
        }
        
        return $get->fetchAll(PDO::FETCH_ASSOC);
    }
    function getContratByRefOrByLabel($text){
        require('../models/db.php');
        $text = strtolower($text);
        $get = $bdd->query('SELECT * FROM contrat WHERE ref LIKE "'.$text.'%" OR libelle LIKE "'.$text.'%" ORDER BY id DESC');
        return $get->fetchAll(PDO::FETCH_ASSOC);
    }
    function getcontratbyref($ref){
        require('../models/db.php');
        $ref = strtolower($ref);
        $get = $bdd->query('SELECT * FROM contrat WHERE ref LIKE "%'.$ref.'%"');
        return $get->fetchAll(PDO::FETCH_ASSOC);       
    }
    function getContractsLength(){
        require('../models/db.php');
        $get = $bdd->query('SELECT * FROM contrat ORDER BY id DESC');
        return $get->rowCount();
    }
    function removeContrat($id){
        require '../models/db.php';
        $checkifexist = $bdd->prepare('SELECT * FROM contrat WHERE id = ?');
        $checkifexist->execute(array($id));
        
        if(checkIfContratExist($id)){
            $del = $bdd->prepare('DELETE FROM contrat WHERE id = ?');
            $del->execute(array($id));
            return true;
        }
        return false;
    }
    function checkIfContratExist($id){
        require '../models/db.php';
        $checkifexist = $bdd->prepare('SELECT * FROM contrat WHERE id = ?');
        $checkifexist->execute(array($id));     
        if($checkifexist->rowCount() == 1){
            return true;
        }
        return false;
    }
    function getuserbyref($ref){
        require('../models/db.php');
        $get = $bdd->prepare('SELECT * FROM demandes_assurance WHERE reference = ?');
        $get->execute(array($ref));
        if($ref->rowCount() > 0){
            return $get->fetchAll(PDO::FETCH_ASSOC);       
        }
        return false;
    }