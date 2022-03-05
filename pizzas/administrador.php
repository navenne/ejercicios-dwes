<?php
/**
 * @author Laura Hidalgo Rivera
 */
session_start();

define("DIRUPLOAD",'img/');
define("MAXSIZE",8000000);

$allowedExts = array("jpeg", "jpg", "png");
$allowedFormat = array("image/jpeg","image/jpg","image/jpeg","image/x-png","image/png");

$nombre = $precio = "";

if (!isset($_SESSION['usuarioActual']) || !$_SESSION['usuarioActual']["perfil"] == "administrador") {
    header('Location: index.php');
}

if (!isset($_SESSION["pizzas"])) {
    $_SESSION["pizzas"] = array();
}

if (isset($_POST['submit'])) {
    $nombre = clearData($_POST["nombre"]);
    $precio = clearData($_POST["precio"]);

    // Obtener la extensión
    $temp = explode(".", $_FILES["file"]["name"]);
    $extension = end($temp);

    if (( $_FILES["file"]["size"] < MAXSIZE) &&
        in_array($_FILES["file"]["type"],$allowedFormat)  &&
        in_array($extension, $allowedExts)) {

        if ($_FILES["file"]["error"] > 0)    {
            echo "Return Code: " . $_FILES["file"]["error"] . "<br/>";
        }
        else {
            $filename = $_FILES["file"]["name"];
            $filename = strtolower(str_replace(" ", "", $nombre)) . ".jpg";

            if (file_exists(DIRUPLOAD .$filename )) {
                echo $_FILES["file"]["name"] . " ya existe. ";
            }
            else {
                move_uploaded_file($_FILES["file"]["tmp_name"], DIRUPLOAD . $filename);
            }
        }

        array_push($_SESSION["pizzas"], array("tipo" => $nombre, "precio" => $precio));
        echo "Pizza creada con éxito.";
    }
    else {
        echo "Archivo demasiado grande o inválido. No se ha podido crear la pizza.";
    }
}

?>

    <!DOCTYPE html>
    <html lang="es">

    <head>
        <meta charset="UTF-8">
        <meta name="author" content="Laura Hidalgo Rivera">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Crear pizzas</title>
    </head>

    <body>
    <a href="index.php">Inicio</a>
    <a href="salir.php">Salir</a><br>

    <h1>Crear pizza</h1>
    <form action="<?= htmlspecialchars($_SERVER["PHP_SELF"]); ?>" enctype="multipart/form-data" method="POST">
        <label>Nombre <input type="text" name="nombre" value="<?= $nombre ?>"></label>
        <br /><br />
        <label>Precio <input type="number" name="precio" value="<?= $precio ?>"></label>
        <br /><br />
        <label>Foto: <input type="file" name="file"></label>
        <br/>
        <input type="submit" name="submit" value="Crear">
    </form>

    </body>

    </html>

<?php
/**
 * Devuelve la cadena libre de caracteres especiales
 * @param $data
 * @return string
 */
function clearData($data)
{
    return stripslashes(htmlspecialchars(trim($data)));
}