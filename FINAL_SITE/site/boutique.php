<?php
require_once("../inc/init.inc.php");
//--------------------------------- TRAITEMENTS PHP ---------------------------------//
//--- AFFICHAGE DES CATEGORIES ---//






$categories_des_produits = executeQuery("SELECT DISTINCT categorie FROM produit");
$contenu .= '<div class="boutique-gauche">';
$contenu .= "<ul>";
while($cat = $categories_des_produits->fetch_assoc())
{
	$contenu .= "<li><a href='?categorie="	. $cat['categorie'] . "'>" . $cat['categorie'] . "</a></li>";
}
$contenu .= "</ul>";
$contenu .= "</div>";
//--- AFFICHAGE DES PRODUITS ---//
$contenu .= '<div class="boutique-droite">';
if(isset($_GET['categorie']))
{
	$donnees = executeQuery("SELECT id_produit,reference,titre,photo,prix FROM produit WHERE categorie='$_GET[categorie]'");
	while($produit = $donnees->fetch_assoc())
	{
		$contenu .= '<div class="boutique-produit">';
		$contenu .= "<h3>$produit[titre]</h3>";
		$contenu .= "<a href=\"fiche_produit.php?id_produit=$produit[id_produit]\"><img src=\"$produit[photo]\" width=\"100\" height=\"100\" /></a>";
		$contenu .= "<p>$produit[prix] €</p>";
		$contenu .= '<a href="fiche_produit.php?id_produit=' . $produit['id_produit'] . '">Voir la fiche</a>';
		$contenu .= '</div>';
	}
}
$contenu .= '</div>';
?>

<?php require_once("../inc/haut.inc.php"); ?>
<!-- Création de la recherche par mot clès -->


<form method="post" action="">
<input type="text" name="keywords" />
<input type="submit" value="Rechercher" />


<select name="recherche">
	<option name="base" value="0">
		--Choisir--
	</option>
	<option name="recherche" value="1">
		Catégorie
	</option>
	<option name="recherche" value="2">
		Couleur
	</option>
	<option name="recherche" value="3">
	Taille
	</option>
</select>

<!-- création de la balise "input" et "select" visible en front par l'utilisateur -->

</form>


<?php echo $contenu;
$resultatA ='';

if(!empty($_POST["keywords"])) {
	$keywords = $_POST['keywords'];

if (isset($_POST['keywords']))
{
$choix = $_POST['recherche'];



/* Le morceau de code ci dessus a été crée pour éviter les érreurs*/

if ($choix==0)
{

 return 0;
}
if ($choix>=1) {


if ($choix==1)
{
$resultatA = executeRequete("SELECT categorie,titre, couleur, taille, photo, prix FROM produit WHERE categorie LIKE '%".$keywords."%'");

}
elseif ($choix==2)
{
$resultatA = executeRequete("SELECT categorie,titre, couleur, taille, photo, prix FROM produit WHERE couleur LIKE '%".$keywords."%'");
}
elseif ($choix==3)
{
$resultatA = executeRequete("SELECT categorie,titre, couleur, taille, photo, prix FROM produit WHERE taille LIKE '%".$keywords."%'");
}

/* En fonction de la recherche demandé, une requete différente sera éffectué */

$contentA ='';


$contentA .= '<table border="1" cellpadding="5"><tr>';
		while($colonneA = $resultatA->fetch_field())
		{
			$contentA .= '<th>' . $colonneA->name . '</th>';
		}
		$contentA .= '</tr>';
		while ($ligneA = $resultatA->fetch_assoc())
	{
		$contentA .= '<tr>';
		foreach ($ligneA as $indiceP => $information) {
			if($indiceP == "photo")
			{
				$contentA .= '<td><img src="' . $information . '" width="70" height="70" /></td>';
			}
		else
			{
				$contentA .= '<td>' . $information . '</td>';
			}
		}

		$contentA .= '</tr>';
	}

		$contentA .= '</table><br /><hr /><br />';

/*Création du tableau de fin de recherche*/
echo $contentA;

}
}
}
require_once("../inc/bas.inc.php");





 ?>
