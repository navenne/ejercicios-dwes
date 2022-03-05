<?php
/**
* @author Laura Hidalgo Rivera
* 
*/
echo "<a href='index.php'>Inicio</a>";
$galeria = scandir('upload/');
foreach ($galeria as $foto) {
    if ($foto != "." && $foto != "..") {
    echo "<img src='upload/$foto' width='100px'>";
    }
}
