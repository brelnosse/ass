<?php
session_start();
require '../models/db.php';
require '../controller/func.php';
function getsinistre($sinistre) {
    global $bdd;
    $stmt = $bdd->prepare("SELECT * FROM quittances WHERE sinistre = ?");
    $stmt->execute([$sinistre]);

    if($stmt->rowCount() > 0) {
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } else {
        return false;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['num_reglement'], $_POST['date_reglement'], $_POST['montant'], $_POST['sinistre'], $_POST['ajouter_par'])) {
        $num_reglement = htmlspecialchars($_POST['num_reglement']);
        $date_reglement = htmlspecialchars($_POST['date_reglement']);
        $montant = htmlspecialchars($_POST['montant']);
        $sinistre = htmlspecialchars($_POST['sinistre']);
        $ajouter_par = htmlspecialchars($_POST['ajouter_par']);
        $date_ajout = date('Y-m-d H:i:s');

        if(getsinistre($sinistre)) {
            $stmt = $bdd->prepare("UPDATE quittances SET montant = montant + ?, date_reglement = ?  WHERE sinistre = ?");
            $stmt->execute([$montant, $date_ajout, $sinistre]);
            $stmt1 = $bdd->query("SELECT 
                q.num_reglement,
                COALESCE(s.libelle, '') AS sinistre_libelle,
                CONCAT(da.nom_client, ' ', da.prenom_client) AS nom_client
                FROM demandes_assurance da, contrat c, sinistres s, quittances q
                WHERE da.reference = c.client_ref AND c.id = s.contrat_id AND s.reference = q.sinistre AND q.sinistre = '".$sinistre."'
                ORDER BY q.date_ajout DESC
            ");
            $client = $stmt1->fetch();            
            updateArchive($num_reglement, $client['nom_client'], $ajouter_par, $client['sinistre_libelle'], $sinistre, $date_reglement, $date_ajout, $montant);
            echo "Quittance Modifiée avec succès.";
        }else{
            $stmt = $bdd->prepare("INSERT INTO quittances (num_reglement, date_reglement, montant, sinistre, date_ajout, ajouter_par) VALUES (?, ?, ?, ?, ?, ?)");
            if($stmt->execute([$num_reglement, $date_reglement, $montant, $sinistre, $date_ajout, $ajouter_par])){
                $stmt1 = $bdd->query("SELECT 
                q.num_reglement,
                COALESCE(s.libelle, '') AS sinistre_libelle,
                CONCAT(da.nom_client, ' ', da.prenom_client) AS nom_client
                FROM demandes_assurance da, contrat c, sinistres s, quittances q
                WHERE da.reference = c.client_ref AND c.id = s.contrat_id AND s.reference = q.sinistre AND q.sinistre = '".$sinistre."'
                ORDER BY q.date_ajout DESC
            ");
            $client = $stmt1->fetch();            
            updateArchive($num_reglement, $client['nom_client'], $ajouter_par, $client['sinistre_libelle'], $sinistre, $date_reglement, $date_ajout, $montant);
            }
            echo "Quittance ajoutée avec succès.";
        }
    } else {
        echo "Erreur : Données manquantes.";
    }
}
?>