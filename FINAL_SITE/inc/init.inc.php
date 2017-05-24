<?php
//Connexion à la base de données
$mysqli = new mysqli('localhost', 'root', '', 'site');
if ($mysqli->connect_error) die('Un problème est survenu lors de la connexion à la base de données'.$mysqli->connect_errno);
$mysqli->set_charset('utf-8');

//Initialisation de la session
session_start();

define('ROOT_SITE', '/site/', true);
$contenu = '';
require_once('fonction.inc.php');
?>
