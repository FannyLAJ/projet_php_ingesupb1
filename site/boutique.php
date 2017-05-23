<?php
require_once("inc/init.inc.php");
//--------------------------------- TRAITEMENTS PHP ---------------------------------//
//--- AFFICHAGE DES CATEGORIES ---//






$categories_des_produits = executeRequete("SELECT DISTINCT categorie FROM produit");
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
	$donnees = executeRequete("SELECT id_produit,reference,titre,photo,prix FROM produit WHERE categorie='$_GET[categorie]'");	
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

<?php require_once("inc/haut.inc.php"); ?>
<form method="post" action="">
    <input type="text" name="keywords">
    <input type="submit" value="Rechercher">


<select name="recherche" > 
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



</form>


<?php echo $contenu;
$resultatA ='';







if(!empty($_POST["keywords"])) {
	

	$keywords = $_POST['keywords'];
	

if (isset($_POST['keywords']))
{
$choix = $_POST['recherche'];





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

echo $contentA;

}
}
}
require_once("inc/bas.inc.php");





 ?>



