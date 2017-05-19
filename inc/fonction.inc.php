<?php

function executeQuery($query) {
    global $mysqli;
    $result = $mysqli->query($query);
    if (!$result) {
        die('Erreur lors de la requête<br>'.$mysqli->error.'<br>Code : '.$query);
    }
    return $result;
}

function debug($var, $mode=1) {
    echo '<div style="background: orange; padding: 5px; float: right; clear: both;">';
    $trace = debug_backtrace();
    $trace = array_shift($trace);
    var_dump($trace);
    echo 'Debug demandé dans le fichier : '.$trace['file'].' à la ligne '.$trace['line'].'<br>';
    if ($mode === 1) {
        echo '<pre>'; print_r($var); echo '</pre>';
    }
    else echo '<pre>'; var_dump($var); echo '</pre>';
}

function isConnected() {
    if (!isset($_SESSION['membre'])) {
        return false;
    }
    return true;
}

function isAdmin() {
    if (isConnected() && isset($_SESSION['membre']) && $_SESSION['membre']['statut'] == 1){
        return true;
    }
    else return false;
}
?>