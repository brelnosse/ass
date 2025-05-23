<?php
session_start();

// Vérification de l'authentification
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

require_once '../controller/demandeController.php';

$controller = new DemandeController();
$msg = '';
$msgType = '';

try {
    if (isset($_POST['save'])) {
        // Nettoyage des données
        $_POST = array_map('trim', $_POST);

        $result = $controller->creerDemande($_POST, $_FILES);

        if ($result['success']) {
            $_SESSION['success_message'] = 'Demande créée avec succès';
            header("Location: index.php");
            exit;
        } else {
            $msg = $result['message'];
            $msgType = 'error';
        }
    }
} catch (Exception $e) {
    $msg = "Une erreur est survenue : " . $e->getMessage();
    $msgType = 'error';
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nouvelle demande d'assurance</title>
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
        <h1 class="root"><button onclick="history.back()" class="btn btn-primary"><i class="fas fa-arrow-left" style="margin-right: 0"></i></button> Demandes / Nouvelle demande</h1>

        <?php if (!empty($msg)): ?>
            <div class="alert alert-<?php echo $msgType === 'error' ? 'danger' : 'success'; ?>">
                <?php echo htmlspecialchars($msg); ?>
            </div>
        <?php endif; ?>

        <form method="post" enctype="multipart/form-data" id="demandeForm" onsubmit="return validateForm()">
            <!-- Le reste du formulaire HTML reste identique -->

            <h2>Veuillez saisir les informations de la demande d'assurance :</h2>
            <?php if (!empty($msg)) { ?>
                <div class="info">
                    <i class="fas fa-check-circle"></i>
                    <?php echo htmlspecialchars($msg); ?>
                </div>
            <?php } ?>
            <hr>
            <div class="inputContainer">
                <div class="input-item">
                    <label for="type_assurance">Type d'assurance <sup style="color: red">*</sup></label>
                    <select name="type_assurance" id="type_assurance" required onchange="updateFormFields()">
                        <option value="">Sélectionner un type</option>
                        <option value="Auto">Assurance Auto</option>
                        <option value="Habitation">Assurance Habitation</option>
                        <option value="Santé">Assurance Santé</option>
                        <option value="Vie">Assurance Vie</option>
                        <option value="Professionnelle">Assurance Professionnelle</option>
                    </select>
                </div>

                <div class="input-item">
                    <label for="reference">Référence <sup style="color: red">*</sup></label>
                    <input type="text" name="reference" id="reference" class="input" required
                        value="DEM-<?php echo date('Ymd') . '-' . rand(1000, 9999); ?>" readonly>
                </div>

                <div class="input-item">
                    <label for="nom_client">Nom du client <sup style="color: red">*</sup></label>
                    <input type="text" name="nom_client" id="nom_client" class="input" required>
                </div>

                <div class="input-item">
                    <label for="prenom_client">Prénom du client <sup style="color: red">*</sup></label>
                    <input type="text" name="prenom_client" id="prenom_client" class="input" required>
                </div>

                <div class="input-item">
                    <label for="email">Email <sup style="color: red">*</sup></label>
                    <input type="email" name="email" id="email" class="input" required>
                </div>

                <div class="input-item">
                    <label for="telephone">Téléphone <sup style="color: red">*</sup></label>
                    <input type="tel" name="telephone" id="telephone" class="input" required>
                </div>

                <div class="input-item">
                    <label for="date_naissance">Date de naissance <sup style="color: red">*</sup></label>
                    <input type="date" name="date_naissance" id="date_naissance" value="<?= date("Y-m-d") ?>" class="input" required>
                </div>

                <div class="input-item">
                    <label for="formule">Formule souhaitée <sup style="color: red">*</sup></label>
                    <select name="formule" id="formule" required>
                        <option value="">Choisir une formule</option>
                        <option value="Basique">Basique</option>
                        <option value="Standard">Standard</option>
                        <option value="Premium">Premium</option>
                        <option value="Sur-mesure">Sur-mesure</option>
                    </select>
                </div>

                <!-- Champs dynamiques pour Auto -->
                <div class="dynamic-fields auto-fields" style="display: none;">
                    <div class="input-item">
                        <label for="marque_vehicule">Marque du véhicule <sup style="color: red">*</sup></label>
                        <input type="text" name="marque_vehicule" id="marque_vehicule" class="input">
                    </div>
                    <div class="input-item">
                        <label for="modele_vehicule">Modèle du véhicule <sup style="color: red">*</sup></label>
                        <input type="text" name="modele_vehicule" id="modele_vehicule" class="input">
                    </div>
                    <div class="input-item">
                        <label for="annee_vehicule">Année du véhicule <sup style="color: red">*</sup></label>
                        <input type="number" name="annee_vehicule" id="annee_vehicule" class="input" min="1900"
                            max="<?php echo date('Y'); ?>">
                    </div>
                    <div class="input-item">
                        <label for="immatriculation">Immatriculation <sup style="color: red">*</sup></label>
                        <input type="text" name="immatriculation" id="immatriculation" class="input">
                    </div>
                </div>

                <!-- Champs dynamiques pour Habitation -->
                <div class="dynamic-fields habitation-fields" style="display: none;">
                    <div class="input-item">
                        <label for="type_logement">Type de logement <sup style="color: red">*</sup></label>
                        <select name="type_logement" id="type_logement" class="input">
                            <option value="">Sélectionner un type</option>
                            <option value="Appartement">Appartement</option>
                            <option value="Maison">Maison</option>
                            <option value="Villa">Villa</option>
                            <option value="Studio">Studio</option>
                        </select>
                    </div>
                    <div class="input-item">
                        <label for="superficie">Superficie (m²) <sup style="color: red">*</sup></label>
                        <input type="number" name="superficie" id="superficie" class="input" min="1">
                    </div>
                    <div class="input-item">
                        <label for="adresse_bien">Adresse du bien <sup style="color: red">*</sup></label>
                        <textarea name="adresse_bien" id="adresse_bien" class="input"></textarea>
                    </div>
                </div>

                <!-- Champs dynamiques pour Santé -->
                <div class="dynamic-fields sante-fields" style="display: none;">
                    <div class="input-item">
                        <label for="situation_familiale">Situation familiale <sup style="color: red">*</sup></label>
                        <select name="situation_familiale" id="situation_familiale" class="input">
                            <option value="">Sélectionner une situation</option>
                            <option value="Célibataire">Célibataire</option>
                            <option value="Marié(e)">Marié(e)</option>
                            <option value="Divorcé(e)">Divorcé(e)</option>
                            <option value="Veuf(ve)">Veuf(ve)</option>
                        </select>
                    </div>
                    <div class="input-item">
                        <label for="nombre_beneficiaires">Nombre de bénéficiaires <sup
                                style="color: red">*</sup></label>
                        <input type="number" name="nombre_beneficiaires" id="nombre_beneficiaires" class="input"
                            min="0">
                    </div>
                    <div class="input-item">
                        <label for="profession">Profession <sup style="color: red">*</sup></label>
                        <input type="text" name="profession" id="profession" class="input">
                    </div>
                </div>

                <div class="input-item">
                    <label for="date_soumission">Date de soumission <sup style="color: red">*</sup></label>
                    <input type="date" name="date_soumission" id="date_soumission" class="input" required
                        value="<?php echo date('Y-m-d'); ?>" readonly>
                </div>

                <div class="input-item">
                    <label for="date_effet_souhaitee">Date d'effet souhaitée <sup style="color: red">*</sup></label>
                    <input type="date" name="date_effet_souhaitee" id="date_effet_souhaitee" class="input" required>
                </div>

                <div class="input-item">
                    <label for="pieces_jointes">Pièces justificatives</label>
                    <input type="file" name="pieces_jointes[]" id="pieces_jointes" class="input" multiple>
                    <small>Formats acceptés: PDF, JPG, PNG (max 5Mo par fichier)</small>
                </div>

                <div class="input-item blob">
                    <label for="commentaires">Commentaires supplémentaires</label>
                    <textarea name="commentaires" id="commentaires"></textarea>
                </div>
            </div>
            <hr>
            <div class="footer">
                <a href="index.php" class="btn def" style="min-width: 150px">Annuler</a>
                <input type="submit" value="Soumettre la demande" name="save" class="btn green">
            </div>
        </form>
    </div>

    <script>
        function validateForm() {
            const typeAssurance = document.getElementById('type_assurance').value;
            const email = document.getElementById('email').value;
            const telephone = document.getElementById('telephone').value;
            const dateEffet = document.getElementById('date_effet_souhaitee').value;

            // Validation de l'email
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email)) {
                alert('Veuillez entrer une adresse email valide');
                return false;
            }

            // Validation du téléphone
            const telRegex = /^[0-9+\s-]{8,}$/;
            if (!telRegex.test(telephone)) {
                alert('Veuillez entrer un numéro de téléphone valide');
                return false;
            }

            // Validation de la date d'effet
            const today = new Date();
            const effectDate = new Date(dateEffet);
            if (effectDate < today) {
                alert('La date d\'effet ne peut pas être dans le passé');
                return false;
            }

            // Validation des champs spécifiques selon le type d'assurance
            switch (typeAssurance) {
                case 'Auto':
                    if (!validateAutoFields()) return false;
                    break;
                case 'Habitation':
                    if (!validateHabitationFields()) return false;
                    break;
                case 'Santé':
                    if (!validateSanteFields()) return false;
                    break;
            }

            return true;
        }

        function validateAutoFields() {
            const annee = document.getElementById('annee_vehicule').value;
            const currentYear = new Date().getFullYear();

            if (annee < 1900 || annee > currentYear) {
                alert('Année du véhicule invalide');
                return false;
            }
            return true;
        }

        function validateHabitationFields() {
            const superficie = document.getElementById('superficie').value;
            if (superficie <= 0) {
                alert('La superficie doit être supérieure à 0');
                return false;
            }
            return true;
        }

        function validateSanteFields() {
            const beneficiaires = document.getElementById('nombre_beneficiaires').value;
            if (beneficiaires < 0) {
                alert('Le nombre de bénéficiaires ne peut pas être négatif');
                return false;
            }
            return true;
        }

        function updateFormFields() {
    const typeAssurance = document.getElementById('type_assurance').value;
    
    // Hide all dynamic fields sections first
    const dynamicFields = document.querySelectorAll('.dynamic-fields');
    dynamicFields.forEach(field => {
        field.style.display = 'none';
    });
    
    // Show the relevant fields based on selected insurance type
    if (typeAssurance === 'Auto') {
        document.querySelector('.auto-fields').style.display = 'block';
        
        // Make auto-specific fields required
        document.getElementById('marque_vehicule').required = true;
        document.getElementById('modele_vehicule').required = true;
        document.getElementById('annee_vehicule').required = true;
        document.getElementById('immatriculation').required = true;
        
        // Remove required from other type-specific fields
        document.getElementById('type_logement').required = false;
        document.getElementById('superficie').required = false;
        document.getElementById('adresse_bien').required = false;
        document.getElementById('situation_familiale').required = false;
        document.getElementById('nombre_beneficiaires').required = false;
        document.getElementById('profession').required = false;
        
    } else if (typeAssurance === 'Habitation') {
        document.querySelector('.habitation-fields').style.display = 'block';
        
        // Make habitation-specific fields required
        document.getElementById('type_logement').required = true;
        document.getElementById('superficie').required = true;
        document.getElementById('adresse_bien').required = true;
        
        // Remove required from other type-specific fields
        document.getElementById('marque_vehicule').required = false;
        document.getElementById('modele_vehicule').required = false;
        document.getElementById('annee_vehicule').required = false;
        document.getElementById('immatriculation').required = false;
        document.getElementById('situation_familiale').required = false;
        document.getElementById('nombre_beneficiaires').required = false;
        document.getElementById('profession').required = false;
        
    } else if (typeAssurance === 'Santé') {
        document.querySelector('.sante-fields').style.display = 'block';
        
        // Make health-specific fields required
        document.getElementById('situation_familiale').required = true;
        document.getElementById('nombre_beneficiaires').required = true;
        document.getElementById('profession').required = true;
        
        // Remove required from other type-specific fields
        document.getElementById('marque_vehicule').required = false;
        document.getElementById('modele_vehicule').required = false;
        document.getElementById('annee_vehicule').required = false;
        document.getElementById('immatriculation').required = false;
        document.getElementById('type_logement').required = false;
        document.getElementById('superficie').required = false;
        document.getElementById('adresse_bien').required = false;
    } else {
        // For Vie or Professionnelle, no specific fields are shown
        // Remove required attribute from all type-specific fields
        document.getElementById('marque_vehicule').required = false;
        document.getElementById('modele_vehicule').required = false;
        document.getElementById('annee_vehicule').required = false;
        document.getElementById('immatriculation').required = false;
        document.getElementById('type_logement').required = false;
        document.getElementById('superficie').required = false;
        document.getElementById('adresse_bien').required = false;
        document.getElementById('situation_familiale').required = false;
        document.getElementById('nombre_beneficiaires').required = false;
        document.getElementById('profession').required = false;
    }
}

        // Initialisation
        document.addEventListener('DOMContentLoaded', function () {
            updateFormFields();
        });
    </script>
    <script src="ressources/js/all.js"></script>
    <script src="ressources/js/menu.js"></script>
</body>

</html>