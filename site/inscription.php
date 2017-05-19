<?php require_once('../inc/init.inc.php');

//Traitement formulaire

if($_POST) {
    debug($_POST);
    $verif_caractere = preg_match('#^[a-zA-Z0-9._-]+$#',$_POST['pseudo']);

    if((!$verif_caractere) && (strlen($_POST['pseudo'])<1) || (strlen($_POST['pseudo'])>20)) {
        $content .= "<div class=erreur>Le pseudo doit contenir entre 1 et 20 caractères.<br>Caractères acceptés de A à Z et de 0 à 9.<br>";
        echo $content;
    }

    else {
        $membre = executeQuery("SELECT * FROM membre WHERE pseudo='".$_POST['pseudo']."';");
        if ($membre->num_rows>0) {
            $content .= "<div class='erreur'>Le pseudo existe déjà.</div>";
            echo $content;
        }

        else {
            executeQuery("INSERT INTO membre (pseudo, mdp, nom, prenom, email, civilite, ville, code_postal, adresse) VALUES".
                "('".$_POST['pseudo']."', '".$_POST['password']."', '".$_POST['lastname']."', '".$_POST['firstname']."', '".$_POST['email'].
                "', '".$_POST['gender']."', '".$_POST['city']."', '".$_POST['zip']."', '".$_POST['address']."');");

        }
    }
}
?>

<?php require_once('../inc/haut.inc.php'); ?>

<form method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>">
    <label for="pseudo">Pseudo : </label>
        <input type="text" id="pseudo" name="pseudo" maxlength="20" placeholder="Votre pseudo" required><br>
    <label for="password">Mot de passe : </label>
        <input type="password" id="password" name="password" required><br>
    <label for="email">Adresse mail : </label>
        <input type="email" id="email" name="email" placeholder="example@mail.com" required><br><br>

    <label for="gender">Civilité : </label><br>
        <input type="radio" id="gender" name="gender" value="m" required>Mr.<br>
        <input type="radio" id="gender" name="gender" value="f" required>Mme.<br>
    <label for="firstname">Prénom : </label>
        <input type="text" id=firstname" name="firstname" maxlength="20" placeholder="Prénom" required><br>
    <label for="lastname">Nom de famille : </label>
        <input type="text" id="lastname" name="lastname" maxlength="20" placeholder="Nom de famille" required><br><br>

    <label for="address">Adresse : </label>
        <input type="text" id="address" name="address" required><br>
    <label for="zip">Code postal : </label>
        <input type="text" id="zip" name="zip" maxlength="5" required><br>
    <label for="city">Ville : </label>
        <input type="text" id="city" name="city" required><br><br>

    <input type="submit" name="incription" value="S'inscrire">

</form>




<?php require_once('../inc/bas.inc.php'); ?>
