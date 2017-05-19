<?php require_once('../inc/init.inc.php');

// Traitement PHP
require_once('../inc/haut.inc.php');


if (!isConnected()) {
    header("Location : connexion.php");
}
else {
    $content .= "<p class='conteneur'>Bonjour <strong>".$_SESSION['membre']['pseudo']."</strong> !"
        ."<br><br>Adresse : ".$_SESSION['membre']['adresse']."<br>".$_SESSION['membre']['code_postal']."&nbsp;".$_SESSION['membre']['ville']
        ."<br><br>Mail : ".$_SESSION['membre']['email']."</p>";
    echo $content;
}

require_once('../inc/bas.inc.php');

