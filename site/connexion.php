<?php
require_once("inc/init.inc.php");
//--------------------------------- TRAITEMENTS PHP ---------------------------------//
if(isset($_GET['action']) && $_GET['action'] == "deconnexion") 
{
	session_destroy(); 
}
if(internauteEstConnecte()) 
{
	header("location:profil.php");
}
if($_POST)
{
    $resultat = executeRequete("SELECT * FROM membre WHERE pseudo='$_POST[pseudo]'");
    if($resultat->num_rows != 0)
    {

         $cryptage  = MCRYPT_BLOWFISH;         // Algorithme utilisé pour le cryptage des blocs
         $key     = "valentin betrancourt";   // Clé de cryptage
         $membre  = $resultat->fetch_assoc(); // 
         $data64  = $membre['mdp'];
         $keyHash = md5($key);           //On générer une clé valide avec une fonction de hachage

         $key = substr($keyHash, 0, mcrypt_get_key_size($cryptage, MCRYPT_MODE_CBC));


        $data64_dec = base64_decode($data64);

        // Récupère le IV, iv_size doit avoir été créé en utilisant la fonction
        // mcrypt_get_iv_size()
        $iv_size = mcrypt_get_iv_size($cryptage, MCRYPT_MODE_CBC);
        $iv_dec = substr($data64_dec, 0, $iv_size);

        // Récupère le texte du cipher (tout, sauf $iv_size du début)
        $data64_dec = substr($data64_dec, $iv_size);

        // On doit supprimer les caractères de valeur 00h de la fin du texte plein
        $mdpdecrypt = mcrypt_decrypt($cryptage, $key, $data64_dec, MCRYPT_MODE_CBC, $iv_dec);

        if($mdpdecrypt = $_POST['mdp'])
        {
            foreach($membre as $indice => $element)
            {
                if($indice != 'mdp')
                {
                    $_SESSION['membre'][$indice] = $element; 
                }
            }
            header("location:profil.php"); 
        }
        else
        {
            $contenu .= '<div class="erreur">Erreur de MDP</div>';
        }       
    }
    else
    {
        $contenu .= '<div class="erreur">Erreur de pseudo</div>';
    }
}
//--------------------------------- AFFICHAGE HTML ---------------------------------//
?>
<?php require_once("inc/haut.inc.php"); ?>
<?php echo $contenu; ?>
 
<form method="post" action="">
    <label for="pseudo">Pseudo</label><br />
    <input type="text" id="pseudo" name="pseudo" /><br /> <br />
         
    <label for="mdp">Mot de passe</label><br />
    <input type="password" id="mdp" name="mdp" /><br /><br />
 
     <input type="submit" value="Se connecter"/>
</form>
 
<?php require_once("inc/bas.inc.php"); ?>
