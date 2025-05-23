<?php
    session_start();
    include('../controller/contratController.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assurance - Liste des contrats</title>
    <link rel="stylesheet" href="ressources/css/menu.css">
    <link rel="stylesheet" href="ressources/css/ajout_contrat.css">
    <link rel="stylesheet" href="ressources/css/common.css">
    <link rel="stylesheet" href="ressources/css/liste_contrat.css">
</head>
<body>
    <?php
        include('components/navbar.php');
        include('components/verticalmenu.php');
    ?>
    <div class="container">
        <h1 class="root"><button onclick="history.back()" class="btn btn-primary"><i class="fas fa-arrow-left" style="margin-right: 0"></i></button> Contrats / Liste des contrats</h1>
        <form method="post">
            <h2>Tous les contrats</h2>
            <?php
                if (isset($_GET['action']) && isset($_GET['id'])) {
                    switch ($_GET['action']) {
                        case 'delete':
                            if (removeContrat($_GET['id'])) { ?>
                                <div class="info alert">
                                    <i class="fas fa-check-circle"></i>
                                    Contrat supprimé avec succès.
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
                        Nouveau contrat créé avec succès.
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
            <!-- <a href="ajout_contrat.php" class="link-btn">
                <i class="fas fa-plus"></i>
                Nouveau contrat
            </a> -->
            <div class="table-container">
                <table style="width: 100%" class="liste_contrat" id="liste_contrat">
                    <thead>
                        <tr>
                            <th>Référence</th>
                            <th>Libellé</th>
                            <th>Assureur</th>
                            <th>Montant</th>
                            <th>Date signature</th>
                            <th>Situation</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                        $start = 0;
                        if (isset($_GET['page'])) {
                            $start = (int)htmlspecialchars($_GET['page']);
                        }
                        $arr = getContracts($start);
                        if (count($arr) === 0) {
                            echo "<tr><td colspan='7' style='text-align: center; padding: 30px;'>Aucun contrat pour le moment.</td></tr>";
                        } else {
                            foreach ($arr as $item) {
                                echo "<tr class='table-line' id='{$item['ref']}'>
                                        <td><span>{$item['ref']}</span></td>
                                        <td><span>{$item['libelle']}</span></td>
                                        <td><span>{$item['ass']}</span></td>
                                        <td><span>{$item['montant']} {$item['devise']}</span></td>
                                        <td><span>{$item['bdate']}</span></td>
                                        <td><span>" . ($item['edate'] <= date('Y-m-d') ? '<i style="color: red;font-weight: 100">Expiré</i>' : 'En cours') . "</span></td>
                                        <td class='actionBtn'>
                                            ".($item['edate'] <= date('Y-m-d') ? '<a href=\'ajout_sinistre.php?id='.$item['id'].'\' class="editItem" title="Nouveau sinistre"><i class="fas fa-biohazard"></i></a><a href=\'modifier_contrat.php?id='.$item['id'].'\' class="editItem" title="Modifier le contrat"><i class="fas fa-pencil-alt"></i></a><button type="button" class="removeItem" id='.$item['id'].'\' title="Supprimer le contrat"><i class="fas fa-trash-alt"></i></button>' : '<i>Non disponible (contrat en cours)</i>')."
                                        </td>
                                    </tr>";
                            }
                            
                        }                    
                    ?>
                    <tr class="hide">
                        <td colspan='7' style='text-align: center; padding: 30px;'>Aucun résultat trouvé.</td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <hr>
            <div class="footer">
                <?php
                    $nbrePage = ceil(getContractsLength() / 10);
                    if ($nbrePage > 1) {
                        for ($i = 0; $i < $nbrePage; $i++) {
                            $activeClass = (isset($_GET['page']) && intval($_GET['page']) / 10 == $i) ? 'active' : '';
                            echo "<a href='?page=" . ($i * 10) . "#liste_contrat' class='$activeClass'>" . ($i + 1) . "</a>";
                        }
                    }
                ?>
            </div>
        </form>
    </div>
    <div class="modalContainer del" style="display: none">
        <div class="modalfen">
            <p>Voulez-vous vraiment supprimer ce contrat ?</p>
            <div class="commandButton">
                <span class="active red deleteCont" id="">Supprimer</span>
                <span class="modalfen ccl">Annuler</span>
            </div>
        </div>
    </div>
    <script src="ressources/js/all.js"></script>
    <script src="ressources/js/menu.js"></script>
    <script src="ressources/js/liste_contrat.js"></script>
</body>
</html>