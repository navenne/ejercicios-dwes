<?php
/**
* @author Laura Hidalgo Rivera
* 
*/

session_start();
if (isset($_SESSION['usuarioActual'])) {
    unset($_SESSION['usuarioActual']);
}

header('Location: index.php');

?>