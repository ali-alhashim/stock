
<?php

header("Content-Type: application/json; charset=UTF-8");
require 'DBconnect.php';


        
    
         $barcode =  $_GET["barcode"];

try
{
   
   
    
        
        


        if($barcode !=null)
        {  

        $sql = "select * from items where barcode ='$barcode'";

    $result=$pdo->query($sql);

    if($result->rowCount() > 0)
    {
        
        // we have the item
       
         
        
         $outp = $result->fetch();

         echo json_encode($outp);
       
    }
    else
    {
        // we don't have the item
        $myObj = new class{};
        $myObj->partno = "";
        echo(json_encode($myObj));
    }

    }


    
    else
    {
        echo("you did not sent a barcode !");
    }
}
catch(Exception $e)
{
    echo($e->getMessage());
}
?>


