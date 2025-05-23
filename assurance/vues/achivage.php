<?php
session_start();
require '../models/db.php';
require '../controller/archiveController.php';

// Rediriger vers la page de connexion si l'utilisateur n'est pas connecté
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

// Récupérer les quittances
$quittances = getQuittances();
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Archive des Quittances</title>
    <link rel="stylesheet" href="ressources/css/menu.css">
    <link rel="stylesheet" href="ressources/css/common.css">
    <link rel="stylesheet" href="ressources/css/archive.css">
</head>

<body>
    <!-- Inclure la barre de navigation et le menu vertical -->
    <?php
    include('components/navbar.php');
    include('components/verticalmenu.php');
    ?>

    <div class="container">
        <h1 class="root"><button onclick="history.back()" class="btn btn-primary"><i class="fas fa-arrow-left" style="margin-right: 0"></i></button> Quittances / Archive</h1>

        <!-- Barre de recherche -->
        <div class="search-bar">
            <input type="text" id="searchInput" placeholder="Rechercher par N° Règlement..." onkeyup="filterQuittances()">
        </div>

        <div class="quittance-container" id="quittanceContainer">
            <?php foreach ($quittances as $quittance): ?>
                <div class="quittance-card" onclick="showModal(<?php echo htmlspecialchars(json_encode($quittance)); ?>)">
                    <h3>N° Règlement : <?php echo htmlspecialchars($quittance['num_reg'] ?? ''); ?></h3>
                    <p>Montant : <?php echo htmlspecialchars(number_format($quittance['montant'] ?? 0, 2)); ?> FCFA</p>
                    <p>Date Règlement : <?php echo htmlspecialchars($quittance['date_reg'] ?? ''); ?></p>
                    <p class="added-by">Ajouté par : <?php echo htmlspecialchars($quittance['employe'] ?? ''); ?></p>
                    <p class="client">Client : <?php echo htmlspecialchars($quittance['client'] ?? ''); ?></p>
                    <hr> 
                </div>
            <?php endforeach; ?>
        </div>
    </div>
           
    <!-- Modal -->
    <div id="modal" class="modal">
        <div class="modal-content">
            <button class="modal-close" onclick="closeModal()">Fermer</button>
            <h2 id="modal-title"></h2>
            <p id="modal-date"></p>
            <p id="modal-montant"></p>
            <p id="modal-sinistre"></p>
            <p id="modal-libelle"></p>
            <p id="modal-ajout"></p>
            <p id="modal-ajoute-par"></p>
            <p id="modal-client"></p>
        </div>
    </div>

    <script>
        // Afficher le modal avec les détails
        function showModal(quittance) {
            document.getElementById('modal-title').textContent = "N° Règlement : " + (quittance.num_reg || '');
            document.getElementById('modal-date').textContent = "Date Règlement : " + (quittance.date_reg || '');
            document.getElementById('modal-montant').textContent = "Montant : " + (quittance.montant ? quittance.montant.toLocaleString() + " FCFA" : '');
            document.getElementById('modal-sinistre').textContent = "Référence Sinistre : " + (quittance.ref_sinistre || '');
            document.getElementById('modal-libelle').textContent = "Libellé Sinistre : " + (quittance.lib_sinistre || '');
            document.getElementById('modal-ajout').textContent = "Date Ajout : " + (quittance.date_ajout || '');
            document.getElementById('modal-ajoute-par').textContent = "Ajouté par : " + (quittance.employe || '');
            document.getElementById('modal-client').textContent = "Client : " + (quittance.client || '');

            document.getElementById('modal').style.display = 'flex';
        }

        // Fermer le modal
        function closeModal() {
            document.getElementById('modal').style.display = 'none';
        }

        // Filtrer les quittances par N° Règlement
        function filterQuittances() {
            const input = document.getElementById('searchInput').value.toLowerCase();
            const cards = document.querySelectorAll('.quittance-card');

            cards.forEach(card => {
                const reglement = card.querySelector('h3').textContent.toLowerCase();
                if (reglement.includes(input)) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        }
    </script>
        <script src="ressources/js/all.js"></script>
        <script src="ressources/js/menu.js"></script>
</body>

</html>