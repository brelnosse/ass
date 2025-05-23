<?php
function ajouterQuittance($num_reglement, $date_reglement, $montant, $sinistre, $ajouter_par) {
    global $bdd;

    // Vérifier si l'utilisateur est connecté
    if (!isset($_SESSION['user_id'])) {
        return "Erreur : Utilisateur non connecté.";
    }

    // Vérifier si le sinistre existe
    $stmt = $bdd->prepare("SELECT COUNT(*) FROM sinistres WHERE reference = ?");
    $stmt->execute([$sinistre]);
    if ($stmt->fetchColumn() == 0) {
        return "Erreur : Le sinistre avec la référence $sinistre n'existe pas.";
    }

    $date_ajout = date('Y-m-d H:i:s');

    // Insérer la quittance dans la base de données
    $stmt = $bdd->prepare("INSERT INTO quittances (num_reglement, date_reglement, montant, sinistre, date_ajout, ajouter_par) 
                           VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$num_reglement, $date_reglement, $montant, $sinistre, $date_ajout, $ajouter_par]);

    return "Quittance ajoutée avec succès.";
}