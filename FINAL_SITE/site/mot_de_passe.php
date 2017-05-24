<?php
  require_once("../inc/init.inc.php");

  //Création du formulaire
  $contenu .= '<h1>Mot de passe oublié</h1>';
  $contenu .= '<form action="#" method="POST">';
  $contenu .= '<label for="email">Adresse mail : </label>';
  $contenu .= '<input type="text" name="email" /><br />';
  $contenu .= '<input type="submit" value="Envoyer" />';
  $contenu .= '</form>';
?>

<?php
  // EXERCICE 5 --
  if($_POST){                                                                                 //Si le formulaire est envoyé
    $to = $_POST['email'];                                                                    //On définie l'adresse mail de l'utilisateur
    if($mdp = executeQuery("SELECT mdp FROM membre WHERE email = '$to'")){                  //Si ce mail est dans la BDD
      if($mdp->num_rows == 1){                                                                //Si on as 1 retour
        $mdp = $mdp->fetch_assoc();                                                           //On fecth le resultat de la requete
        $message = "Suite a votre demande, voici votre mot de passe : " . $mdp['mdp'];        //On définie le message du mail
        mail($to, "Oublie MDP", $message);                                                    //On envoie le mail
        $contenu .= '<h2 style="color:green">Mail envoyé</h2>';                               //On affiche que le mail est bien envoyé
      } else{
        $contenu .= '<h2 style="color:red">Erreur : Ce mail n est pas enregistré</h2>';       //Sinon, on affiche que le mail n'est pas enregistré
      }
    } else{
      $contenu .= '<h2 style="color:red">Erreur : probleme SQL</h2>';         //Sinon, on affiche une erreur
    }
  }
?>

<?php
  require_once("../inc/haut.inc.php");
  echo $contenu;
  require_once("../inc/bas.inc.php"); ?>
