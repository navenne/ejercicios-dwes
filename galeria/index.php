<?php
/**
* @author Laura Hidalgo Rivera
* 
*/

session_start();

if (!isset($_SESSION['auth'])) {
    $_SESSION['auth'] = false;
}
if (isset($_POST['submit'])) {
    if (($_POST['user'] == 'usuario') && ($_POST['password'] == '1234')) {
        $_SESSION['auth'] = true;
    }
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="author" content="Laura Hidalgo Rivera">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Actividad 1</title>
</head>
<body>
    <h1>Inicio</h1>
    <nav>
        <a href="index.php">Inicio</a>
        <a href="galeria.php">Galería</a>
        <br>
        <?php
            if ($_SESSION['auth']) {
                echo "<a href='salir.php'>Salir</a>";
            }
        ?>
    </nav>
    <div>
        <?php
            
                include "upload_form.html";
            
        ?>
    </div>
</body>
</html>