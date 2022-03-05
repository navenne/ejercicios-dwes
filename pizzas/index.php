<?php
/**
 * @author Laura Hidalgo Rivera
 */
session_start();

// Array de usuarios
if (!isset($_SESSION['usuarios'])) {
    $_SESSION['usuarios'] = array(
        array('usuario' => 'administrador',
            'psw' => 'administrador',
            'perfil' => 'administrador'),
        array('usuario' => 'cliente',
            'psw' => 'cliente',
            'perfil' => 'cliente'),
        array('usuario' => 'invitado',
            'psw' => 'invitado',
            'perfil' => 'invitado'),
    );
}

// Array de pizzas
if (!isset($_SESSION['pizzas'])) {
    $_SESSION['pizzas'] = array(
        array("tipo" => "Cuatro Quesos", "precio" => 8),
        array("tipo" => "Primavera", "precio" => 9),
        array("tipo" => "Romana", "precio" => 8.5),
    );
}

// Datos del formulario
$usuarioErr = $psw = $pswErr = $nombre = $nombreErr = $tel = $telErr = "";
$usuario = isset($_POST['usuario']) ? clearData($_POST['usuario']) : "";
$psw = isset($_POST['psw']) ? clearData($_POST['psw']) : "";
$processform = false;

// Validación de datos del formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Inicio de sesión
    if (isset($_POST["login"])) {
        $processform = false;
        if (buscarUsuario($usuario, $psw) != -1) {
            $usuarioActual = buscarUsuario($usuario, $psw);
            setUsuarioActual($usuarioActual);
            $usuarioErr = "";
            $pswErr = "";
        } else {
            $usuarioErr = "Usuario o contraseña incorrectos";
            $pswErr = "Usuario o contraseña incorrectos";
        }
    }

    // Datos del pedido
    if (isset($_POST["pedir"])) {
    $processform = true;
        if (empty($_POST["nombre"])) {
            $nombreErr = "El nombre es obligatorio";
            $processform = false;
        } else {
            $name = clearData($_POST["nombre"]);
        }

        if (empty($_POST["tel"])) {
            $telErr = "El teléfono es obligatorio";
            $processform = false;
        } else {
            $name = clearData($_POST["tel"]);
        }
    }
}

?>
    <!DOCTYPE html>
    <html lang="es">

    <head>
        <meta charset="UTF-8">
        <meta name="author" content="Laura Hidalgo Rivera">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Pizzas - Ejercicio Repaso</title>
        <style>
            .error {
                color: red
            }

            ;
        </style>
    </head>

    <body>
    <nav>
        <a href="index.php">Inicio</a>
        <?php
        if (isset($_SESSION["usuarioActual"])) {
            echo "<a href=\"salir.php\">Salir</a> ";
        }
        if (isset($_SESSION["usuarioActual"])) {
            if ($_SESSION['usuarioActual']["perfil"] == "administrador") {
                echo "<a href=\"administrador.php\"> Crear pizzas</a>";
            }
        } else {
        ?>
    </nav>
    <h2>Login</h2>
    <form action="<?= htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <label>Usuario <input type="text" name="usuario"></label>
        <span class="error"><?= $usuarioErr; ?></span>
        <br><br>
        <label>Contraseña <input type="password" name="psw"></label>
        <span class="error"><?= $pswErr; ?></span>
        <br><br>
        <input type="submit" name="login" value="Login">
    </form>
    <?php
    }
    ?>
    <h2>Pedido</h2>
    <form action="<?= htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <label>Nombre <input type="text" name="nombre"></label>
        <span class="error"><?= $nombreErr; ?></span>
        <br><br>
        <label>Teléfono <input type="tel" name="tel"></label>
        <span class="error"><?= $telErr; ?></span>
        <br><br>
        <?php
        foreach ($_SESSION['pizzas'] as $key => $pizza) {
            ?>
            <div style="display: flex; padding: 5px;">
                <div style="margin-right: 5px;">
                    <p><?= $pizza['tipo']; ?></p>
                    <label>Cantidad <input type='number' name='<?= $key; ?>' value='<?= isset($_COOKIE[str_replace(" ", "", $pizza["tipo"])]) ? $_COOKIE[str_replace(" ", "", $pizza["tipo"])] : "" ?>'></label>
                    <p>Precio: <?= $pizza['precio'] ?> €</p>
                </div>
                <img src='img/<?= str_replace(" ", "", strtolower($pizza["tipo"])) ?>.jpg' width='200'>
            </div>
            <?php
        }
        ?>
        <input type="submit" name="pedir">
    </form>
    <?php
    if ($processform) {
        $filename = date('dmY_His') . '.txt';
        $content = array("Pedido\n", "Nombre: ", $_POST['nombre'], "\n", "Teléfono: ", $_POST['tel'], "\n");
        $total = 0;
        foreach ($_SESSION['pizzas'] as $key => $pizza) {
            $cantidad = $_POST[$key];
            setcookie(str_replace(" ", "", $pizza["tipo"]), $cantidad, time() + 3600 * 24 * 365);
            if ($cantidad > 0) {
                array_push($content, $pizza['tipo'], " (", $pizza['precio'], "€) x", $cantidad, " -> ", $pizza['precio']*$cantidad, "€\n");
                $total += $pizza['precio']*$cantidad;
            }
        }
        if ($_SESSION['usuarioActual']['perfil'] == "cliente") {
            array_push($content, "Descuento del 10% -> ", $total, "€ - ", $total*0.1, "€\n");
            $total *= 0.9;
        }
        array_push($content, "Total: ", $total, "€");
        file_put_contents($filename, $content);

        echo "Pedido realizado con éxito";
        header('Location: index.php');
    }
    ?>
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

/**
 * Busca un usuario en el array
 * @param $usuario
 * @param $psw
 * @return int|string $key si lo encuentra, -1 si no
 */
function buscarUsuario($usuario, $psw)
{
    foreach ($_SESSION["usuarios"] as $key => $user) {
        if ($user["usuario"] == $usuario && $user["psw"] == $psw) {
            return $key;
        }
    }
    return -1;
}

/**
 * @param int $usuarioActual
 */
function setUsuarioActual(int $usuarioActual): void
{
    $_SESSION["usuarioActual"]["usuario"] = $_SESSION["usuarios"][$usuarioActual]["usuario"];
    $_SESSION["usuarioActual"]["psw"] = $_SESSION["usuarios"][$usuarioActual]["psw"];
    $_SESSION["usuarioActual"]["perfil"] = $_SESSION["usuarios"][$usuarioActual]["perfil"];
}