<?php
require_once("inc/init.inc.php");
//--------------------------------- TRAITEMENTS PHP ---------------------------------//
//--- AJOUT PANIER ---//
if(isset($_POST['ajout_panier']))
{	// debug($_POST);
	$resultat = executeRequete("SELECT * FROM produit WHERE id_produit='$_POST[id_produit]'");
	$produit = $resultat->fetch_assoc();

	ajouterProduitDansPanier($produit['titre'], $_POST['id_produit'],$_POST['quantite'],$produit['prix'],$produit['photo']);
}
//--- VIDER PANIER ---//
if(isset($_GET['action']) && $_GET['action'] == "vider")
{
	unset($_SESSION['panier']);
}
//----Modifier le panier---//
if($_POST){	//Si formulaire validé
	for($i = 0; $i < count($_SESSION['panier']['id_produit']); $i++){	//Pour chaque produit dans le panier
		if(isset($_POST['-' . $i]) && $_SESSION['panier']['quantite'][$i] > 0){		//Si bouton - cliquer et que la quantité est > 0
			$_SESSION['panier']['quantite'][$i]--;		//On diminue la quantité de 1
		}
		if(isset($_POST['+' . $i])){		//Si bouton +
			$_SESSION['panier']['quantite'][$i]++;		//On augmente la quantité de 1
		}
		if(isset($_POST['Supprimer' . $i])){	//Si bouton Suppri;er
			$_SESSION['panier']['quantite'][$i] = 0;	//On passe la quantité a 0
		}
	}
}


//--- PAIEMENT ---//
if(isset($_POST['payer']))
{
	for($i=0 ;$i < count($_SESSION['panier']['id_produit']) ; $i++)
	{
		$resultat = executeRequete("SELECT * FROM produit WHERE id_produit=" . $_SESSION['panier']['id_produit'][$i]);
		$produit = $resultat->fetch_assoc();
		if($produit['stock'] < $_SESSION['panier']['quantite'][$i])
		{
			$contenu .= '<hr /><div class="erreur">Stock Restant: ' . $produit['stock'] . '</div>';
			$contenu .= '<div class="erreur">Quantité demandée: ' . $_SESSION['panier']['quantite'][$i] . '</div>';
			if($produit['stock'] > 0)
			{
				$contenu .= '<div class="erreur">la quantité de l\'produit ' . $_SESSION['panier']['id_produit'][$i] . ' à été réduite car notre stock était insuffisant, veuillez vérifier vos achats.</div>';
				$_SESSION['panier']['quantite'][$i] = $produit['stock'];
			}
			else
			{
				$contenu .= '<div class="erreur">l\'produit ' . $_SESSION['panier']['id_produit'][$i] . ' à été retiré de votre panier car nous sommes en rupture de stock, veuillez vérifier vos achats.</div>';
				retirerproduitDuPanier($_SESSION['panier']['id_produit'][$i]);
				$i--;
			}
			$erreur = true;
		}
	}
	if(!isset($erreur))
	{
		executeRequete("INSERT INTO commande (id_membre, montant, date_enregistrement) VALUES (" . $_SESSION['membre']['id_membre'] . "," . montantTotal() . ", NOW())");
		$id_commande = $mysqli->insert_id;
		for($i = 0; $i < count($_SESSION['panier']['id_produit']); $i++)
		{
			executeRequete("INSERT INTO details_commande (id_commande, id_produit, quantite, prix) VALUES ($id_commande, " . $_SESSION['panier']['id_produit'][$i] . "," . $_SESSION['panier']['quantite'][$i] . "," . $_SESSION['panier']['prix'][$i] . ")");
		}
		mail($_SESSION['membre']['email'], "confirmation de la commande", "Merci votre n° de suivi est le $id_commande", "From:vendeur@dp_site.com");
		$contenu .= "<div class='validation'>Merci pour votre commande. votre n° de suivi est le $id_commande</div>";
		
		$requete = initPaypalURL();
       		$curl = curl_init($requete);
        	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0); //Afin d'éviter les erreurs, la connexion sécurisée est désactivée
        	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        	$retour_paypal = curl_exec($curl);
        	if (!$retour_paypal) 
		{
            		echo "<p>Erreur</p><p>";
            		echo curl_error($curl);
            		echo "</p>";
        	}
        	else 
		{
            		$liste_param_paypal=recupParametresPaypal($retour_paypal);

            		if ($liste_param_paypal['ACK'] == 'Success') 
			{
                		header("Location: https://www.sandbox.paypal.com/webscr&cmd=_express-checkout&token=".$liste_param_paypal['TOKEN']);
            		}
        	}
        	curl_close($curl);
	}
}

//--------------------------------- AFFICHAGE HTML ---------------------------------//
include("inc/haut.inc.php");
echo $contenu;
echo "<table border='1' style='border-collapse: collapse' cellpadding='7'>";
echo "<tr><td colspan='5'>Panier</td></tr>";
echo "<tr><th>Titre</th><th>Produit</th><th>Quantité</th><th>Prix Unitaire</th><th>Apercu</th></tr>";
if(empty($_SESSION['panier']['id_produit'])) // panier vide
{
	echo "<tr><td colspan='5'>Votre panier est vide</td></tr>";
}
else
{
	for($i = 0; $i < count($_SESSION['panier']['id_produit']); $i++)	//Pour chaque produit du panier
	{
		if($_SESSION['panier']['quantite'][$i] > 0){	//Si quantité > 0
			//On affiche les infos du produit
			echo "<tr>";
			echo "<td>" . $_SESSION['panier']['titre'][$i] . "</td>";
			echo "<td>" . $_SESSION['panier']['id_produit'][$i] . "</td>";
			echo "<td><form action='#' method='post'><input type='submit' value='-' name='-" . $i . "' />" . $_SESSION['panier']['quantite'][$i] . "<input type='submit' value='+' name='+" . $i . "' /></td>";
			echo "<td>" . $_SESSION['panier']['prix'][$i] . "</td>";
			echo "<td><img width='50px' alt='apercu' src='" . $_SESSION['panier']['photo'][$i] . "' /></td>";
			echo "<td><form action='#' method='post'><input type='submit' value='Supprimer' name='Supprimer" . $i . "' /></form></td>";
			echo "</tr>";
		}
	}
	echo "<tr><th colspan='3'>Total</th><td colspan='2'>" . montantTotal() . " euros</td></tr>";
	echo "<tr><th colspan='3'>HT</th><td colspan='2'>" . montantTotal() * 80 / 100  . " euros</td></tr>";
	if(internauteEstConnecte())
	{
		echo '<form method="post" action="">';
		echo '<tr><td colspan="5"><input type="submit" name="payer" value="Valider et déclarer le paiement" /></td></tr>';
		echo '</form>';
	}
	else
	{
		echo '<tr><td colspan="3">Veuillez vous <a href="inscription.php">inscrire</a> ou vous <a href="connexion.php">connecter</a> afin de pouvoir payer</td></tr>';
	}
	echo "<tr><td colspan='5'><a href='?action=vider'>Vider mon panier</a></td></tr>";
}
echo "</table><br />";
echo "<em>Réglement par CHÈQUE uniquement à l'adresse suivante : administration Ynov Campus, 2 Rue de la Bourse 13109 Aix-en-Provence</em><br />";
include("inc/bas.inc.php");
