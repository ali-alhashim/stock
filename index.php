<?php
require 'Sessions.php';
require 'DBconnect.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Stock Management by Ali AI</title>
    <link rel="stylesheet" href="Style_index.css" type="text/css"/>
</head>

<body class="bodyBackground">

<?php
echo Message();

//---------------- count items -------------
$sql = "SELECT COUNT(*) FROM items;";


$result=$pdo->query($sql);
$countRow = $result->fetch();


//-----------------------------------------

?>



<table class="menuTable">



     <tr>
        <td>
             <div class="menuInfo"> Total Items: <?php echo($countRow[0]); ?> </div> 
       </td>
    </tr>


    <tr>
        <td>
            <a href="itemList.php"> <div class="menuItem"> Items List </div> </a>
       </td>
    </tr>
    
 




    <tr>
        <td>
            <a href="SalesInvoice.php"> <div class="menuItem"> Sales Invoice </div> </a>
       </td>
    </tr>



    <tr>
        <td>
            <a href="history.php"> <div class="menuItem"> Items History </div> </a>
       </td>
    </tr>

    <tr>
        <td>
            <div class="menuItem">  Control Panel
                <br>
                <form action="Cpanel.php" method="POST">
                    <input type="text" name="username">
                    <input type="password" name="pass">
                    <br>
                    <input type="submit" value="Login" style="width: 32%; height: 30px;margin: 10px;font-weight: bolder;">
                   
                </form>
                 </div> 
       </td>
    </tr>



    <tr>
        <td>
            <a href="barcode13.apk"> <div class="menuItem">  Download Android App </div> </a>
       </td>
    </tr>


</table>
    
</body>
</html>