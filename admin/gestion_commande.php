
<?php 

require_once("../inc/init.inc.php");
require_once("../inc/haut.inc.php");

if(!internauteEstConnecteEtEstAdmin())
{
	header("location:../connexion.php");
	exit();
}


/*foreach($_POST as $indice => $valeur)
	{
		$_POST[$indice] = htmlEntities(addSlashes($valeur));
	}*/
	if ($_POST){
		switch ($_POST['cetat']) {
			case 'livré': executeRequete("UPDATE commande set etat=3 WHERE id_commande=$_GET[id_commande]");
			break;
			case 'envoyé' : executeRequete("UPDATE commande set etat=2 WHERE id_commande=$_GET[id_commande]");
			break;
			case 'en cours de traitement' : executeRequete("UPDATE commande set etat=1 WHERE id_commande=$_GET[id_commande]");
			break;
			default:

		}
		
	}	



	$content = '';
	$contentA = '';
	$somme ='';
	$contenu .= '<a href="?action=commande">Affichage des commandes</a><br />';


	echo $contenu;
	if(isset($_GET['action']) && $_GET['action'] == "commande") {

		$resultat = executeRequete("SELECT id_commande,date_enregistrement,etat,montant FROM commande");

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
			foreach ($ligne as $indice => $informationA)
			{

				$content .= '<td>' . $informationA . '</td>';

			}
			$content .= '<td><a href="?action=commandeencours&id_commande=' . $ligne['id_commande'] .'"><img src="../inc/img/edit.png" /></a></td>';
			$content .= '</tr>';
		}

$content .= '</table><br /><hr /><br />';
     $somme = executeRequete("SELECT sum(montant) FROM commande");
   $sommeB = $somme->fetch_assoc();


   $content .= "Votre chiffre d'affaire est de : ".$sommeB['sum(montant)'];
		$content .= '</table><br /><hr /><br />';
	}
	
	echo $content;
	
	if(isset($_GET['action']) && $_GET['action'] == "commandeencours") {

		if(isset($_GET['id_commande'])) {

			$resultatB = executeRequete("SELECT DISTINCT c.id_commande, d.id_produit, p.titre,p.photo, d.quantite, m.id_membre, m.nom, m.prenom,m.ville, m.code_postal, c.etat FROM commande c, details_commande d, produit p, membre m WHERE c.id_commande = $_GET[id_commande] AND c.id_commande = d.id_commande AND c.id_membre = m.id_membre AND p.id_produit = d.id_produit");



			

	

		echo '
		<h1> Commande </h1>
		<form method="post" enctype="multipart/form-data" action="">


			<label for="cetat">Etat de la commande</label><br />
			

			<select name="cetat"> 

				<option name="1">
				en cours de traitement 
				</option>
				<option name="1"> 
				livré
				</option>
				<option name="3"> 
				envoyé
				</option>


			</select>

			<input type="submit" value="Confirmer"/>
		</form>';


$contentA .= '<table border="1" cellpadding="5"><tr>';
		while($colonne = $resultatB->fetch_field())
		{    
			$contentA .= '<th>' . $colonne->name . '</th>';
		}
		$contentA .= '</tr>';
		while ($ligne = $resultatB->fetch_assoc())
		{
			$contentA .= '<tr>';
			foreach ($ligne as $indice => $informationA)
			if($indice == "photo")
			{
				$contentA .= '<td><img src="' . $informationA . '" width="70" height="70" /></td>';
			}
			else
			{
				$contentA .= '<td>' . $informationA . '</td>';
			}
			
			$contentA .= '</tr>';
		}

$contentA .= '</table><br /><hr /><br />';

		
		
echo $contentA;

}
}




	


	require_once("../inc/bas.inc.php"); ?>
