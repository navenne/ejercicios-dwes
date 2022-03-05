<?php
/**
* @author Laura Hidalgo Rivera
* 
*/

session_start();

if (isset($_SESSION['auth'])) {
    $_SESSION['auth'] = false;
}

header('Location: index.php');

?>