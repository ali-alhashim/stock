
<?php
 require 'DBconnect.php';
 ?>  

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="qrcode.js"></script>
    <link href="Style_index.css" type="text/css" rel="stylesheet">
    <title>Item List</title>

    <style>
    @media print {
  html, body {
    width: 210mm;
    height: 297mm;
  }
  /* ... the rest of the rules ... */
}

.listTable
{
    margin-left: auto;
    margin-right: auto;
    padding: 10px;
    
    text-align: center;
    width: 95%;
    border-spacing: 0;
    text-align: center;
    
}
.listTable >thead>tr>td{
    border: grey solid 1px; 
    font-weight: bolder;
    
}
img{
   height: 250px; 
   width: 250px;
}
tr:hover{
    background-color: khaki;
}
td
{
    border: grey solid 1px; 
    text-align: center;
    vertical-align: middle;
}

thead
{
    position: sticky;
  top: 25px;
  background-color: chocolate;
    
}
    </style>

</head>
<body class="bodyBackground">
 
 
 

 <?php
// if search sent -----------------
if(isset($_POST["SearchBy"]))
{
    switch($_POST["SearchBy"])
    {
        case "barcode":
            $searchByX ="where barcode like '%$_POST[search]%'";
            break;
        case "partno":
            $searchByX ="where partno like '%$_POST[search]%'";
            break;
        case "desc":
            $searchByX ="where desc like '%$_POST[search]%'";
            break;

        case "brand":
            $searchByX ="where brand like '%$_POST[search]%'";
            break;

        case "location":
            $searchByX ="where location like '%$_POST[search]%'";
            break;

            case "stock":
                $searchByX ="where stock like '%$_POST[search]%'";
                break;
    }
}
?>

 <div class="navBar">

    <form action="itemList.php" method="POST">
    Search  <select name="SearchBy">
                <option value="barcode">Barcode</option>
                <option value="partno">Part No</option>
                <option value="desc">Descaption</option>
                <option value="brand">Brand</option>
                <option value="location">Location</option>
                <option value="stock">Stock</option>
            </select>  
    : <input type="text" name="search"> 
    <input type="submit" value="search">
    </form>
    <b>......Total Items = </b>
<div id="totalItemsDiv">
    Loading ...
</div>

    </div>

 <table class="listTable">
<thead>
    <tr>
        <td>SN</td>
        <td>Barcode</td>
        <td>Part No</td>
        <td>Descaption</td>
        <td>Brand</td>
        <td>Location</td>
        <td>Stock</td>
        <td>image</td>
    </tr>
</thead>
<tbody>
    <?php
    $sql = "SELECT * FROM items $searchByX ";
    $result=$pdo->query($sql);

    if($result->rowCount() > 0)
    {
        
        // we have the item
       
         
         $i=0;
         while($row = $result->fetch())
         {
             $i++;
             echo ('<tr>');
             echo ("<td>$i</td>");
             
             echo ("<td>   <div id='qrcode$i'></div> <br> ". $row["barcode"] ."</td>");
                    
             echo ("

             <script type='text/javascript'>
             var qrcode = new QRCode(document.getElementById('qrcode$i'), {
             text: '". $row["barcode"] ."',
              width: 100,
              height: 100,
              colorDark : '#000000',
             colorLight : '#ffffff',
             correctLevel : QRCode.CorrectLevel.H
                });
              </script>

             ");


             echo ("<td>". $row["partno"] ."</td>");
             echo ("<td>". $row["desc"] ."</td>");
             echo ("<td>". $row["brand"] ."</td>");
             echo ("<td>". $row["location"] ."</td>");
             echo ("<td>". $row["stock"] ."</td>");
             echo ("<td> <img src='". $row["image"] ."'></td>");
         }

         echo("<input type='hidden' value='$i' id='totalItems'>");
    }
    ?>
</tbody>
 </table>

<script>
    let totalItemsX = document.getElementById("totalItems").value;
    
    document.getElementById("totalItemsDiv").innerText = totalItemsX;
    
</script>

</body>
</html>