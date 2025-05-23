<?php
    session_start();
    include('../controller/demandeController.php');
    // Instanciation du contrôleur
    $controller = new DemandeController();

    // Gestion de la suppression
    if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
        try {
            $id = (int) htmlspecialchars($_GET['id']);
            if ($controller->deleteDemande($id)) {
                $msg = "Demande supprimée avec succès.";
                $msgType = "success";
            } else {
                $msg = "Échec de la suppression de la demande.";
                $msgType = "error";
            }
        } catch (Exception $e) {
            $msg = "Une erreur est survenue : " . htmlspecialchars($e->getMessage());
            $msgType = "error";
        }
    }
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assurance - Liste des demandes</title>
    <link rel="stylesheet" href="ressources/css/menu.css">
    <!-- <link rel="stylesheet" href="ressources/css/ajout_contrat.css"> -->
    <link rel="stylesheet" href="ressources/css/common.css">
    <link rel="stylesheet" href="ressources/css/liste_demandes.css">
</head>
<body>
    <?php
        include('components/navbar.php');
        include('components/verticalmenu.php');
    ?>



    <div class="container">
        
        <h1 class="root"><button onclick="history.back()" class="btn btn-primary"><i class="fas fa-arrow-left" style="margin-right: 0"></i></button> Demandes / Liste des demandes</h1>
        <form method="post">
            <h2>Toutes les demandes</h2>
            <?php
                try {
                    if (isset($_GET['action']) && isset($_GET['id'])) {
                        switch ($_GET['action']) {
                            case 'delete':
                                if (function_exists('deleteDemande') && deleteDemande($_GET['id'])) { ?>
                                    <div class="info alert">
                                        <i class="fas fa-check-circle"></i>
                                        Demande supprimée avec succès.
                                    </div>
                                <?php
                                }
                                break;
                            default:
                                break;
                        }
                    }
                    if (!empty($msg)) { ?>
                        <div class="info">
                            <i class="fas fa-check-circle"></i>
                            Nouvelle demande créée avec succès.
                        </div>
                    <?php
                    }
                } catch (Exception $e) {
                    echo "<div class='error'>Une erreur est survenue : " . htmlspecialchars($e->getMessage()) . "</div>";
                }
            ?>
            <hr>
            <div class="filterTools">
                <div class="leftContainer"></div>
                <div class="rightContainer" id="searchcontainer">
                    <i class="fas fa-search"></i>
                    <input type="search" id="search" class="input" placeholder="Entrez la référence ou le nom du client...">
                </div>
            </div>
            <a href="ajout_demande.php" class="link-btn">
                <i class="fas fa-plus"></i>
                Nouvelle demande
            </a>
            <div class="table-container">
            <div class="table-container">
    <table class="liste_contrat" id="liste_demande">
        <thead>
            <tr>
                <th>Référence</th>
                <th>Nom</th>
                <th>Prénom</th>
                <th>Email</th>
                <th>Téléphone</th>
                <th>Type d'assurance</th>
                <th>Formule souhaitée</th> <!-- Nouvelle colonne -->
                <th>Date de soumission</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php
            try {
                $start = 0;
                if (isset($_GET['page'])) {
                    $start = (int)htmlspecialchars($_GET['page']);
                }

                // Appel des fonctions via le contrôleur
                $demandes = $controller->getAllDemandes($start);
                if (empty($demandes)) {
                    echo "<tr><td colspan='9' style='text-align: center; padding: 30px;'>Aucune demande pour le moment.</td></tr>";
                } else {
                    foreach ($demandes as $demande) {
                        echo "<tr class='table-line'>
                                <td><span>{$demande['reference']}</span></td>
                                <td><span>{$demande['nom_client']}</span></td>
                                <td><span>{$demande['prenom_client']}</span></td>
                                <td><span>{$demande['email']}</span></td>
                                <td><span>{$demande['telephone']}</span></td>
                                <td><span>{$demande['type_assurance']}</span></td>
                                <td><span>{$demande['formule']}</span></td> <!-- Affichage de la formule -->
                                <td><span>{$demande['date_soumission']}</span></td>
                                <td class='actionBtn'>
                                     <a href='ajout_contrat.php?ref={$demande['reference']}' class='editItem' title='nouveau contrat'>
                                        <i class='fas fa-file-contract'></i>
                                    </a>                                   
                                    <a href='modifier_demande.php?id={$demande['id']}' class='editItem' title='Modifier la demande'>
                                        <i class='fas fa-pencil-alt'></i>
                                    </a>
                                    <button type='button' class='removeItem' id='{$demande['id']}' title='supprimer la demande'>
                                        <i class='fas fa-trash-alt'></i>
                                    </button>
                                </td>
                              </tr>";
                    }
                }
            } catch (Exception $e) {
                echo "<tr><td colspan='9' style='text-align: center; padding: 30px;'>Erreur : " . htmlspecialchars($e->getMessage()) . "</td></tr>";
            }
        ?>
        <tr class="hide">
            <td colspan='9' style='text-align: center; padding: 30px;'>Aucun résultat trouvé.</td>
        </tr>
        </tbody>
    </table>
</div>
</div>
            <hr>
            <div class="footer">
                <?php
                    try {
                        $nbrePage = ceil($controller->getDemandesLength() / 10);
                        if ($nbrePage > 1) {
                            for ($i = 0; $i < $nbrePage; $i++) {
                                $activeClass = (isset($_GET['page']) && intval($_GET['page']) / 10 == $i) ? 'active' : '';
                                echo "<a href='?page=" . ($i * 10) . "#liste_demande' class='$activeClass'>" . ($i + 1) . "</a>";
                            }
                        }
                    } catch (Exception $e) {
                        echo "<div class='error'>Erreur : " . htmlspecialchars($e->getMessage()) . "</div>";
                    }
                ?>
            </div>
        </form>
    </div>
    <div class="modalContainer del" style="display: none">
        <div class="modalfen">
            <p>Voulez-vous vraiment supprimer cette demande ?</p>
            <div class="commandButton">
                <span class="active red deleteCont" id="">Supprimer</span>
                <span class="modalfen ccl">Annuler</span>
            </div>
        </div>
    </div>
    <script src="ressources/js/all.js"></script>
    <script src="ressources/js/menu.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Recherche de demandes
            const searchInput = document.getElementById('search');
            searchInput.addEventListener('keyup', function() {
                const searchText = this.value.toLowerCase();
                const tableLines = document.querySelectorAll('.table-line');
                const hideLine = document.querySelector('.hide');
                let found = false;
                
                tableLines.forEach(line => {
                    const text = line.textContent.toLowerCase();
                    if (text.includes(searchText)) {
                        line.style.display = '';
                        found = true;
                    } else {
                        line.style.display = 'none';
                    }
                });
                
                hideLine.style.display = found ? 'none' : '';
            });
            
            // Modal de suppression
            const btnsRemove = document.querySelectorAll('.removeItem');
            const modalContainer = document.querySelector('.modalContainer');
            const modalFenCcl = document.querySelector('.modalfen.ccl');
            const deleteCont = document.querySelector('.deleteCont');
            
            btnsRemove.forEach(function(btn) {
                btn.addEventListener('click', function() {
                    modalContainer.style.display = 'flex';
                    deleteCont.id = this.id;
                });
            });
            
            modalFenCcl.addEventListener('click', function() {
                modalContainer.style.display = 'none';
            });
            
            deleteCont.addEventListener('click', function() {
                window.location.href = '?action=delete&id=' + this.id + '#liste_demande';
            });
        });
    </script>
</body>
</html>