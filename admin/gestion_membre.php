
<?php require_once("../inc/init.inc.php");?>
<?php require_once("../inc/haut.inc.php");
//Traitement PHP
if (!isAdmin()) {
    header("location: connexion.php");
}

if (isset($_GET['action']) && $_GET['action']=='suppression') {
    $result = executeQuery("DELETE FROM membre WHERE id_membre=$_GET[id_membre];");
}
if (!empty($_POST)) {
    foreach ($_POST as $indice => $value) {
        $_POST[$indice] = htmlentities(addslashes($value));
    }
    executeQuery("INSERT INTO membre (pseudo, mdp, email, nom, prenom, civilite, ville, code_postal, adresse) VALUES('$_POST[pseudo]',"
        ."'$_POST[password]', '$_POST[email]', '$_POST[lastname]', '$_POST[firstname]', '$_POST[gender]', '$_POST[city]', '$_POST[zip]', '$_POST[address]');");

    $content.= "Insertion successful.";
}

//Liens vers les produits
$content .= "<a href='gestion_membre.php?action=affichage'>Afficher la liste des membres</a><br><br>";
$content .= "<a href='gestion_membre.php?action=ajout'>Ajouter manuellement un membre</a>";

//Traitement
if (isset($_GET['action']) && $_GET['action']=='affichage') {
    $result = executeQuery("SELECT * FROM membre;");
    $content.= "Nombre de membres inscrits : ".$result->num_rows;
    $content.= "<table border=1px><tr>";
    while ($colonne = $result->fetch_field()) {
        $content.= "<th>".$colonne->name."</th>";
    }
    $content.= "<th>Modification</th>";
    $content.="<th>Suppression</th></tr>";
    while ($ligne = $result->fetch_assoc()) {
        $content.="<tr>";
        foreach ($ligne as $indice => $value) {
            $content.="<td>".$value."</td>";
        }
        $content.="<td><a href='gestion_membre.php?action=modification&id_membre=".$ligne['id_membre']."'><img src='../inc/img/edit.png'></a></td>";
        $content.="<td><a href='gestion_membre.php?action=suppression&id_membre=".$ligne['id_membre']."'><img src='../inc/img/delete.png'></a></td>";
        $content.="</tr>";
    }
    $content.="</table>";
}

echo $content;

if (isset($_GET['action']) && $_GET['action'] == 'ajout' || $_GET['action'] == 'modification') {
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

    <input type='submit' name='incription' value='S'inscrire'>

</form>";
}
?>

<?php require_once("../inc/bas.inc.php") ?>
