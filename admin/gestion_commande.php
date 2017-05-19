<?php 

require_once("../inc/init.inc.php");
require_once("../inc/haut.inc.php");

if(!internauteEstConnecteEtEstAdmin())
{
	header("location:../connexion.php");
	exit();
}

$content = '';
$contentA = '';
$contentB = '';
$contenu .= '<a href="?action=commande">Affichage des commandes</a><br />';


echo $contenu;
if(isset($_GET['action']) && $_GET['action'] == "commande") {

$resultat = executeRequete("SELECT id_commande,date_enregistrement,etat FROM commande");

$content .= '<h2> Affichage des commandes en cours </h2>';
$content .= 'Nombre de produit(s) dans la boutique : ' . $resultat->num_rows;
$content .= '<table border="1" cellpadding="5"><tr>';
while($colonne = $resultat->fetch_field())
	{    
		$content .= '<th>' . $colonne->name . '</th>';
	}
	$content .= '</tr>';
	while ($ligne = $resultat->fetch_assoc())
	{
		$content .= '<tr>';
		foreach ($ligne as $indice => $information)
		{
		
				$content .= '<td>' . $information . '</td>';
			
		}
		$content .= '<td><a href="?action=commandeencours&id_commande=' . $ligne['id_commande'] .'"><img src="../inc/img/edit.png" /></a></td>';
		$content .= '</tr>';
	}

	$content .= '</table><br /><hr /><br />';
}

echo $content;

if(isset($_GET['action']) && $_GET['action'] == "commandeencours") {

if(isset($_GET['id_commande'])) {

$resultat = executeRequete("SELECT c.id_commande,d.id_produit, p.titre, p.photo, d.quantite, m.id_membre,m.nom, m.prenom, m.adresse, m.ville, m.code_postal,c.etat FROM details_commande d, produit p, commande c, membre m WHERE (c.id_commande=$_GET[id_commande]) = (d.id_commande=$_GET[id_commande]) AND c.id_membre = m.id_membre");
$produit_actuel = $resultat->fetch_assoc();

}

var_dump($produit_actuel);


echo '
	<h1> Commande </h1>
	<form method="post" enctype="multipart/form-data" action="">
	
		
		<label for="etat">Etat de la commande</label><br />
		<input type="text" id="etat" name="etat" placeholder="Etat du produit"  value="'; if(isset($produit_actuel['etat'])) echo $produit_actuel['etat']; echo '" /><br /><br />

	
			<input type="submit" value="'; echo ucfirst($_GET['action']) . ' du produit"/>
	</form>';
}


require_once("../inc/bas.inc.php"); ?>
