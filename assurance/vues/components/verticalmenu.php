<div class="vertical-menu">
    <h1>Assurance</h1>
    <h4>Gestion des contrats</h4>
    <ul class="v-menu-itemcontainer">
        <li class="v-menu-item">
            <span class="v-menu-item-title">
                <i class="fas fa-file-contract"></i><span>Contrats</span><i class="fas fa-angle-down"></i>
            </span>
            <ul class="v-menu-item-menu" id="vm-2">
                <!-- <li><a href="ajout_contrat.php">Nouveau contrat</a></li> -->
                <li><a href="liste_contrat.php">Gérer les contrats</a></li>
            </ul>
        </li>  
<!--            
        <li class="v-menu-item">
            <span class="v-menu-item-title">
                <i class="fas fa-pen-square"></i><span>Assureurs et contacts</span><i class="fas fa-angle-down"></i>
            </span>
            <ul class="v-menu-item-menu" id="vm-3">
                <li><a href="http://">Liste des contrats</a></li>
                <li><a href="http://">Ajouter Une convention</a></li>
            </ul>
        </li>                      -->
    </ul>
    <h4>Demande D'assurance</h4>
    <ul class="v-menu-itemcontainer">
        <li class="v-menu-item">
            <span class="v-menu-item-title">
                <i class="fas fa-home"></i><span>Gestion des demandes</span><i class="fas fa-angle-down"></i>
            </span>
            <ul class="v-menu-item-menu" id="vm-4">
                <li><a href="ajout_demande.php">Ajouter Une demande</a></li>
                <li><a href="index.php">Toutes les demandes</a></li>
            </ul>
        </li>                  
    </ul>
    <h4>Gestion des Sinistres</h4>
    <ul class="v-menu-itemcontainer">
        <li class="v-menu-item">
            <span class="v-menu-item-title">
                <i class="fas fa-biohazard"></i><span>Sinistres</span><i class="fas fa-angle-down"></i>
            </span>
            <ul class="v-menu-item-menu" id="vm-5">
                <li><a href="ajout_sinistre.php">Nouveau sinistres</a></li>
                <li><a href="liste_sinistre.php">Liste sinistre</a></li>
                <!-- <li><a href="http://">Modifier un sinistre</a></li> -->
            </ul>
        </li>    
        <li class="v-menu-item">
            <span class="v-menu-item-title">
                <i class="fas fa-folder-open"></i><span>Quittance</span><i class="fas fa-angle-down"></i>
            </span>
            <ul class="v-menu-item-menu" id="vm-6">
                <li><a href="ajout_quittance.php">Ajout & liste Quittance </a></li>
                <!-- <li><a href="list_quittance.php">Liste des Quittance</a></li>
                <li><a href="http://">Ajouter Une convention</a></li> -->
            </ul>
        </li>                
    </ul>
    <h4>Règlement des dossiers</h4> 
    <ul class="v-menu-itemcontainer">
        <li class="v-menu-item">
            <span class="v-menu-item-title">
                <i class="fas fa-book"></i><span>Archivage des Dossiers </span><i class="fas fa-angle-down"></i>
            </span>
            <ul class="v-menu-item-menu" id="vm-7">
                <li><a href="achivage.php">Archive des Quittances</a></li>
            </ul>
        </li>           
    </ul>    
    <?php
    if (isset($_SESSION['user_poste']) && $_SESSION['user_poste'] == 'agts') { ?>
    
    <?php
    }
    if(isset($_SESSION['user_poste']) && $_SESSION['user_poste'] == 'rasd'){ ?>
        <h4>Tableau de bord </h4>
        <ul class="v-menu-itemcontainer">
            <li class="v-menu-item">
                <span class="v-menu-item-title">
                    <i class="fas fa-home"></i><span>Option</span><i class="fas fa-angle-down"></i>
                </span>
                <ul class="v-menu-item-menu" id="vm-8">
                    <li><a href="http://">Ajouter Un Contrat</a></li>
                    <li><a href="http://">Liste des contrats</a></li>
                    <li><a href="http://">Ajouter Une convention</a></li>
                </ul>
            </li>                  
        </ul>
    <?php
    }
    ?>
</div>