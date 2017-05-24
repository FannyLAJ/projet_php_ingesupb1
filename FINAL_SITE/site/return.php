<?php require_once('../inc/init.inc.php'); ?>

<?php include('../inc/haut.inc.php');
//-- Vérification de la commande par l'utilisateur --//

$requete = initPaypalURL();
$requete = $requete."&METHOD=GetExpressCheckoutDetails"
            ."TOKEN=".$_GET['token'];

$curl = curl_init($requete);
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
$retour_paypal = curl_exec($curl);

if (!$retour_paypal) {
    echo "<p>Erreur</p><p>";
    echo curl_error($curl);
    echo "</p>";
}
else {
    $liste_param_paypal=recupParametresPaypal($retour_paypal);
}

$contenu .= '<div>Vérification commande</div>'.
    '<div>Vous êtes sur le point d\'acheter :</div>';
echo $contenu;
echo "<table border='1' style='border-collapse: collapse' cellpadding='7'>";
echo "<tr><td colspan='5'>Panier</td></tr>";
echo "<tr><th>Titre</th><th>Produit</th><th>Quantité</th><th>Prix Unitaire</th><th>Apercu</th></tr>";
for($i = 0; $i < count($_SESSION['panier']['id_produit']); $i++)
{
    echo "<tr>";
    echo "<td>" . $_SESSION['panier']['titre'][$i] . "</td>";
    echo "<td>" . $_SESSION['panier']['id_produit'][$i] . "</td>";
    echo "<td>" . $_SESSION['panier']['quantite'][$i] . "</td>";
    echo "<td>" . $_SESSION['panier']['prix'][$i] . "</td>";
    echo "<td><img  height= '70px' width='50px' alt='apercu' src='" . $_SESSION['panier']['photo'] . "' /></td>";
    echo "</tr>";

}
echo "<tr><th colspan='3'>Total</th><td colspan='2'>" . montantTotal() . " euros</td></tr>";
echo "<tr><th colspan='3'>HT</th><td colspan='2'>" . montantTotal() * 80 / 100  . " euros</td></tr>";
if(internauteEstConnecte())
{
    echo '<form method="post" action="">';
    echo '<tr><td colspan="5"><input type="submit" name="payer" value="Valider et déclarer le paiement" /><input type="submit" name="cancel" value="Annuler"></td></tr>';
    echo '</form>';

}
else
{
    echo '<tr><td colspan="3">Veuillez vous <a href="inscription.php">inscrire</a> ou vous <a href="connexion.php">connecter</a> afin de pouvoir payer</td></tr>';
}
echo "<tr><td colspan='5'><a href='?action=cancel'>Vider mon panier</a></td></tr>";
echo "</table>";

//Si l'utilisateur annule le paiement, il est redirigé.
if (isset($_POST['cancel'])) {
    header('location: cancel.php');
}
curl_close($curl);

//Sinon, on procède au paiement.
if (isset($_POST['payer'])) {
$amt = montantTotal();
$requete = initPaypalURL();
$requete = $requete.'&METHOD=DoExpressCheckoutPayment'
            .'&TOKEN='.$_GET['token'].'&AMT='.$amt
            .'&CURRENCYCODE=EUR'.'&PayerID='.$_GET['PayerID']
            .'&PAYMENTACTION=sale';

$curl = curl_init($requete);
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);


$retour_paypal = curl_exec($curl);

if (!$retour_paypal) {
    echo "<p>Erreur</p><p>";
    echo curl_error($curl);
    echo "</p>";
}
else {
    $liste_param_paypal=recupParametresPaypal($retour_paypal);

    if ($liste_param_paypal['ACK'] == 'Success') {
        echo '<div class="validation">Paiement effectué avec succès</div>';
        unset($_SESSION['panier']);
    }
}

curl_close($curl);
}

?>
<?php require_once('../inc/bas.inc.php'); ?>
