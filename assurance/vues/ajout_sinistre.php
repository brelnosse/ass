<?php
session_start();
include('../controller/sinistreController.php');
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assurance - Déclarer un sinistre</title>
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
        <h1 class="root"><button onclick="history.back()" class="btn btn-primary"><i class="fas fa-arrow-left" style="margin-right: 0"></i></button> Sinistres / Nouveau sinistre</h1>
        <form method="post">
            <h2>Veuillez saisir les informations du sinistre :</h2>
            <?php if (!empty($msg)) { ?>
                <div class="info">
                    <i class="fas fa-check-circle"></i>
                    <?php echo $msg; ?>
                </div>
            <?php } ?>
            <hr>
            <div class="inputContainer">
                <div class="input-item">
                    <label for="contrat_id">Contrat associé <sup style="color: red">*</sup></label>
                    <select name="contrat_id" id="contrat_id" required onchange="chargerInfosContrat()">
                        <option value="">Sélectionner un contrat</option>
                        <?php
                        if(!isset($_GET['id'])){
                            // Récupérer la liste des contrats
                            $contrats = getContracts(0);
                            foreach ($contrats as $contrat) {
                                echo '<option value="' . $contrat['id'] . '" 
                                        data-reference="' . $contrat['ref'] . '" 
                                        data-libelle="' . $contrat['libelle'] . '">'
                                    . $contrat['ref'] . ' - ' . $contrat['libelle'] . '</option>';
                            }
                        }else {    
                            $contrats = getContractById(htmlspecialchars($_GET['id']));
                            echo '<option value="' . $contrats['id'] . '" 
                                    data-reference="' . $contrats['ref'] . '" 
                                    data-libelle="' . $contrats['libelle'] . '" selected>'
                                . $contrats['ref'] . ' - ' . $contrats['libelle'] . '</option>';
                        }

                        ?>
                    </select>
                </div>

                <div class="input-item">
                    <label for="reference">Référence <sup style="color: red">*</sup></label>
                    <input type="text" name="reference" id="reference" class="input" required
                        value="SIN-<?php echo date('Ymd') . '-' . rand(1000, 9999); ?>" readonly>
                </div>

                <div class="input-item">
                    <label for="libelle">Libellé <sup style="color: red">*</sup></label>
                    <input type="text" name="libelle" id="libelle" class="input" required>
                </div>

                <div class="input-item">
                    <label for="garantie">Garantie souscrite <sup style="color: red">*</sup></label>
                    <select name="garantie" id="garantie" required>
                        <option value="">Choisir une garantie</option>
                        <option value="Tous risques">Tous risques</option>
                        <option value="Responsabilité civile">Responsabilité civile</option>
                        <option value="Dommages corporels">Dommages corporels</option>
                        <option value="Bris de glace">Bris de glace</option>
                    </select>
                </div>

                <div class="input-item">
                    <label for="nature">Nature du sinistre <sup style="color: red">*</sup></label>
                    <select name="nature" id="nature" required>
                        <option value="">Sélectionner une nature</option>
                        <option value="Matériel">Matériel</option>
                        <option value="Corporel">Corporel</option>
                        <option value="Vol">Vol</option>
                        <option value="Incendie">Incendie</option>
                        <option value="Catastrophe naturelle">Catastrophe naturelle</option>
                    </select>
                </div>

                <div class="input-item">
                    <label for="date_declaration">Date déclaration <sup style="color: red">*</sup></label>
                    <input type="date" name="date_declaration" id="date_declaration" class="input" required
                        value="<?php echo date('Y-m-d'); ?>">
                </div>

                <div class="input-item">
                    <label for="date_validation">Date Validation <sup style="color: red">*</sup></label>
                    <input type="date" name="date_validation" id="date_validation" class="input" required>
                </div>

                <div class="input-item">
                    <label for="montant_expertise">Montant Expertise (en FCFA) <sup style="color: red">*</sup></label>
                    <input type="number" name="montant_expertise" id="montant_expertise" class="input" min="0"
                        step="0.01" required>
                </div>

                <div class="input-item">
                    <label for="montant_indemnise">Montant Indemnisé (en FCFA) <sup style="color: red">*</sup></label>
                    <input type="number" name="montant_indemnise" id="montant_indemnise" class="input" min="0"
                        step="0.01" required>
                </div>

                <div class="input-item blob">
                    <label for="motif">Motif <sup style="color: red">*</sup></label>
                    <textarea name="motif" id="motif" required></textarea>
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
    <script>
        // Fonction pour charger automatiquement les informations du contrat sélectionné
        function chargerInfosContrat() {
            const contratSelect = document.getElementById('contrat_id');
            const selectedOption = contratSelect.options[contratSelect.selectedIndex];

            if (selectedOption.value) {
                // Récupérer les attributs data du contrat sélectionné
                // document.getElementById('reference').value = selectedOption.getAttribute('data-reference');
                document.getElementById('libelle').value = selectedOption.getAttribute('data-libelle');
            } else {
                // Réinitialiser les champs si aucun contrat n'est sélectionné
                document.getElementById('libelle').value = '';
            }
        }
    </script>
</body>

</html>