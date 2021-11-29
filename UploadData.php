<?php
require 'DBconnect.php';
try {
     



   

   
    if($_SERVER['REQUEST_METHOD'] == 'POST')
 {

    

 $barcode   = $_POST['barcode'];
 $stock = $_POST['stock'];
 $ImageData = $_POST['image_data'];


 $partNo = $_POST['partno'];

 $desc = $_POST['desc'];

 $brand = $_POST['brand'];

 $location = $_POST['location'];

 $inout = $_POST['inout'];

$serverAddress = $_POST['serverAddress'];



 $ImageName = preg_replace("/[^A-Za-z0-9 ]/", '', $barcode); 
 
 $ImagePath = "$serverAddress/images/$ImageName.jpg";

 $ImagePathX = "images/$ImageName.jpg";
 
 $ServerURL = "$ImagePath";
 //check before insert as new item if the barcode registered
 $sql = "select * from items where barcode ='$barcode'";
 $result=$pdo->query($sql);

    if($result->rowCount() > 0)
    {
        
        // we have the item only update the stock QTY
        $updateSQL = "UPDATE `items` SET `stock` = '$stock', `partno` = '$partNo', `desc` = '$desc', `brand` = '$brand', `location` = '$location', `image` = '$ServerURL'   WHERE (`barcode` = '$barcode'); ";
        $stmt = $pdo->prepare($updateSQL);
        if($stmt->execute())
        {
            file_put_contents($ImagePathX,base64_decode($ImageData));


            $hSQL="INSERT INTO `history` SET `stock` = '$stock', `partno` = '$partNo', `desc` = '$desc', `brand` = '$brand', `location` = '$location', `inout` = '$inout', `barcode`= '$barcode', `image` = '$ServerURL' ";
            $stmt = $pdo->prepare($hSQL);
            $stmt->execute();


            echo("Your Data Has Been Updated."); 
        }

        //register the transaction in history table

        
    }
    else
    {
       
        
        $InsertSQL = "INSERT INTO `items` (`barcode`, `partno`, `desc`, `brand`, `location`, `stock`, `image`) VALUES ('$barcode', '$partNo', '$desc', ' $brand', '$location', '$stock', '$ServerURL');";
       
       
        $stmt = $pdo->prepare($InsertSQL);
        
        if($stmt->execute())
        {
       
        file_put_contents($ImagePathX,base64_decode($ImageData));


        $hSQL="INSERT INTO `history` SET `stock` = '$stock', `partno` = '$partNo', `desc` = '$desc', `brand` = '$brand', `location` = '$location', `inout` = '$inout', `barcode`= '$barcode', `image` = '$ServerURL' ";
        $stmt = $pdo->prepare($hSQL);
        $stmt->execute();



        echo("Your Data Has Been Inserted.");
        }
    }
 


}

   
    
    

}
catch(Exception $e)
{
    echo($e->getMessage());
}

?>