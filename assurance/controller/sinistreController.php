<?php
// Inclusion du fichier de configuration de la base de données
include_once('../models/db.php');
// include_once('../controller/contratController.php');

// Variable pour stocker les messages
$msg = "";


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
function getContractById($id) {
    global $bdd; // Utiliser $bdd au lieu de $db pour être cohérent
    $query = $bdd->prepare("SELECT * FROM contrat WHERE id = ?"); // Utiliser 'contrat' au lieu de 'contrats'
    $query->execute([$id]);
    return $query->fetch(PDO::FETCH_ASSOC);
}
// Traitement du formulaire d'ajout de sinistre
if(isset($_POST['save'])) {
    // Récupération des données du formulaire
    $contrat_id = isset($_GET['id']) ? intval($_GET['id']) : null;
    $reference = htmlspecialchars($_POST['reference']);
    $libelle = htmlspecialchars($_POST['libelle']);
    $garantie = htmlspecialchars($_POST['garantie']);
    $nature = htmlspecialchars($_POST['nature']);
    $date_declaration = htmlspecialchars($_POST['date_declaration']);
    $date_validation = htmlspecialchars($_POST['date_validation']);
    $montant_expertise = floatval($_POST['montant_expertise']);
    $montant_indemnise = floatval($_POST['montant_indemnise']);
    $motif = htmlspecialchars($_POST['motif']);
    
    // Création de la requête SQL d'insertion
    $query = "INSERT INTO sinistres (contrat_id, reference, libelle, garantie_souscrite, nature_sinistre, 
                date_declaration, date_validation, montant_expertise, montant_indemnise, motif) 
              VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
    // Préparation de la requête
    $stmt = $bdd->prepare($query);
    
    // Exécution de la requête avec les valeurs
    if($stmt->execute([$contrat_id, $reference, $libelle, $garantie, $nature, $date_declaration, 
                      $date_validation, $montant_expertise, $montant_indemnise, $motif])) {
        $msg = "Sinistre ajouté avec succès";
        
        // Redirection vers la liste des sinistres après 2 secondes
        header("refresh:2;url=liste_sinistre.php");
    } else {
        $msg = "Erreur lors de l'ajout du sinistre";
    }
}

// Fonction pour récupérer tous les sinistres avec les informations des contrats associés
function getAllSinistres() {
    global $bdd;
    $query = "SELECT s.*, c.ref as contrat_ref, c.libelle as contrat_libelle 
              FROM sinistres s 
              LEFT JOIN contrat c ON s.contrat_id = c.id 
              ORDER BY s.date_declaration DESC";
    $stmt = $bdd->prepare($query);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Fonction pour récupérer un sinistre par son ID avec les informations du contrat associé
function getSinistreById($id) {
    global $bdd;
    $query = "SELECT s.*, c.ref as contrat_ref, c.libelle as contrat_libelle 
              FROM sinistres s 
              LEFT JOIN contrat c ON s.contrat_id = c.id 
              WHERE s.id = ?";
    $stmt = $bdd->prepare($query);
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Fonction pour mettre à jour un sinistre
function updateSinistre($id, $contrat_id, $reference, $libelle, $garantie, $nature, $date_declaration, 
                        $date_validation, $montant_expertise, $montant_indemnise, $motif) {
    global $bdd;
    $query = "UPDATE sinistres SET contrat_id = ?, reference = ?, libelle = ?, garantie_souscrite = ?, 
              nature_sinistre = ?, date_declaration = ?, date_validation = ?, 
              montant_expertise = ?, montant_indemnise = ?, motif = ? WHERE id = ?";
    $stmt = $bdd->prepare($query);
    return $stmt->execute([$contrat_id, $reference, $libelle, $garantie, $nature, $date_declaration, 
                          $date_validation, $montant_expertise, $montant_indemnise, $motif, $id]);
}

// Fonction pour supprimer un sinistre
function deleteSinistre($id) {
    global $bdd;
    $query = "DELETE FROM sinistres WHERE id = ?";
    $stmt = $bdd->prepare($query);
    return $stmt->execute([$id]);
}

// Fonction pour récupérer les sinistres par contrat
function getSinistresByContratId($contrat_id) {
    global $bdd;
    $query = "SELECT * FROM sinistres WHERE contrat_id = ? ORDER BY date_declaration DESC";
    $stmt = $bdd->prepare($query);
    $stmt->execute([$contrat_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Fonction pour rechercher des sinistres par référence ou libellé
function getSinistresByRefOrByLabel($text) {
    global $bdd;
    $text = strtolower($text);
    $query = "SELECT s.*, c.ref as contrat_ref, c.libelle as contrat_libelle 
              FROM sinistres s 
              LEFT JOIN contrat c ON s.contrat_id = c.id 
              WHERE s.reference LIKE ? OR s.libelle LIKE ? 
              ORDER BY s.date_declaration DESC";
    $stmt = $bdd->prepare($query);
    $stmt->execute(["%$text%", "%$text%"]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Fonction pour obtenir le nombre total de sinistres
function getSinistresLength() {
    global $bdd;
    $query = "SELECT COUNT(*) as total FROM sinistres";
    $stmt = $bdd->prepare($query);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result['total'];
}

// Fonction pour vérifier si un sinistre existe
function checkIfSinistreExist($id) {
    global $bdd;
    $query = "SELECT COUNT(*) as count FROM sinistres WHERE id = ?";
    $stmt = $bdd->prepare($query);
    $stmt->execute([$id]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result['count'] > 0;
}
?>