<?php require_once('../inc/init.inc.php');
//Connexion/Déconnexion

if (isset($_GET['action']) && $_GET['action'] == 'deconnexion') {
    session_destroy();
    header("location: connexion.php");
}

if (isConnected()) {
    header("location: profil.php");
}

//Traitement du formulaire
if ($_POST) {
    $result = executeQuery("SELECT * FROM membre WHERE pseudo='".$_POST['pseudo']."';");
    if ($result->num_rows!=0) {
        $membre = $result -> fetch_assoc();
        if ($membre['mdp'] == $_POST['password']) {
            foreach ($membre as $indice => $element) {
                if ($indice != 'mdp') {
                    $_SESSION['membre'][$indice] = $element;
                }
            }
            header("Location: profil.php");
        }
        else {
            $content .= "<div class='erreur'>Mot de passe incorrect !</div>";
        }
    }
    else $content .= "<div class='erreur'>Pseudo incorrect !</div>";
}


?>
<?php require_once('../inc/haut.inc.php'); ?>
<?php echo $content; ?>


<form method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>">
    <label for="pseudo">Pseudo : </label>
        <input type="text" id="pseudo" name="pseudo" maxlength="20" required><br>
    <label for="password">Mot de passe : </label>
        <input type="password" id="password" name="password" required><br>
        <input type="submit" name="connexion" value="Connexion">
</form>

<?php require_once('../inc/bas.inc.php'); ?>

