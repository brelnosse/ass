<?php
function getQuittances()
{
    global $bdd;
    $stmt = $bdd->query("SELECT * FROM archive ORDER BY date_ajout DESC");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

?>