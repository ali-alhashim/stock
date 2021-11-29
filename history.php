<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>history</title>
    <link rel="stylesheet" href="Style_index.css" type="text/css"/>
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
}
    </style>

</head>
<body class="bodyBackground">
<?php
$searchByX ="";
// if search sent -----------------
if(isset($_POST["FilterBy"]))
{
    switch($_POST["FilterBy"])
    {
        case "OUT":
            $searchByX ="where barcodedb.history.inout < 0";
            break;
        case "IN":
            $searchByX ="where barcodedb.history.inout > 0";
            break;
       
    }
}
?>



<div class="navBar">
<form action="history.php" method="POST">
    Search  <select name="FilterBy">
                <option value="OUT">OUT</option>
                <option value="IN">IN</option>
               
            </select>  
   
    <input type="submit" value="Filter">
    </form>
</div>






 <?php
 require 'DBconnect.php';
 ?>   
 <table class="listTable">
<thead>
    <tr>
        <td>SN</td>
        <td>Date Time</td>
        <td>Barcode</td>
        <td>Part No</td>
        <td>Descaption</td>
        <td>Brand</td>
        <td>Location</td>
        <td>Stock</td>
        <td>in & out</td>
        <td>image</td>
    </tr>
</thead>
<tbody>
    <?php
    $sql = "SELECT * FROM history $searchByX  ORDER BY datetime DESC;";
   
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
             echo ("<td>". $row["datetime"] ."</td>");
             echo ("<td>". $row["barcode"] ."</td>");
             echo ("<td>". $row["partno"] ."</td>");
             echo ("<td>". $row["desc"] ."</td>");
             echo ("<td>". $row["brand"] ."</td>");
             echo ("<td>". $row["location"] ."</td>");
             echo ("<td>". $row["stock"] ."</td>");
             echo ("<td>". $row["inout"] ."</td>");
             echo ("<td> <img src='". $row["image"] ."'></td>");
         }
    }
    ?>
</tbody>
 </table>

</body>
</html>