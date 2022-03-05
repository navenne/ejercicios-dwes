<?php
/**
* @author Laura Hidalgo Rivera
* 
*/

    define("DIRUPLOAD",'upload/');
    define("MAXSIZE",200000);

    $allowedExts = array("gif", "jpeg", "jpg", "png", "PNG");
    $allowedFormat = array("image/gif","image/jpeg","image/jpg","image/jpeg","image/x-png","image/png", "image/PNG");
    
    //Obtenemos la extensión, podriamos hacerlo tambien con pathinfo() más adelante.
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
            /* Conviene renombrar la imagen bien con el id de una base de datos o con un 
            identificador único
            */
           // $filename = time() . $filename; 
            $filename = uniqid().'.'.pathinfo($filename,PATHINFO_EXTENSION);

            if (file_exists(DIRUPLOAD .$filename )) {
                echo $_FILES["file"]["name"] . " already exists. ";
             }
            else {  
                move_uploaded_file($_FILES["file"]["tmp_name"], DIRUPLOAD . $filename);
             }
            echo "<br/>";
            echo "<a href=\"".$_SERVER['HTTP_REFERER']."\">Volver</a>";
            echo "<h2>Galería</h2>";
            include "galeria.php";
      }
  }
else {
  echo "Invalid file";
}
