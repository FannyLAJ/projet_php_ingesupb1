
<?php require_once("../inc/init.inc.php");?>
<?php require_once("../inc/haut.inc.php");
//Traitement PHP
if (!isAdmin()) {
    header("location: connexion.php");
}

if (isset($_GET['action']) && $_GET['action']=='suppression') {
    $result = executeQuery("DELETE FROM produit WHERE id_produit=$_GET[id_produit];");
}
if (!empty($_POST)) {
    $photo_link = "";
    if (!empty($_FILES['photo']['name'])) {
        $nom_photo = $_POST['reference']."_".$_FILES['photo']['name'];
        $photo_bdd = ROOT_SITE."photo/".$nom_photo;
        $photo_dossier = $_SERVER['DOCUMENT_ROOT'].ROOT_SITE."photo/".$nom_photo;
        copy($_FILES['photo']['tmp_name'], $photo_dossier);
    }
    foreach ($_POST as $indice => $value) {
        $_POST[$indice] = htmlentities(addslashes($value));
    }
    executeQuery("INSERT INTO produit (reference, categorie, titre, description, couleur, taille, sexe, photo, prix, stock) VALUES('$_POST[reference]',"
    ."'$_POST[categorie]', '$_POST[titre]', '$_POST[description]', '$_POST[couleur]', '$_POST[taille]', '$_POST[sexe]', '$photo_bdd', $_POST[prix], $_POST[stock]);");

    $content.= "Insertion successful.";
}

//Liens vers les produits
$content .= "<a href='gestion_boutique.php?action=affichage'>Afficher les produits</a>";
$content .= "<a href='gestion_boutique.php?action=ajout'>Ajouter un produit</a>";

//Traitement
if (isset($_GET['action']) && $_GET['action']=='affichage') {
    $result = executeQuery("SELECT * FROM produit;");
    $content.= "Nombre de produits : ".$result->num_rows;
    $content.= "<table border=1px><tr>";
    while ($colonne = $result->fetch_field()) {
        $content.= "<th>".$colonne->name."</th>";
    }
    $content.= "<th>Modification</th>";
    $content.="<th>Suppression</th></tr>";
    while ($ligne = $result->fetch_assoc()) {
        $content.="<tr>";
        foreach ($ligne as $indice => $value) {
            if ($indice == 'photo') {
                $content.="<td><img src='".$value."'></td>";
            }
            else {
                $content.="<td>".$value."</td>";
            }
        }
        $content.="<td><a href='gestion_boutique.php?action=modification&id_produit=".$ligne['id_produit']."'><img src='../inc/img/edit.png'></a></td>";
        $content.="<td><a href='gestion_boutique.php?action=suppression&id_produit=".$ligne['id_produit']."'><img src='../inc/img/delete.png'></a></td>";
        $content.="</tr>";
    }
    $content.="</table>";
}

echo $content;

if (isset($_GET['action']) && $_GET['action'] == 'ajout') {
    echo "<form method='post' enctype='multipart/form-data' action='".$_SERVER['PHP_SELF']."'>
    <label for='reference'>Référence : </label>
    <input type='text' id='reference' name='reference'><br>

    <label for='categorie'>Catégorie : </label>
    <input type='text' id='categorie' name='categorie'><br>

    <label for='titre'>Titre : </label>
    <input type='text' id='titre' name='titre'><br>

    <label for='description'>Description : </label>
    <input type='text' id='description' name='description'><br>

    <label for='couleur'>Couleur : </label>
    <input type='text' id='couleur' name='couleur'><br>

    <label for='taille'>Taille : </label>
    <select id='taille' name='taille'>
        <option value='XS'>XS</option>
        <option value='S'>S</option>
        <option value='M'>M</option>
        <option value='L'>L</option>
        <option value='XL'>XL</option>
    </select><br>

    <label for='sexe'>Public : </label><br>
    <input type='radio' id='sexe' name='sexe' value='H'>H<br>
    <input type='radio' id='sexe' name='sexe' value='F'>F<br>
    <input type='radio' id='sexe' name='sexe' value='H/F'>H/F<br>

    <label for='photo'>Photo : </label>
    <input type='file' name='photo' id='photo'><br>

    <label for='prix'>Prix : </label>
    <input type='text' id='prix' name='prix'><br>

    <label for='stock'>Stock</label>
    <input type='text' id='stock' name='stock'><br>

    <input type='submit' value='Envoyer'>
</form>";
}
?>

<?php require_once("../inc/bas.inc.php") ?>
