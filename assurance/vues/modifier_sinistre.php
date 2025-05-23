<?php
session_start();
include('../controller/sinistreController.php');

// Vérifier si l'ID du sinistre est passé en paramètre
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: liste_sinistre.php");
    exit();
}

$id = (int)$_GET['id'];
$sinistre = getSinistreById($id);

if (!$sinistre) {
    header("Location: liste_sinistre.php?error=notfound");
    exit();
}

// Récupérer la liste des contrats pour le select
$contrats = getContracts(0);

// Gestion de la soumission du formulaire de modification
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $reference = isset($_POST['reference']) ? htmlspecialchars($_POST['reference']) : '';
    $libelle = isset($_POST['libelle']) ? htmlspecialchars($_POST['libelle']) : '';
    $contrat_id = isset($_POST['contrat_id']) ? (int)$_POST['contrat_id'] : null;
    $nature_sinistre = isset($_POST['nature_sinistre']) ? htmlspecialchars($_POST['nature_sinistre']) : '';
    $date_declaration = isset($_POST['date_declaration']) ? htmlspecialchars($_POST['date_declaration']) : '';
    $date_validation = isset($_POST['date_validation']) ? htmlspecialchars($_POST['date_validation']) : '';
    $montant_indemnise = isset($_POST['montant_indemnise']) ? (float)$_POST['montant_indemnise'] : 0;
    $description = isset($_POST['description']) ? htmlspecialchars($_POST['description']) : '';
    $garantie = isset($_POST['garantie']) ? htmlspecialchars($_POST['garantie']) : '';
    $montant_expertise = isset($_POST['montant_expertise']) ? (float)$_POST['montant_expertise'] : 0;

    if (updateSinistre($id, $contrat_id, $reference, $libelle, $garantie, $nature_sinistre, 
                       $date_declaration, $date_validation, $montant_expertise, $montant_indemnise, $description)) {
        header("Location: liste_sinistre.php?update=success");
        exit();
    } else {
        $msg = "Erreur lors de la modification du sinistre.";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier un sinistre</title>
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
        <h1 class="root"><button onclick="history.back()" class="btn btn-primary"><i class="fas fa-arrow-left" style="margin-right: 0"></i></button> Sinistres / Modifier un sinistre</h1>
        
        <form method="post">
            <?php if(isset($msg) AND !empty($msg)): ?>
                <div class="error-message"><?= $msg ?></div>
            <?php endif; ?>
            
            <div>
                <label for="reference">Référence :</label>
                <input type="text" id="reference" name="reference" 
                       value="<?= htmlspecialchars($sinistre['reference']) ?>" required>
            </div>
            
            <div>
                <label for="libelle">Libellé :</label>
                <input type="text" id="libelle" name="libelle" 
                       value="<?= htmlspecialchars($sinistre['libelle']) ?>" required>
            </div>

            <div>
                <label for="contrat_id">Contrat associé :</label>
                <select id="contrat_id" name="contrat_id">
                    <option value="">-- Aucun contrat associé --</option>
                    <?php foreach($contrats as $contrat): ?>
                        <option value="<?= $contrat['id'] ?>" 
                                <?= $sinistre['contrat_id'] == $contrat['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($contrat['ref']) ?> - <?= htmlspecialchars($contrat['libelle']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div>
                <label for="garantie">Garantie souscrite :</label>
                <input type="text" id="garantie" name="garantie" 
                       value="<?= htmlspecialchars($sinistre['garantie_souscrite']) ?>" required>
            </div>

            <div>
                <label for="nature_sinistre">Nature du sinistre :</label>
                <select id="nature_sinistre" name="nature_sinistre" required>
                    <?php
                    $natures = ["Accident", "Incendie", "Vol", "Dégât des eaux", "Catastrophe naturelle"];
                    foreach($natures as $nature): ?>
                        <option value="<?= $nature ?>" 
                                <?= $sinistre['nature_sinistre'] == $nature ? 'selected' : '' ?>>
                            <?= $nature ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div>
                <label for="date_declaration">Date de déclaration :</label>
                <input type="date" id="date_declaration" name="date_declaration" 
                       value="<?= htmlspecialchars($sinistre['date_declaration']) ?>" disabled required>
            </div>

            <div>
                <label for="date_validation">Date de validation :</label>
                <input type="date" id="date_validation" name="date_validation" 
                       value="<?= htmlspecialchars($sinistre['date_validation']) ?>" required>
            </div>

            <div>
                <label for="montant_expertise">Montant expertise (FCFA) :</label>
                <input type="number" id="montant_expertise" name="montant_expertise" 
                       value="<?= htmlspecialchars($sinistre['montant_expertise']) ?>" required>
            </div>

            <div>
                <label for="montant_indemnise">Montant indemnisé (FCFA) :</label>
                <input type="number" id="montant_indemnise" name="montant_indemnise" 
                       value="<?= htmlspecialchars($sinistre['montant_indemnise']) ?>" required>
            </div>

            <div class="full-width">
                <label for="description">Description du sinistre :</label>
                <textarea id="description" name="description" required><?= htmlspecialchars($sinistre['motif']) ?></textarea>
            </div>

            <div class="form-actions full-width">
                <button type="submit" class="btn-primary">Enregistrer les modifications</button>
                <a href="liste_sinistre.php" class="btn-secondary">Annuler</a>
            </div>
        </form>
    </div>
    <script src="ressources/js/all.js"></script>
    <script src="ressources/js/menu.js"></script>
</body>
</html>