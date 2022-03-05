<?php

/**
 * @author Laura Hidalgo Rivera
 * 
 */

session_start();
unset($_SESSION['tablero']);
session_destroy();

if (!isset($_SESSION['tablero'])) {
    header('Location: index.php');
}

?>