<?php
//------------------------------------
function executeQuery($req)
{
	global $mysqli;
	$resultat = $mysqli->query($req);
	if (!$resultat)
	{
		die("Erreur sur la requete sql.<br />Message : " . $mysqli->error . "<br />Code: " . $req);
	}
	return $resultat;
}
//------------------------------------
function debug($var, $mode = 1)
{
		echo '<div style="background: orange; padding: 5px; float: right; clear: both; ">';
		$trace = debug_backtrace();
		$trace = array_shift($trace);
		echo "Debug demandé dans le fichier : $trace[file] à la ligne $trace[line].<hr />";
		if($mode === 1)
		{
			echo "<pre>"; print_r($var); echo "</pre>";
		}
		else
		{
			echo "<pre>"; var_dump($var); echo "</pre>";
		}
	echo '</div>';
}
//------------------------------------
function isConnected()
{
	if(!isset($_SESSION['membre']))
	{
		return false;
	}
	else
	{
		return true;
	}
}
//------------------------------------
function isAdmin()
{
	if(isConnected() && $_SESSION['membre']['statut'] == 1)
	{
			return true;
	}
	return false;
}

function creationDuPanier()
{
   if (!isset($_SESSION['panier']))
   {
      $_SESSION['panier']=array();
      $_SESSION['panier']['titre'] = array();
      $_SESSION['panier']['id_produit'] = array();
			  $_SESSION['panier']['photo'] = array();
      $_SESSION['panier']['quantite'] = array();
      $_SESSION['panier']['prix'] = array();
   }
}

function ajouterProduitDansPanier($titre,$id_produit,$quantite,$prix,$id_photo)
{
	creationDuPanier();
    $position_produit = array_search($id_produit,  $_SESSION['panier']['id_produit']);
    if ($position_produit !== false)
    {
       $_SESSION['panier']['quantite'][$position_produit] += $quantite ;
    }
    else
    {
			$_SESSION['panier']['titre'][] = $titre;
			$_SESSION['panier']['id_produit'][] = $id_produit;
			$_SESSION['panier']['photo'][] = $id_photo;
			$_SESSION['panier']['quantite'][] = $quantite;
			$_SESSION['panier']['prix'][] = $prix;
    }
}
//------------------------------------
function montantTotal()
{
   $total=0;
   for($i = 0; $i < count($_SESSION['panier']['id_produit']); $i++)
   {
      $total += $_SESSION['panier']['quantite'][$i] * $_SESSION['panier']['prix'][$i];
   }
   return round($total,2);
}
//------------------------------------
function retirerproduitDuPanier($id_produit_a_supprimer)
{
	$position_produit = array_search($id_produit_a_supprimer,  $_SESSION['panier']['id_produit']);
	if ($position_produit !== false)
    {
		array_splice($_SESSION['panier']['titre'], $position_produit, 1);
		array_splice($_SESSION['panier']['id_produit'], $position_produit, 1);
		array_splice($_SESSION['panier']['photo'], $position_produit, 1);
		array_splice($_SESSION['panier']['quantite'], $position_produit, 1);
		array_splice($_SESSION['panier']['prix'], $position_produit, 1);
	}
}

//------------- PAYPAL ----------------//
function initPaypalURL() {
    $url = 'https://api-3t.sandbox.paypal.com/nvp?';
    $user = 'givrefleur_api1.gmail.com';
    $password = 'WSLP8A6PPZ95V2HA';
    $signature = 'AiPC9BjkCyDFQXbSkoZcgqH3hpacAmESlpFherNJROwLytDLRClNKbcR';
    $version = '204.0';

    $url= $url.'USER='.$user.'&PWD='.$password.'&SIGNATURE='.$signature.'&VERSION='.$version.'&METHOD=SetExpressCheckout&AMT=10.0'.
            '&CANCELURL='.urlencode("http://localhost/projet_php_ingesupb1/cancel.php").
			'&RETURNURL='.urlencode("http://localhost/projet_php_ingesupb1/site/return.php").
			'&CURRENCYCODE=EUR'.
			'&LOCALECODE=FR';

    return $url;
}



function recupParametresPaypal($retour_paypal)
{
    $liste_param_paypal = '';

    $liste_param = explode("&", $retour_paypal);
    foreach ($liste_param as $param) {
        list($nom, $valeur) = explode("=", $param);
        $liste_param_paypal[$nom] = urldecode($valeur);
    }
    echo '<pre>'; print_r($liste_param_paypal); echo '</pre>';
    return $liste_param_paypal;
}
