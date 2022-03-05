<?php

/**
 * @author Laura Hidalgo Rivera
 * mina -> 9
 * numero -> 1-8
 * vacío -> 0
 * funcion que crea el tablero vacío
 * $_SESSION['tablero']
 * 1. Crear el tablero vacío.
 * 2. Colocar las minas.
 *   bucle de 10. Generar 2 num aleatorios. Uno para la fila y otro para la columna.
 *   do while para que no salgan repetidos.
 * 3. Casillas de números.
 * 4. Ganar. casillas visibles -> todas las casillas menos las minas
 * 5. Perder. clic en casilla 9.
 */
session_start();

define("TAM", 10);
define("MINAS", 10);

if (!isset($_SESSION['tablero'])) {
    $_SESSION['tablero'] = array();
    $_SESSION['visibles'] = array();
    crearTablero();
}

if (isset($_GET["button"])) {
    if (clicCasilla(substr($_GET["button"], 0, 1), substr($_GET["button"], 1, 2)) == -1) {
        echo "Has perdido.";
    } elseif (clicCasilla(substr($_GET["button"], 0, 1), substr($_GET["button"], 1, 2)) == 1) {
        echo "Has ganado.";
    }
}



function crearTablero()
{
    for ($i = 0; $i < TAM; $i++) {
        for ($j = 0; $j < TAM; $j++) {
            $_SESSION['tablero'][$i][$j] = 0;
            $_SESSION['visibles'][$i][$j] = 0;
        }
    }


    for ($i = 0; $i < MINAS; $i++) {
        do {
            $fila = rand(0, 9);
            $columna = rand(0, 9);
        } while ($_SESSION['tablero'][$fila][$columna] == 9);

        $_SESSION['tablero'][$fila][$columna] = 9;

        for ($j = max($fila - 1, 0); $j <= min($fila + 1, TAM - 1); $j++) {
            for ($k = max($columna - 1, 0); $k <= min($columna + 1, TAM - 1); $k++) {
                if ($_SESSION['tablero'][$j][$k] != 9) {
                    $_SESSION['tablero'][$j][$k] += 1;
                }
            }
        }
    }
    return $_SESSION['tablero'];
}

function visualizarTablero()
{
    echo "<form action='" . htmlspecialchars($_SERVER["PHP_SELF"]) . "' method='get'>";
    echo "<table>";
    for ($i = 0; $i < TAM; $i++) {
        echo "<tr>";
        for ($j = 0; $j < TAM; $j++) {
            echo "<td>";
            if (isset($_GET['button']) &&  $_SESSION['visibles'][$i][$j] == 1) {
                echo $_SESSION['tablero'][$i][$j];
            } else {
                echo "<button type='submit' name='button' value='$i$j'>";
            }
            echo "</td>";
        }
        echo "</tr>";
    }
    echo "</table>";
    echo "</form>";


}

function ganar()
{
    return count($_SESSION['visibles']) == TAM*TAM-MINAS ? true : false;
}

function clicCasilla($fila, $columna)
{
    if ($_SESSION['visibles'][$fila][$columna] == 0) {

        $_SESSION['visibles'][$fila][$columna] = 1;

        if ($_SESSION['tablero'][$fila][$columna] == 9) {
            return -1;
        } elseif (ganar()) {
            return 1;
        } elseif ($_SESSION['tablero'][$fila][$columna] == 0) {
            clicCasilla(max($fila-1,0), $columna);
            clicCasilla($fila, max($columna-1,0));
            clicCasilla($fila, min($columna+1, TAM-1));
            clicCasilla(min($fila+1,TAM-1), $columna);
        } else {
            return;
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <link
      href="https://fonts.googleapis.com/css2?family=Roboto&display=swap"
      rel="stylesheet"
    />
    <title>Document</title>
</head>

<body>
    <h1>Buscaminas</h1>
    <?php
    visualizarTablero();

    echo "<a href='cerrar_sesion.php'>Reiniciar</a>";

    ?>
</body>

</html>