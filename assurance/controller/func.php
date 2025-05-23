<?php
    // Récupérer un contrat par son ID

    function getuserbyid($id){
        require '../models/db.php';
        $stmt = $bdd->prepare('SELECT * FROM employe WHERE id = ?');
        $stmt->execute(array($id));
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Envoie de notification au RASD

    function checkifcontractsexpired(){
        require '../models/db.php';
        $stmt = $bdd->query('SELECT * FROM contrat WHERE edate <= CURDATE()');
        if($stmt->rowCount() > 0){
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        return false;
    }

    function checkifnotifexist($rasd_id, $id_contrat){
        require '../models/db.php';
        $stmt = $bdd->query('SELECT * FROM notif WHERE id_rasd = '.$rasd_id.' AND id_contrat = '.$id_contrat.' AND date_env = CURDATE()');
        if($stmt->rowCount() > 0){
            return true;
        }
        return false;
    }
    function updatenotif($rasd_id){
        require '../models/db.php';
        $stmt = $bdd->prepare('UPDATE notif SET status = "1" WHERE id_rasd = ? ');
        $stmt->execute(array($rasd_id));  
    }

    function addnotif($rasd_id){
        require '../models/db.php';
        if(checkifcontractsexpired()){
            $expiredcontracts = checkifcontractsexpired();
            foreach($expiredcontracts as $expiredcontract){
                if(!checkifnotifexist($rasd_id, $expiredcontract['id'])){
                    $stmt = $bdd->prepare('INSERT INTO notif(id_contrat, id_rasd, status, date_env) VALUES(?, ?, "0", CURDATE())');
                    $stmt->execute(array($expiredcontract['id'], $rasd_id));  
                }else{
                    return false;
                }
            }     
            return true;     
        }
        return false;
    }
    function getunreadednotif($rasd_id){
        require '../models/db.php';
        $stmt = $bdd->query('SELECT * FROM notif WHERE status = "0" AND id_rasd = '.$rasd_id);
        if($stmt->rowCount() > 0){
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        return false;
    }
    function getallnotif($rasd_id){
        require '../models/db.php';
        $stmt = $bdd->query('SELECT * FROM notif WHERE id_rasd = '.$rasd_id.' ORDER by id DESC');
        if($stmt->rowCount() > 0){
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        return [];
    }
    function getcontratbyrefn($ref){
        require('../models/db.php');
        $ref= strtolower($ref);
        $get = $bdd->query('SELECT * FROM contrat WHERE ref LIKE "%'.$ref.'%"');
        return $get->fetchAll(PDO::FETCH_ASSOC);       
    }
    function getdemandebyrefcontrat($cont_ref){
        require('../models/db.php');
        $cont_ref = strtolower($cont_ref);
        $get = $bdd->query('SELECT DA.*
                            FROM demandes_assurance DA, contrat C
                            WHERE DA.reference = C.client_ref AND C.ref = "'.$cont_ref.'"');
        return $get->fetchAll(PDO::FETCH_ASSOC);           
    }
    function getContractByIdn($id) {
        require('../models/db.php');
        $query = $bdd->prepare("SELECT * FROM contrat WHERE id = ?"); // Utiliser 'contrat' au lieu de 'contrats'
        $query->execute([$id]);
        return $query->fetch(PDO::FETCH_ASSOC);
    }
    //ajout de donnees a la table archive
    function updateArchive($num_reg, $client, $employe, $lib_sinistre, $ref_sinistre, $date_reg, $date_ajout, $montant){
        require('../models/db.php');
        $query = $bdd->prepare("SELECT * FROM archive WHERE client = ? AND ref_sinistre = ? AND employe = ?");
        $query->execute([$client, $ref_sinistre, $employe]);

        if($query->rowCount() == 1){
            $stmt = $bdd->prepare('UPDATE archive SET date_ajout = CURDATE(), montant = montant + ? WHERE client = ? AND ref_sinistre = ? AND employe = ?');
            $stmt->execute(array($montant, $client, $ref_sinistre, $employe));              
        }else{
            $stmt = $bdd->prepare("INSERT INTO archive(num_reg, client, employe, lib_sinistre, ref_sinistre, date_reg, date_ajout, montant) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$num_reg, $client, $employe, $lib_sinistre, $ref_sinistre, $date_reg, $date_ajout, $montant]);            
        }
    }