<?php

try {
    $pdo = new PDO('mysql:host=127.0.0.1;dbname=barcodedb;charset=utf8', 'admin', '12345678');

    $pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);

   

   
    
    

}
catch(PDOException $e)
{
    echo($e->getMessage());
}

?>