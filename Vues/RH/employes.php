<?php
    session_start();
    include('../../Controller/registerController.php');
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mini Dashboard</title>
    <link rel="stylesheet" href="../../ressources/css/common.css">
    <link rel="stylesheet" href="../../ressources/css/menu.css">
    <link rel="stylesheet" href="../../ressources/css/employes.css">
</head>
<body>
    <?php
        include('components/navbar.php');    
    ?>
    <div class="container">
        <div class="dashboard-header" method="post">
            <div class="search-container">
                <input type="text" class="search-input" id="search" placeholder="Rechercher un utilisateur...">
                <button class="search-btn"><i class="fas fa-search"></i></button>
            </div>
            
            <div class="filters-container">
                <div class="filter-group"> 
                    <span class="filter-label">Date naissance:</span>
                    <input type="date" class="filter-input" id="bdate">
                </div>
                <div class="filter-group">
                    <span class="filter-label">Date d'ajout:</span>
                    <input type="date" class="filter-input" id="added_date">
                </div>
                
                <div class="filter-group">
                    <span class="filter-label">Ordre:</span>
                    <select class="filter-select" id="order" style="height: 50px">
                        <option value="">Par d&eacute;faut</option>
                        <option value="ASC">Croissant</option>
                        <option value="DESC">Décroissant</option>
                    </select>
                </div>
                
                <div class="filter-group">
                    <span class="filter-label">Status:</span>
                    <select class="filter-select" id="status" style="height: 50px">
                        <option value="">Tous</option>
                        <option value="connected">Connecté</option>
                        <option value="disconnected">Déconnecté</option>
                    </select>
                </div>
                
                <div class="filter-group">
                    <span class="filter-label">Email:</span>
                    <select class="filter-select" id="mailstatus" style="height: 50px">
                        <option value="">Tous</option>
                        <option value="sent">Envoyé</option>
                        <option value="not_sent">Non envoyé</option>
                    </select>
                </div>
                <div class="filter-group">
                    <input type="checkbox" class="filter-input" id="isAnd">
                    <span class="filter-label">Appliqué simultanement </span>
                </div>
            </div>
            <div class="cancelContainer">
                <button class="btn danger"  id="cancel" disabled><i class="fas fa-ban"></i> Retirer les filtres</button>
            </div>
        </div>
        
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Prénom</th>
                        <th>Email</th>
                        <th>Code</th>
                        <th>Poste</th>
                        <th>Date de création</th>
                        <th>Téléphone</th>
                        <th>Email Status</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody class="trainees">

                </tbody>
            </table>
        </div>
        
        <div class="dashboard-footer">
            <div class="results-info">
                <i class="first-part">Affichage de 1 à 10</i>
                <b class="second-part"></b>
            </div>
            
            <div class="pagination">
            </div>
        </div>
        <div class="loaderc" id="loaderc">
            <div class="loadercontainer">
                <div class="cercle"></div>
                <div class="enfant">
                    <div class="tete">
                    <div class="oeil-gauche"></div>
                    <div class="oeil-droit"></div>
                    <div class="sourire"></div>
                    </div>
                    <div class="corps"></div>
                    <div class="bras-gauche"></div>
                    <div class="bras-droit"></div>
                    <div class="jambe-gauche"></div>
                    <div class="jambe-droite"></div>
                </div>
            </div> 
        </div>
    </div>
    <script src="employes.js"></script>
    <script src="../../ressources/js/all.js"></script>
    <script>
        resultsInfoSecond.innerHTML = `
            sur <?= getemployenum() ?> entrées
        `;
    </script>
</body>
</html>