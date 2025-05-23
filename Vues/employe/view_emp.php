<?php
    session_start();
    include('../../Controller/registerController.php');
    if(isset($_GET['id'])){
        if(!getemployebyid($_GET['id'])){
            header('location:user-not-found.php');
        }
    }else{
        header('location: ../RH/employes.php');
    }
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails de l'employé</title>
    <link rel="stylesheet" href="../../ressources/css/common.css">
    <link rel="stylesheet" href="../../ressources/css/menu.css">
    <link rel="stylesheet" href="../../ressources/css/view_emp.css">
</head>
<body>
    <?php
        include('../RH/components/navbar.php');    
    ?>
    <?php 
        if(getemployebyid($_GET['id'])[0]['status_compte'] == 'unactive'){ ?>
   <div class="container">
        <div class="header">
            <h2 class="title"><a href="../RH/employes.php" class="btn btn-secondary">←</a> Détails de l'employé <i style="font-weight: 100; color: #999">(bloqué)</i></h2>
        </div>
        
        <div class="user-card">
            <div class="user-header">
                <img src="<?= "../../".getemployebyid($_GET['id'])[0]['photo'] ?>" alt="Photo de profil" class="user-avatar">
                <div class="user-title">
                    <h1 style="color: #999"><i class="fas fa-lock"></i> <?= getemployebyid($_GET['id'])[0]['prenom']." ".getemployebyid($_GET['id'])[0]['nom']; ?></h1>
                    <p style="color: #999"><?= 
                        getemployebyid($_GET['id'])[0]['poste'] == 'agts' ? "AGTS - Agent Assurance Site" : "RASD - Responsable Assurance Direction"
                    ?></p>
                    <div class="user-badges">
                        <span class="badge <?= getemployebyid($_GET['id'])[0]['received_email'] != null ? "badge-success" : "badge-danger"?>"><?= getemployebyid($_GET['id'])[0]['received_email'] != null ? "Mail envoyé" : "Mail non envoyé" ?></span>
                        <span class="badge <?= getemployebyid($_GET['id'])[0]['status'] != null &&  getemployebyid($_GET['id'])[0]['status'] == "connected" ? "badge-success" : "badge-danger" ?>">
                            <?=
                                getemployebyid($_GET['id'])[0]['status'] != null &&  getemployebyid($_GET['id'])[0]['status'] == "connected" ? "connecté" : "Déconnecté"
                            ?>
                        </span>
                    </div>
                </div>
            </div>
            <p style="text-align: right; margin: 5px"><i class="badge badge-success">Compte cr&eacute;e le : <b><?= getemployebyid($_GET['id'])[0]['added_date'] ?></b></i></p>
            <p style="text-align: right; margin: 5px"><i class="badge badge-danger">Compte bloqu&eacute; le : <b><?= getemployebyid($_GET['id'])[0]['blocked_date'] ?></b></i></p>
            <div class="user-content">
                <div class="user-details">
                    <!-- Mode Affichage -->
                    <div class="view-mode" style="<?= isset($_POST['save-updates']) ? "display: none" : "display: block" ?>">
                        <div class="detail-group">
                            <span class="detail-label">Nom</span>
                            <div class="detail-value"><?= getemployebyid($_GET['id'])[0]['nom'] ?></div>
                        </div>
                        
                        <div class="detail-group">
                            <span class="detail-label">Prénom</span>
                            <div class="detail-value"><?= getemployebyid($_GET['id'])[0]['prenom'] ?></div>
                        </div>
                        
                        <div class="detail-group">
                            <span class="detail-label">Email</span>
                            <div class="detail-value"><?= getemployebyid($_GET['id'])[0]['email'] ?></div>
                        </div>
                        
                        <div class="detail-group">
                            <span class="detail-label">Code</span>
                            <div class="detail-value"><?= getemployebyid($_GET['id'])[0]['auth_key'] ?></div>
                        </div>
                        
                        <div class="detail-group">
                            <span class="detail-label">Poste</span>
                            <div class="detail-value"><?= strtoupper(getemployebyid($_GET['id'])[0]['poste']) ?></div>
                        </div>
                        
                        <div class="detail-group">
                            <span class="detail-label">Date de naissance</span>
                            <div class="detail-value"><?= getemployebyid($_GET['id'])[0]['bdate'] ?></div>
                        </div>
                        
                        <div class="detail-group">
                            <span class="detail-label">Numéro de téléphone</span>
                            <div class="detail-value"><?= getemployebyid($_GET['id'])[0]['phone'] ?></div>
                        </div>
                        
                        <div class="action-buttons">
                            <button class="btn active" id="edit-btn" style="width: 100%" disabled><i class="fas fa-user-edit"></i>Modifier</button>
                        </div>
                    </div>
                </div>
                
                <form class="user-actions" method="post" id="myForm2">
                    <h3>Actions</h3>
                    
                    <div class="action-section">
                        <h4 class="action-title">Communication</h4>
                        <?= getemployebyid($_GET['id'])[0]['received_email'] == null ? '<button class="btn active" disabled><i class="fas fa-envelope"></i>Envoyer le code de l\'employé</button>' : "<i>Pas de communication pour le moment.</i>"?>
                        
                    </div>
                    
                    <div class="action-section">
                        <h4 class="action-title">Accès</h4>
                        <div class="action-buttons">
                            <label for="activate" class="btn success"><i class="fas fa-user-shield"></i> Activé le compte</label>
                            <input type="submit" name="activate_account" id="activate" style="display: none">
                        </div>
                    </div>
                    
                    <div class="action-section">
                        <h4 class="action-title">Journal d'activité</h4>
                        <div class="detail-value" style="display: flex; flex-direction: column">
                            <?php echo getjournal($_GET['id']); ?>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php   
        }else{ ?>
    <div class="container">
        <div class="header">
            <h2 class="title"><a href="../RH/employes.php" class="btn btn-secondary">←</a> Détails de l'employé</h2>
        </div>
        
        <div class="user-card">
            <div class="user-header">
                <img src="<?= "../../".getemployebyid($_GET['id'])[0]['photo'] ?>" alt="Photo de profil" class="user-avatar">
                <div class="user-title">
                    <h1><?= getemployebyid($_GET['id'])[0]['prenom']." ".getemployebyid($_GET['id'])[0]['nom']; ?></h1>
                    <p><?= 
                        getemployebyid($_GET['id'])[0]['poste'] == 'agts' ? "AGTS - Agent Assurance Site" : "RASD - Responsable Assurance Direction"
                    ?></p>
                    <div class="user-badges">
                        <span class="badge <?= getemployebyid($_GET['id'])[0]['received_email'] != null ? "badge-success" : "badge-danger"?>"><?= getemployebyid($_GET['id'])[0]['received_email'] != null ? "Mail envoyé" : "Mail non envoyé" ?></span>
                        <span class="badge <?= getemployebyid($_GET['id'])[0]['status'] != null &&  getemployebyid($_GET['id'])[0]['status'] == "connected" ? "badge-success" : "badge-danger" ?>">
                            <?=
                                getemployebyid($_GET['id'])[0]['status'] != null &&  getemployebyid($_GET['id'])[0]['status'] == "connected" ? "connecté" : "Déconnecté"
                            ?>
                        </span>
                    </div>
                </div>
            </div>
            <p style="text-align: right"><i class="badge">Compte cr&eacute;e le : <b><?= getemployebyid($_GET['id'])[0]['added_date'] ?></b></i></p>
            <div class="user-content">
                <div class="user-details">
                    <!-- Mode Affichage -->
                    <div class="view-mode" style="<?= isset($_POST['save-updates']) ? "display: none" : "display: block" ?>">
                        <div class="detail-group">
                            <span class="detail-label">Nom</span>
                            <div class="detail-value"><?= getemployebyid($_GET['id'])[0]['nom'] ?></div>
                        </div>
                        
                        <div class="detail-group">
                            <span class="detail-label">Prénom</span>
                            <div class="detail-value"><?= getemployebyid($_GET['id'])[0]['prenom'] ?></div>
                        </div>
                        
                        <div class="detail-group">
                            <span class="detail-label">Email</span>
                            <div class="detail-value"><?= getemployebyid($_GET['id'])[0]['email'] ?></div>
                        </div>
                        
                        <div class="detail-group">
                            <span class="detail-label">Code</span>
                            <div class="detail-value"><?= getemployebyid($_GET['id'])[0]['auth_key'] ?></div>
                        </div>
                        
                        <div class="detail-group">
                            <span class="detail-label">Poste</span>
                            <div class="detail-value"><?= strtoupper(getemployebyid($_GET['id'])[0]['poste']) ?></div>
                        </div>
                        
                        <div class="detail-group">
                            <span class="detail-label">Date de naissance</span>
                            <div class="detail-value"><?= getemployebyid($_GET['id'])[0]['bdate'] ?></div>
                        </div>
                        
                        <div class="detail-group">
                            <span class="detail-label">Numéro de téléphone</span>
                            <div class="detail-value"><?= getemployebyid($_GET['id'])[0]['phone'] ?></div>
                        </div>
                        
                        <div class="action-buttons">
                            <button class="btn active" id="edit-btn" style="width: 100%"><i class="fas fa-user-edit"></i>Modifier</button>
                        </div>
                    </div>
                    
                    <!-- Mode Édition -->
                    <div class="edit-mode" style="<?= isset($_POST['save-updates']) ? "display: block" : "display: none" ?>">
                        <form class="user-form" method="post" id="myForm" enctype="multipart/form-data">
                            <?php isUpdated() ?>
                            <div class="form-input-group">
                                <div class="form-group">
                                    <label class="form-label">Nom</label>
                                    <input type="text" class="form-control" name="nom" value="<?= getemployebyid($_GET['id'])[0]['nom'] ?>">
                                    <?php isNomErrU() ?>
                                </div>
                                
                                <div class="form-group">
                                    <label class="form-label">Prénom</label>
                                    <input type="text" class="form-control" name="prenom" value="<?= getemployebyid($_GET['id'])[0]['prenom'] ?>">                               
                                    <?php isPrenomErrU() ?>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control" name="email" value="<?= getemployebyid($_GET['id'])[0]['email'] ?>">
                                <?php isEmailErrU() ?>
                           
                            </div>
                            
                            <div class="form-input-group">
                                <div class="form-group">
                                    <label class="form-label">Code</label>
                                    <input type="text" class="form-control" value="<?= getemployebyid($_GET['id'])[0]['auth_key'] ?>" readonly disabled>                                
                                </div>
                                
                                <div class="form-group">
                                    <label class="form-label">Poste</label>
                                    <select name="poste" class="form-control">
                                        <option value="">Sélectionnez un poste</option>
                                        <option value="agts" <?= getemployebyid($_GET['id'])[0]['poste'] == 'agts' ? 'selected':'' ?>>AGTS - Agent Assurance Site</option>
                                        <option value="rasd" <?= getemployebyid($_GET['id'])[0]['poste'] == 'rasd' ? 'selected':'' ?>>RASD - Responsable Assurance Direction</option>
                                    </select>
                                    <?php isPosteErrU() ?>
                                </div>
                            </div>
                            
                            <div class="form-input-group">
                                <div class="form-group">
                                    <label class="form-label">Date de naissance</label>
                                    <input type="date" class="form-control" value="<?= getemployebyid($_GET['id'])[0]['bdate'] ?>" name="bdate">
                                    <?php isBdateErrU() ?>
                                </div>
                                
                                <div class="form-group">
                                    <label class="form-label">Numéro de téléphone</label>
                                    <input type="tel" class="form-control" value="<?= getemployebyid($_GET['id'])[0]['phone'] ?>" name="phone">
                                    <?php isPhoneErrU() ?>
                                    </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label">Photo de profil</label>
                                <input type="file" class="form-control" name="photo">
                                <input type="hidden" name="pp" value="<?= getemployebyid($_GET['id'])[0]['photo'] ?>">
                                <?php isPhotoErrU() ?>
                            </div>
                            
                            <div class="action-buttons">
                                <button type="submit" class="btn active" style="flex: 1" name="save-updates"><i class="fas fa-save"></i>Enregistrer</button>
                                <button type="button" class="btn danger" id="cancel-btn"><i class="fas fa-ban"></i>Annuler</button>
                            </div>
                        </form>
                    </div>
                </div>
                
                <form class="user-actions" method="post" id="myForm2">
                    <h3>Actions</h3>
                    
                    <div class="action-section">
                        <h4 class="action-title">Communication</h4>
                        <?= getemployebyid($_GET['id'])[0]['received_email'] == null ? '<input type="submit" class="btn active" name="resendmail" value="Envoyer le code de l\'employé par email">' : "<i>Pas de communication pour le moment.</i>"?>
                        
                    </div>
                    
                    <div class="action-section">
                        <h4 class="action-title">Accès</h4>
                        <div class="action-buttons">
                            <label for="desactivate" class="btn danger"><i class="fas fa-user-slash"></i>Désactiver le compte</label>
                            <input type="submit" name="desactivate_account" id="desactivate" style="display: none">
                        </div>
                    </div>
                    
                    <div class="action-section">
                        <h4 class="action-title">Journal d'activité</h4>
                        <div class="detail-value" id="journal" style="margin-top: 10px; height: 130px; overflow-y: auto;">
                            <?php echo getjournal($_GET['id']); ?>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="view_emp.js"></script>     
    <script>
        document.getElementById('myForm').addEventListener('submit', function() {
            document.getElementById('loaderc').style.display = 'flex';
            document.getElementById('submitbtn').disabled = true;
        });
    </script>    
    <?php
        }
    ?>
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
    <script>
        document.getElementById('myForm2').addEventListener('submit', function() {
            document.getElementById('loaderc').style.display = 'flex';
            document.getElementById('submitbtn').disabled = true;
        });
    </script>
    <script src="../../ressources/js/all.js"></script>
</body>
</html>