<?php
require '../models/db.php';

$stmt = $bdd->prepare("SELECT * FROM quittances ORDER BY date_ajout DESC");
$stmt->execute();
$quittances = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (empty($quittances)) {
    echo "<tr><td colspan='6'>Aucune quittance enregistrée.</td></tr>";
} else {
    foreach ($quittances as $quittance) {
        echo "<tr>
                <td>{$quittance['num_reglement']}</td>
                <td>{$quittance['date_reglement']}</td>
                <td>{$quittance['montant']} FCFA</td>
                <td>{$quittance['sinistre']}</td>
                <td>{$quittance['date_ajout']}</td>
                <td>{$quittance['ajouter_par']}</td>
              </tr>";
    }
}


// Récupérer les paramètres de recherche et de tri
$search = isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '';
$sort = isset($_GET['sort']) ? htmlspecialchars($_GET['sort']) : 'date_ajout';
$order = isset($_GET['order']) && strtolower($_GET['order']) === 'asc' ? 'ASC' : 'DESC';

// Construire la requête SQL avec recherche et tri
$query = "SELECT * FROM quittances WHERE 
          num_reglement LIKE :search OR 
          sinistre LIKE :search OR 
          ajouter_par LIKE :search 
          ORDER BY $sort $order";

$stmt = $bdd->prepare($query);
$stmt->bindValue(':search', "%$search%", PDO::PARAM_STR);
$stmt->execute();
$quittances = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Retourner les données au format JSON
echo json_encode($quittances);
?>