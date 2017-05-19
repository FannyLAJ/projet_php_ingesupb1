<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="content-type" content="text/html, charset: utf8">
        <title>SITE E-COMMERCE</title>
        <link rel="stylesheet" href="<?php echo ROOT_SITE ?>inc/css/style.css">
    </head>

    <body>
        <header>
            <div class="wrapper">
                <span>
                    <a href="#" title="Monsite">Monsite.com</a>
                </span>
                <nav>
                    <?php
                        if (isConnected() && isAdmin()) {
                            echo '<a href="'.ROOT_SITE.'admin/gestion_membre.php">Gestion des membres</a>';
                            echo '<a href="'.ROOT_SITE.'admin/gestion_boutique.php">Gestion de la boutique</a>';
                            echo '<a href="'.ROOT_SITE.'admin/gestion_produit.php">Gestion des produits</a>';
                        }
                        if (isConnected()) {
                            echo '<a href="'.ROOT_SITE.'site/boutique.php">Boutique</a>';
                            echo '<a href="'.ROOT_SITE.'site/panier.php">Mon panier</a>';
                            echo '<a href="'.ROOT_SITE.'site/profil.php">Mon profil</a>';
                            echo '<a href="'.ROOT_SITE.'site/connexion.php?action=deconnexion">Déconnexion</a>';
                        }
                        else {
                            echo '<a href="'.ROOT_SITE.'site/inscription.php">Inscription</a>';
                            echo '<a href="'.ROOT_SITE.'site/connexion.php">Connexion</a>';
                            echo '<a href="'.ROOT_SITE.'site/boutique.php">Boutique</a>';
                            echo '<a href="'.ROOT_SITE.'site/panier.php">Mon panier</a>';
                        }
                    ?>
                </nav>
            </div>
        </header>

        <section>
            <div class="wrapper">

