<?php
session_start();
require '../models/db.php';

// Rediriger vers la page de connexion si l'utilisateur n'est pas connecté
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

// Récupérer les informations de l'utilisateur connecté
$stmt = $bdd->prepare("SELECT nom FROM employe WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
$nom_utilisateur = $user ? $user['nom'] : 'Utilisateur inconnu';
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assurance - Gestion des Quittances</title>
    <link rel="stylesheet" href="ressources/css/menu.css">
    <link rel="stylesheet" href="ressources/css/common.css">
    <link rel="stylesheet" href="ressources/css/ajout_quittance.css">
    <script src="ressources/js/jquery-3.7.1.min.js"></script>
</head>

<body>
    <!-- Inclure la barre de navigation et le menu vertical -->
    <?php
    include('components/navbar.php');
    include('components/verticalmenu.php');
    ?>

    <div class="container">
        <h1 class="root"><button onclick="history.back()" class="btn btn-primary"><i class="fas fa-arrow-left" style="margin-right: 0"></i></button> Quittances / Liste des quittances</h1>

        <div class="quittance-container">
            <div class="action-bar">
                <h2>Registre des quittances</h2>
                <button id="btnAjouter" class="btn green">
                    <i class="fas fa-plus"></i> Ajouter une quittance
                </button>
            </div>

            <div class="table-container">
                <table class="quittance-table">
                    <thead>
                        <tr>
                            <th>N°Règlement</th>
                            <th>Date règlement</th>
                            <th>Montant</th>
                            <th>Sinistre</th>
                            <th>Date Ajout</th>
                            <th>Ajouté par</th>
                        </tr>
                    </thead>
                    <tbody id="quittanceTable">
                        <!-- Les données seront chargées ici -->
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Modal d'ajout -->
        <div id="modalAjout" class="modal" style="display: none;">
            <div class="modal-content">
                <h2>Ajouter une Quittance</h2>
                <form id="formAjout">
                    <div class="inputContainer">
                        <div class="input-item">
                            <label for="num_reglement">N° Règlement <sup style="color: red">*</sup></label>
                            <input type="text" name="num_reglement" id="num_reglement" class="input" required
                                value="REG-<?php echo date('Ymd') . '-' . rand(1000, 9999); ?>" readonly>
                        </div>
                        <div class="input-item">
                            <label for="date_reglement">Date règlement</label>
                            <input type="date" name="date_reglement" id="date_reglement" required>
                        </div>
                        <div class="input-item">
                            <label for="montant">Montant (FCFA)</label>
                            <input type="number" name="montant" id="montant" min="0" step="0.01" required>
                        </div>
                        <div class="input-item">
                            <label for="sinistre">Référence Sinistre <sup style="color: red">*</sup></label>
                            <select name="sinistre" id="sinistre" required>
                                <option value="">Sélectionner un sinistre</option>
                                <?php
                                // Récupérer les références des sinistres
                                $stmt = $bdd->query("SELECT reference, libelle FROM sinistres ORDER BY date_declaration DESC");
                                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                    echo '<option value="' . htmlspecialchars($row['reference']) . '">' . htmlspecialchars($row['reference']) . ' - ' . htmlspecialchars($row['libelle']) . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                        <input type="hidden" name="ajouter_par" value="<?php echo $nom_utilisateur; ?>">
                    </div>
                    <button type="submit" class="btn green">Enregistrer</button>
                    <button type="button" class="btn red" id="btnAnnuler">Annuler</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            // Charger les quittances
            function loadQuittances() {
                $.get("get_quittances.php", function (data) {
                    $("#quittanceTable").html(data);
                });
            }

            // Afficher le modal d'ajout
            $("#btnAjouter").click(function () {
                $("#modalAjout").show();
            });

            // Fermer le modal avec le bouton "Annuler"
            $("#btnAnnuler").click(function () {
                $("#modalAjout").hide();
                $("#formAjout")[0].reset();
            });

            // Soumettre le formulaire d'ajout
            $("#formAjout").submit(function (e) {
                e.preventDefault();
                $.post("ajouter_quittance.php", $(this).serialize(), function (response) {
                    alert(response);
                    $("#modalAjout").hide();
                    loadQuittances();
                });
            });

            // Charger les quittances au démarrage
            loadQuittances();

            
        });
    </script>
    <script>
        document.getElementById('formAjout').addEventListener('submit', function (e) {
    const sinistre = document.getElementById('sinistre').value;
    if (!sinistre) {
        alert('Veuillez sélectionner une référence de sinistre.');
        e.preventDefault();
    }
});
    </script>
    <script src="ressources/js/all.js"></script>
    <script src="ressources/js/menu.js"></script>
</body>

</html>