<?php
session_start();
require_once('../controller/demandeController.php');

// Vérifier si l'ID de la demande est passé en paramètre
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$id = (int)$_GET['id'];
$controller = new DemandeController();
$demande = $controller->getDemandeById($id);

if (!$demande) {
    header("Location: index.php?error=notfound");
    exit();
}

// Gestion de la soumission du formulaire de modification
$msg = '';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        // Nettoyage des données
        $_POST = array_map('trim', $_POST);

        // Mise à jour de la demande
        if ($controller->updateDemande($id, $_POST)) {
            header("Location: index.php?update=success");
            exit();
        } else {
            $msg = "Erreur lors de la modification de la demande.";
        }
    } catch (Exception $e) {
        $msg = "Une erreur est survenue : " . htmlspecialchars($e->getMessage());
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier une demande</title>
    <link rel="stylesheet" href="ressources/css/menu.css">
    <link rel="stylesheet" href="ressources/css/common.css">
    <link rel="stylesheet" href="ressources/css/contrat_modif.css">
</head>
<body>
    <?php
        include('components/navbar.php');
        include('components/verticalmenu.php');
    ?>
    <div class="container">
        <h1 class="root"><button onclick="history.back()" class="btn btn-primary"><i class="fas fa-arrow-left" style="margin-right: 0"></i></button> Demandes / Modifier une demande</h1>
        
        <form method="post">
            <?php if (!empty($msg)): ?>
                <div class="error-message"><?= htmlspecialchars($msg) ?></div>
            <?php endif; ?>
            
            <div>
                <label for="reference">Référence :</label>
                <input type="text" id="reference" name="reference" 
                       value="<?= htmlspecialchars($demande['reference']) ?>" readonly>
            </div>
            
            <div>
                <label for="type_assurance">Type d'assurance :</label>
                <select id="type_assurance" name="type_assurance" required>
                    <option value="Auto" <?= $demande['type_assurance'] == 'Auto' ? 'selected' : '' ?>>Assurance Auto</option>
                    <option value="Habitation" <?= $demande['type_assurance'] == 'Habitation' ? 'selected' : '' ?>>Assurance Habitation</option>
                    <option value="Santé" <?= $demande['type_assurance'] == 'Santé' ? 'selected' : '' ?>>Assurance Santé</option>
                    <option value="Vie" <?= $demande['type_assurance'] == 'Vie' ? 'selected' : '' ?>>Assurance Vie</option>
                    <option value="Professionnelle" <?= $demande['type_assurance'] == 'Professionnelle' ? 'selected' : '' ?>>Assurance Professionnelle</option>
                </select>
            </div>

            <div>
                <label for="nom_client">Nom du client :</label>
                <input type="text" id="nom_client" name="nom_client" 
                       value="<?= htmlspecialchars($demande['nom_client']) ?>" required>
            </div>

            <div>
                <label for="prenom_client">Prénom du client :</label>
                <input type="text" id="prenom_client" name="prenom_client" 
                       value="<?= htmlspecialchars($demande['prenom_client']) ?>" required>
            </div>

            <div>
                <label for="email">Email :</label>
                <input type="email" id="email" name="email" 
                       value="<?= htmlspecialchars($demande['email']) ?>" required>
            </div>

            <div>
                <label for="telephone">Téléphone :</label>
                <input type="tel" id="telephone" name="telephone" 
                       value="<?= htmlspecialchars($demande['telephone']) ?>" required>
            </div>

            <div>
                <label for="date_naissance">Date de naissance :</label>
                <input type="date" id="date_naissance" name="date_naissance" 
                       value="<?= htmlspecialchars($demande['date_naissance']) ?>" required>
            </div>

            <div>
                <label for="formule">Formule souhaitée :</label>
                <select id="formule" name="formule" required>
                    <option value="Basique" <?= $demande['formule'] == 'Basique' ? 'selected' : '' ?>>Basique</option>
                    <option value="Standard" <?= $demande['formule'] == 'Standard' ? 'selected' : '' ?>>Standard</option>
                    <option value="Premium" <?= $demande['formule'] == 'Premium' ? 'selected' : '' ?>>Premium</option>
                    <option value="Sur-mesure" <?= $demande['formule'] == 'Sur-mesure' ? 'selected' : '' ?>>Sur-mesure</option>
                </select>
            </div>

            <div>
                <label for="date_effet_souhaitee">Date d'effet souhaitée :</label>
                <input type="date" id="date_effet_souhaitee" name="date_effet_souhaitee" 
                       value="<?= htmlspecialchars($demande['date_effet_souhaitee']) ?>" required>
            </div>

            <div>
                <label for="commentaires">Commentaires :</label>
                <textarea id="commentaires" name="commentaires"><?= htmlspecialchars($demande['commentaires']) ?></textarea>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-primary">Enregistrer les modifications</button>
                <a href="index.php" class="btn-secondary">Annuler</a>
            </div>
        </form>
    </div>
    <script src="ressources/js/all.js"></script>
    <script src="ressources/js/menu.js"></script>
</body>
</html>