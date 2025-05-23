<?php
session_start();
include('../controller/contratController.php');

// Vérifier si l'ID du contrat est passé en paramètre
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: liste_contrat.php");
    exit();
}

$id = $_GET['id'];
$contrat = getContractById($id); // Fonction à créer dans contratController.php

if (!$contrat) {
    echo "Contrat introuvable.";
    exit();
}

// Gestion de la soumission du formulaire de modification
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $ref = $_POST['ref'];
    $ass = $_POST['ass'];
    $montant = $_POST['montant'];
    $libelle = $_POST['libelle'];
    $modpass = $_POST['modpass'];
    $devise = $_POST['devise'];
    $bdate = $_POST['bdate'];
    $edate = $_POST['edate'];
    $typeCont = $_POST['typeCont'];
    $objCont = $_POST['objCont'];
    $natCont = $_POST['natCont'];

    // Fonction de mise à jour du contrat
    if (updateContract($id, $ref, $ass, $montant, $libelle, $modpass, $devise, $bdate, $edate, $typeCont, $objCont, $natCont)) {
        header("Location: liste_contrat.php?update=success");
        exit();
    } else {
        $msg = "Erreur lors de la modification du contrat.";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier un contrat</title>
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
        <h1><button onclick="history.back()" class="btn btn-primary"><i class="fas fa-arrow-left" style="margin-right: 0"></i></button> Modifier le contrat</h1>
        
        <form method="post">
            <?php if(isset($msg) and !empty($msg)): ?>
                <div class="error-message full-width"><?= $msg ?></div>
            <?php endif; ?>
            
            <div>
                <label for="ref">Référence :</label>
                <input type="text" id="ref" name="ref" value="<?= htmlspecialchars($contrat['ref']) ?>" required>
            </div>
            
            <div>
                <label for="ass">Assureur :</label>
                <select id="ass" name="ass" required>
                    <option value="AXA" <?= $contrat['ass'] == "AXA" ? 'selected' : '' ?>>AXA</option>
                    <option value="Allianz" <?= $contrat['ass'] == "Allianz" ? 'selected' : '' ?>>Allianz</option>
                    <option value="MAIF" <?= $contrat['ass'] == "MAIF" ? 'selected' : '' ?>>MAIF</option>
                </select>
            </div>

            <div>
                <label for="montant">Montant :</label>
                <input type="number" id="montant" name="montant" value="<?= htmlspecialchars($contrat['montant']) ?>" required>
            </div>

            <div>
                <label for="libelle">Libellé :</label>
                <input type="text" id="libelle" name="libelle" value="<?= htmlspecialchars($contrat['libelle']) ?>" required>
            </div>

            <div>
                <label for="modpass">Mode de passation :</label>
                <select id="modpass" name="modpass" required>
                    <option value="Appel d'offres" <?= $contrat['modpass'] == "Appel d'offres" ? 'selected' : '' ?>>Appel d'offres</option>
                    <option value="Gré à gré" <?= $contrat['modpass'] == "Gré à gré" ? 'selected' : '' ?>>Gré à gré</option>
                </select>
            </div>

            <div>
                <label for="devise">Devise :</label>
                <select id="devise" name="devise" required>
                    <option value="EUR" <?= $contrat['devise'] == "EUR" ? 'selected' : '' ?>>Euro (€)</option>
                    <option value="USD" <?= $contrat['devise'] == "USD" ? 'selected' : '' ?>>Dollar ($)</option>
                    <option value="FCFA" <?= $contrat['devise'] == "FCFA" ? 'selected' : '' ?>> FCFA</option>
                </select>
            </div>

            <div>
                <label for="bdate">Date de début :</label>
                <input type="date" id="bdate" name="bdate" value="<?= htmlspecialchars($contrat['bdate']) ?>" required>
            </div>

            <div>
                <label for="edate">Date de fin :</label>
                <input type="date" id="edate" name="edate" value="<?= htmlspecialchars($contrat['edate']) ?>" required>
            </div>

            <div>
                <label for="typeCont">Type du contrat :</label>
                <select id="typeCont" name="typeCont" required>
                    <option value="Assurance Auto" <?= $contrat['typeCont'] == "Assurance Auto" ? 'selected' : '' ?>>Assurance Auto</option>
                    <option value="Assurance Santé" <?= $contrat['typeCont'] == "Assurance Santé" ? 'selected' : '' ?>>Assurance Santé</option>
                </select>
            </div>

            <div class="full-width">
                <label for="objCont">Objet du contrat :</label>
                <textarea id="objCont" name="objCont" required><?= htmlspecialchars($contrat['objCont']) ?></textarea>
            </div>

            <div class="full-width">
                <label for="natCont">Nature du contrat :</label>
                <select id="natCont" name="natCont" required>
                    <option value="Temporaire" <?= $contrat['natCont'] == "Temporaire" ? 'selected' : '' ?>>Temporaire</option>
                    <option value="Permanent" <?= $contrat['natCont'] == "Permanent" ? 'selected' : '' ?>>Permanent</option>
                </select>
            </div>

            <div class="form-actions full-width">
                <button type="submit" class="btn green">Enregistrer</button>
                <a href="liste_contrat.php" class="btn def">Annuler</a>
            </div>
        </form>
    </div>
    <script src="ressources/js/all.js"></script>
    <script src="ressources/js/menu.js"></script>
</body>
</html>