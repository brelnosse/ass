<?php
    session_start();
    include('../controller/sinistreController.php');
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assurance - Liste des sinistres</title>
    <link rel="stylesheet" href="ressources/css/menu.css">
    <link rel="stylesheet" href="ressources/css/common.css">
    <link rel="stylesheet" href="ressources/css/ajout_contrat.css">
    <link rel="stylesheet" href="ressources/css/liste_contrat.css">
</head>
<body>
    <?php
        include('components/navbar.php');
        include('components/verticalmenu.php');
    ?>
    <div class="container">
        <h1 class="root"><button onclick="history.back()" class="btn btn-primary"><i class="fas fa-arrow-left" style="margin-right: 0"></i></button> Sinistres / Liste des sinistres</h1>
        <form method="post">
            <h2>Tous les sinistres</h2>
            <?php
                if (isset($_GET['action']) && isset($_GET['id'])) {
                    switch ($_GET['action']) {
                        case 'delete':
                            if (deleteSinistre($_GET['id'])) { ?>

                                <div class="info alert">
                                    <i class="fas fa-check-circle"></i>
                                    Sinistre supprimé avec succès.
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
                        Nouveau sinistre créé avec succès.
                    </div>
                <?php
                }
            ?>
            <hr>
            <div class="filterTools">
                <div class="leftContainer"></div>
                <div class="rightContainer" id="searchcontainer">
                    <i class="fas fa-search"></i>
                    <input type="search" id="search" class="input" placeholder="Entrez la référence ou le libellé...">
                </div>
            </div>
            <a href="ajout_sinistre.php" class="link-btn">
                <i class="fas fa-plus"></i>
                Nouveau sinistre
            </a>
            <div class="table-container">
                <table style="width: 100%" class="liste_contrat" id="liste_sinistre">
                    <thead>
                        <tr>
                            <th>Référence</th>
                            <th>Libellé</th>
                            <th>Contrat</th>
                            <th>Nature</th>
                            <th>Date déclaration</th>
                            <th>Montant indemnisé</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                        $start = 0;
                        if (isset($_GET['page'])) {
                            $start = (int)htmlspecialchars($_GET['page']);
                        }
                        $sinistres = getAllSinistres($start);
                        if (count($sinistres) === 0) {
                            echo "<tr><td colspan='8' style='text-align: center; padding: 30px;'>Aucun sinistre pour le moment.</td></tr>";
                        } else {
                            foreach ($sinistres as $sinistre) {
                                $statut = strtotime($sinistre['date_validation']) > time() ? 'En cours' : 'Traité';
                                echo "<tr class='table-line'>
                                        <td><span>{$sinistre['reference']}</span></td>
                                        <td><span>{$sinistre['libelle']}</span></td>
                                        <td><span>" . ($sinistre['contrat_ref'] ? $sinistre['contrat_ref'] : 'Non associé') . "</span></td>
                                        <td><span>{$sinistre['nature_sinistre']}</span></td>
                                        <td><span>{$sinistre['date_declaration']}</span></td>
                                        <td><span>{$sinistre['montant_indemnise']} FCFA</span></td>
                                        <td><span>{$statut}</span></td>
                                        <td class='actionBtn'>
                                            <a href='modifier_sinistre.php?id={$sinistre['id']}' class='editItem' title='Modifier le sinistre'>
                                                <i class='fas fa-pencil-alt'></i>
                                            </a>
                                            <button type='button' class='removeItem' id='{$sinistre['id']}' title='Supprimer le sinistre'>
                                                <i class='fas fa-trash-alt'></i>
                                            </button>
                                        </td>
                                    </tr>";
                            }
                        }
                    ?>
                    <tr class="hide">
                        <td colspan='8' style='text-align: center; padding: 30px;'>Aucun résultat trouvé.</td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <hr>
            <div class="footer">
                <?php
                    $nbrePage = ceil(getSinistresLength() / 10);
                    if ($nbrePage > 1) {
                        for ($i = 0; $i < $nbrePage; $i++) {
                            $activeClass = (isset($_GET['page']) && intval($_GET['page']) / 10 == $i) ? 'active' : '';
                            echo "<a href='?page=" . ($i * 10) . "#liste_sinistre' class='$activeClass'>" . ($i + 1) . "</a>";
                        }
                    }
                ?>
            </div>
        </form>
    </div>
    <div class="modalContainer del" style="display: none">
        <div class="modalfen">
            <p>Voulez-vous vraiment supprimer ce sinistre ?</p>
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
            // Recherche de sinistres
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
                window.location.href = '?action=delete&id=' + this.id + '#liste_sinistre';
            });
        });
    </script>
</body>
</html>