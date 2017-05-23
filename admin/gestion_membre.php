
<?php require_once("../inc/init.inc.php");?>
<?php require_once("../inc/haut.inc.php");
//Traitement PHP
if (!internauteEstConnecteEtEstAdmin()) {
    header("location: connexion.php");
}

if (isset($_GET['action']) && $_GET['action']=='suppression') {
    $result = executeRequete("DELETE FROM membre WHERE id_membre=$_GET[id_membre];");
    $result = executeRequete("ALTER TABLE membre AUTO_INCREMENT=$_GET[id_membre];");
    header("location: gestion_membre.php?action=affichage");
}

if (!empty($_POST)) {

    foreach ($_POST as $indice => $value) {
        $_POST[$indice] = htmlentities(addslashes($value));
    }

    if ($_POST['ajouter'] == 'Ajouter') {
        $result = executeRequete("INSERT INTO membre (pseudo, mdp, email, nom, prenom, civilite, ville, code_postal, adresse) VALUES('$_POST[pseudo]',"
            ."'$_POST[password]', '$_POST[email]', '$_POST[lastname]', '$_POST[firstname]', '$_POST[gender]', '$_POST[city]', '$_POST[zip]', '$_POST[address]');");
        if ($result) echo "Niquel !";
        else echo "<div style='background-color: red'>CA MARCHE PAAAS</div>";
    }
    else if ($_POST['ajouter'] == "Administrateur") {
        $result = executeRequete("INSERT INTO membre (pseudo, mdp, email, nom, prenom, civilite, ville, code_postal, adresse, statut) VALUES('$_POST[pseudo]',"
            ."'$_POST[password]', '$_POST[email]', '$_POST[lastname]', '$_POST[firstname]', '$_POST[gender]', '$_POST[city]', '$_POST[zip]', '$_POST[address]', 1);");
        if ($result) echo "Niquel !";
        else echo "<div style='background-color: red'>CA MARCHE PAAAS</div>";
    }
}

//Liens vers les produits
$contenu .= "<a href='gestion_membre.php?action=affichage'>Afficher la liste des membres</a><br><br>";
$contenu .= "<a href='gestion_membre.php?action=ajout'>Ajouter manuellement un membre</a>";

//Traitement
if (isset($_GET['action']) && $_GET['action']=='affichage') {
    $result = executeRequete("SELECT * FROM membre;");
    $contenu.= "Nombre de membres inscrits : ".$result->num_rows;
    $contenu.= "<table border=1px><tr>";
    while ($colonne = $result->fetch_field()) {
        $contenu.= "<th>".$colonne->name."</th>";
    }
    $contenu.= "<th>Modification</th>";
    $contenu.="<th>Suppression</th></tr>";
    while ($ligne = $result->fetch_assoc()) {
        $contenu.="<tr>";
        foreach ($ligne as $indice => $value) {
            $contenu.="<td>".$value."</td>";
        }
        $contenu.="<td><a href='gestion_membre.php?action=modification&id_membre=".$ligne['id_membre']."'><img src='../inc/img/edit.png'></a></td>";
        $contenu.="<td><a href='gestion_membre.php?action=suppression&id_membre=".$ligne['id_membre']."'><img src='../inc/img/delete.png'></a></td>";
        $contenu.="</tr>";
    }
    $contenu.="</table>";
}

echo $contenu;

if (isset($_GET)) {
    if (isset($_GET['action']) != false) {
        if ($_GET['action'] == 'ajout' || $_GET['action'] == 'modification') {
            echo "<form method='post' action='".$_SERVER['PHP_SELF']."'>
        <label for='pseudo'>Pseudo : </label>
            <input type='text' id='pseudo' name='pseudo' maxlength='20' placeholder='Pseudo'><br>
        <label for='password'>Mot de passe : </label>
            <input type='password' id='password' name='password'><br>
        <label for='email'>Adresse mail : </label>
            <input type='email' id='email' name='email' placeholder='example@mail.com'><br><br>

        <label for='gender'>Civilité : </label><br>
            <input type='radio' id='gender' name='gender' value='m'>Mr.<br>
            <input type='radio' id='gender' name='gender' value='f'>Mme.<br>
        <label for='firstname'>Prénom : </label><br>
            <input type='text' id='firstname' name='firstname' maxlength='20' placeholder='Prénom'><br>
        <label for='lastname'>Nom de famille : </label>
            <input type='text' id='lastname' name='lastname' maxlength='20' placeholder='Nom de famille'><br><br>
    
        <label for='address'>Adresse : </label>
            <input type='text' id='address' name='address'><br>
        <label for='zip'>Code postal : </label>
            <input type='text' id='zip' name='zip' maxlength='5'><br>
        <label for='city'>Ville : </label>
            <input type='text' id='city' name='city'><br><br>

        <input type='submit' name='ajouter' value='Ajouter'>&nbsp;<input type='submit' name='ajouter' value='Administrateur'>
    </form>";
        }
    }
}
?>

<?php require_once("../inc/bas.inc.php") ?>
