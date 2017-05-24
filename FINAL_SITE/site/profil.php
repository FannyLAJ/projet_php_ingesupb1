<?php require_once('../inc/init.inc.php');

// Traitement PHP
require_once('../inc/haut.inc.php');

if (!isConnected()) {
    header("Location : connexion.php");
}
else {
    $contenu .= "<p class='conteneur'>Bonjour <strong>".$_SESSION['membre']['pseudo']."</strong> !"
        ."<br><br>Adresse : ".$_SESSION['membre']['adresse']."<br>".$_SESSION['membre']['code_postal']."&nbsp;".$_SESSION['membre']['ville']
        ."<br><br>Mail : ".$_SESSION['membre']['email']."</p>"
        ."<a href='profil.php?action=modification&id=".$_SESSION['membre']['id_membre']."'>Modifier vos informations</a>&nbsp;"
        ."<a href='profil.php?action=avatar&id=".$_SESSION['membre']['id_membre']."'>Ajouter un avatar</a>";

    echo $contenu;
}

if (isset($_POST['inscription'])) {
    $result = executeQuery("UPDATE membre SET pseudo='$_POST[pseudo]', nom='$_POST[lastname]', prenom='$_POST[firstname]', email='$_POST[email]', ville='$_POST[city]',
      adresse='$_POST[address]', code_postal=$_POST[zip] WHERE id_membre=" . $_SESSION['membre']['id_membre']);

    if ($result != false) {
        echo "Vos modifications ont bien été enregistrées, elles prendront effet à votre prochaine connexion !<br>";
        $result = executeQuery("SELECT * FROM membre WHERE id_membre=" . $_SESSION['membre']['id_membre']);

        $contenu="<table border='solid 1px grey'>";
        while ($colonne = $result->fetch_field()) {
            if ($colonne->name != 'mdp' && $colonne->name != 'statut') $contenu .= "<th>" . $colonne->name . "</th>";
        }
        while ($ligne = $result->fetch_assoc()) {
            $contenu .= "<tr>";
            foreach ($ligne as $indice => $value) {
                if ($indice != 'mdp' && $indice != 'statut') $contenu .= "<td>" . $value . "</td>";
            }
            $contenu.="</tr>";
        }
        $contenu.="</table>";
        echo $contenu;
    }
}

if (isset($_POST['avatar'])) {
    $destination = 'C:/wamp64/www'.ROOT_SITE.'inc/img/galerie';

    if (is_dir($destination) == false) mkdir($destination, 077);

    $filename = $_SESSION['membre']['id_membre']."_".$_FILES['avatar']['name'];
    $imgbdd = '../inc/img/galerie/'.$filename;
    $img = $destination."/".$_FILES['avatar']['name'];

    $size = getimagesize($_FILES['avatar']['tmp_name']);

    if ($size[0] > 300 || $size[1] > 300) echo "<div class='erreur'>Erreur : le fichier est trop grand !</div>";

    else {
        $result = move_uploaded_file($_FILES['avatar']['tmp_name'], $imgbdd);
        if ($result == FALSE) echo "<div class='erreur'>Une erreur est survenue lors du transfert de votre fichier !</div>";

        else {
            $result=executeQuery("UPDATE membre SET avatar='$imgbdd' WHERE id_membre=".$_SESSION['membre']['id_membre']);
            echo "<br><img src='".$imgbdd."'>";
        }
    }
}

if (isset($_GET)) {
    if (isset($_GET['action']) && $_GET['action'] == 'modification') {
        echo "<form method='post' action='".$_SERVER['PHP_SELF']."'>
    <label for='pseudo'>Pseudo : </label>
        <input type='text' id='pseudo' name='pseudo' maxlength='20' value='".$_SESSION['membre']['pseudo']."'><br>
    <label for='email'>Adresse mail : </label>
        <input type='email' id='email' name='email' value='".$_SESSION['membre']['email']."'><br><br>

    <label for='gender'>Civilité : </label><br>
        <input type='radio' id='gender' name='gender' value='m'>Mr.<br>
        <input type='radio' id='gender' name='gender' value='f'>Mme.<br>
    <label for='firstname'>Prénom : </label>
        <input type='text' id=firstname' name='firstname' maxlength='20' value='".$_SESSION['membre']['prenom']."'><br>
    <label for='lastname'>Nom de famille : </label>
        <input type='text' id='lastname' name='lastname' maxlength='20' value='".$_SESSION['membre']['nom']."'><br><br>

    <label for='address'>Adresse : </label>
        <input type='text' id='address' name='address' value='".$_SESSION['membre']['adresse']."'><br>
    <label for='zip'>Code postal : </label>
        <input type='text' id='zip' name='zip' maxlength='5' value='".$_SESSION['membre']['code_postal']."'><br>
    <label for='city'>Ville : </label>
        <input type='text' id='city' name='city' value='".$_SESSION['membre']['ville']."'><br><br>

    <input type='submit' name='inscription' value='Modifier'>

</form>";
    }

    else if (isset($_GET['action']) && $_GET['action'] == 'avatar') {
        echo "<form method='post' enctype='multipart/form-data' action='".$_SERVER['PHP_SELF']."'>
            <label for='photo'>Photo : </label>
            <input type='file' name='avatar' id='avatar'><br>
            <input type='submit' name='avatar' value='Envoyer'><br>
            <span>L'image ne doit pas dépasser 200px de hauteur et 200px de largeur !</span>";
    }
}

//Création du formulaire
$supr = '<div>';
$supr .= '<form action="#" method="post">';
$supr .= '<input type="submit" name="supprimer_compte" value="Supprimer votre compte"/>';
$supr .= '</div>';

if(isset($_POST['supprimer_compte'])){												//Si le formulaire est validé
	$mail = $_SESSION['membre']['email'];								//On sauvegarde le mail de l'utilisateur
	$resultat = executeQuery("DELETE FROM membre WHERE email = '$mail'");				//On supprime ce compte de la BDD membre
	session_destroy();										//On supprime la session
	header('Location:../index.php');   								//On redirige vers index.php
}

//On affiche le formulaire
echo $supr;

require_once('../inc/bas.inc.php');
