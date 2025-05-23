<?php
session_start();
include('../controller/contratController.php');
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assurance - Ajouter un contrat</title>
    <link rel="stylesheet" href="ressources/css/menu.css">
    <link rel="stylesheet" href="ressources/css/common.css">
    <link rel="stylesheet" href="ressources/css/ajout_contrat.css">
</head>

<body>
    <?php
    include('components/navbar.php');
    include('components/verticalmenu.php');
    ?>
    <div class="container">
        <h1 class="root"><button onclick="history.back()" class="btn btn-primary"><i class="fas fa-arrow-left" style="margin-right: 0"></i></button> Contrats / Nouveau contrat</h1>
        <form method="post">
            <h2>Veuillez saisir les informations suivantes :</h2>
            <?php if (!empty($msg)) { ?>
                <div class="info">
                    <i class="fas fa-check-circle"></i>
                    Nouveau contrat créé avec succès.
                </div>
            <?php } ?>
            <hr>
            <div class="inputContainer">
                <div class="input-item">
                    <label for="ref">Référence <sup style="color: red">*</sup></label>
                    <input type="text" name="ref" id="ref" class="input" required
                        value="CON-<?php echo date('Ymd') . '-' . rand(1000, 9999); ?>" readonly>
                </div>
                <div class="input-item">
                    <label for="ass">Assureur <sup style="color: red">*</sup></label>
                    <select name="ass" id="ass" required>
                        <option value="">Choisir un assureur</option>
                        <option value="AXA">AXA</option>
                        <option value="Allianz">Allianz</option>
                        <option value="MAIF">MAIF</option>
                        <option value="MACIF">MACIF</option>
                    </select>
                </div>

                <div class="input-item">
                    <label for="montant">Montant <sup style="color: red">*</sup></label>
                    <input type="number" name="montant" id="montant" class="input" min="0" step="0.01" required>
                </div>

                <div class="input-item">
                    <label for="libelle">Libellé <sup style="color: red">*</sup></label>
                    <input type="text" name="libelle" id="libelle" class="input" required>
                </div>

                <div class="input-item">
                    <label for="modpass">Mode de passation <sup style="color: red">*</sup></label>
                    <select name="modpass" id="modpass" required>
                        <option value="">Sélectionner un mode</option>
                        <option value="Appel d'offres">Appel d'offres</option>
                        <option value="Gré à gré">Gré à gré</option>
                        <option value="Consultation restreinte">Consultation restreinte</option>
                    </select>
                </div>

                <div class="input-item">
                    <label for="devise">Devise <sup style="color: red">*</sup></label>
                    <select name="devise" id="devise" required>
                        <option value="">Sélectionner une devise</option>
                        <option value="EUR">Euro (€)</option>
                        <option value="USD">Dollar ($)</option>
                        <option value="FCFA">FCFA </option>
                    </select>
                </div>

                <div class="input-item">
                    <label for="bdate">Date de début <sup style="color: red">*</sup></label>
                    <input type="date" name="bdate" id="bdate" class="input" required>
                </div>

                <div class="input-item">
                    <label for="typeCont">Type du contrat <sup style="color: red">*</sup></label>
                    <select name="typeCont" id="typeCont" required>
                        <option value="">Sélectionner un type</option>
                        <option value="Assurance Auto">Assurance Auto</option>
                        <option value="Assurance Habitation">Assurance Habitation</option>
                        <option value="Assurance Santé">Assurance Santé</option>
                        <option value="Assurance Vie">Assurance Vie</option>
                    </select>
                </div>

                <div class="input-item blob">
                    <label for="objCont">Objet du contrat <sup style="color: red">*</sup></label>
                    <textarea name="objCont" id="objCont" required></textarea>
                </div>

                <div class="input-item">
                    <label for="edate">Date de fin <sup style="color: red">*</sup></label>
                    <input type="date" name="edate" id="edate" class="input" required>
                </div>

                <div class="input-item">
                    <label for="natCont">Nature du contrat <sup style="color: red">*</sup></label>
                    <select name="natCont" id="natCont" required>
                        <option value="">Sélectionner une nature</option>
                        <option value="Temporaire">Temporaire</option>
                        <option value="Permanent">Permanent</option>
                        <option value="Renouvelable">Renouvelable</option>
                    </select>
                </div>
            </div>
            <hr>
            <div class="footer">
                <button onclick="history.back()" class="btn def">Annuler</button>
                <input type="submit" value="Enregistrer" name="save" class="btn green">
            </div>
        </form>
    </div>
    <script src="ressources/js/all.js"></script>
    <script src="ressources/js/menu.js"></script>
</body>

</html>