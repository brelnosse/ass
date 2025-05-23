<div class="navbar">
    <span class="item maintitle">Assurance</span>
    <div class="menu-toggle" id="menuToggle">
        <span></span>
        <span></span>
        <span></span>
    </div>
    <div class="item-container" id="mobileMenu">
    <?php 
        switch($_SERVER['REQUEST_URI']){
            case '/ass/Vues/RH/home.php': ?>
                <a href="/ass/Vues/RH/home.php" class="btn active"><i class="fas fa-home"></i> Accueil</a>
                <a href="/ass/Vues/RH/employes.php" class="btn"><i class="fas fa-users"></i> Employ&eacute;s</a>
                <a href="/ass/Vues/RH/register.php" class="btn"><i class="fas fa-user-plus"></i> Nouvel employ&eacute;</a>
            <?php
            break;
            case '/ass/Vues/RH/register.php': ?>
                <a href="/ass/Vues/RH/home.php" class="btn"><i class="fas fa-home"></i> Accueil</a>
                <a href="/ass/Vues/RH/employes.php" class="btn"><i class="fas fa-users"></i> Employ&eacute;s</a>
                <a href="/ass/Vues/RH/register.php" class="btn active"><i class="fas fa-user-plus"></i> Nouvel employ&eacute;</a>
            <?php
            break;
            case '/ass/Vues/RH/employes.php': ?>
                <a href="/ass/Vues/RH/home.php" class="btn"><i class="fas fa-home"></i> Accueil</a>
                <a href="/ass/Vues/RH/employes.php" class="btn active"><i class="fas fa-users"></i> Employ&eacute;s</a>
                <a href="/ass/Vues/RH/register.php" class="btn"><i class="fas fa-user-plus"></i> Nouvel employ&eacute;</a>
            <?php
            break;
            case '/ass/Vues/employe/view_emp.php': ?>
                <a href="/ass/Vues/RH/home.php" class="btn"><i class="fas fa-home"></i> Accueil</a>
                <a href="/ass/Vues/RH/employes.php" class="btn active"><i class="fas fa-users"></i> Employ&eacute;s</a>
                <a href="/ass/Vues/RH/register.php" class="btn"><i class="fas fa-user-plus"></i> Nouvel employ&eacute;</a>
            <?php
            break;
            default:?>
            <a href="/ass/Vues/RH/home.php" class="btn"><i class="fas fa-home"></i> Accueil</a>
            <a href="/ass/Vues/RH/employes.php" class="btn "><i class="fas fa-users"></i> Employ&eacute;s</a>
            <a href="/ass/Vues/RH/register.php" class="btn"><i class="fas fa-user-plus"></i> Nouvel employ&eacute;</a>
            <?php

        }
    ?>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Sélectionner les éléments du menu
    const menuToggle = document.getElementById('menuToggle');
    const mobileMenu = document.getElementById('mobileMenu');
    
    // Ajouter un écouteur d'événement pour le clic sur le bouton hamburger
    if (menuToggle) {
        menuToggle.addEventListener('click', function() {
            // Basculer la classe 'active' pour afficher/masquer le menu
            mobileMenu.classList.toggle('active');
            menuToggle.classList.toggle('active');
        });
    }
    
    // Fermer le menu mobile lorsqu'un lien est cliqué
    const menuLinks = mobileMenu.querySelectorAll('.btn');
    menuLinks.forEach(link => {
        link.addEventListener('click', function() {
            // Vérifier si nous sommes en mode mobile (largeur d'écran <= 768px)
            if (window.innerWidth <= 768) {
                mobileMenu.classList.remove('active');
                menuToggle.classList.remove('active');
            }
        });
    });
    
    // Fermer le menu mobile lorsque la fenêtre est redimensionnée au-delà de 768px
    window.addEventListener('resize', function() {
        if (window.innerWidth > 768) {
            mobileMenu.classList.remove('active');
            menuToggle.classList.remove('active');
        }
    });
});
</script>