<?php
require 'DBconnect.php';
require 'Sessions.php';



 $searchByX = "";
 $username= $_POST["username"];
 
 $pass = $_POST["pass"];

$sql = "select * from users where username ='$username' and pass='$pass';";

$result=$pdo->query($sql);

    if($result->rowCount() > 0)
    {
        //the login correct
    }
    else
    {
        //back
        $_SESSION["ErrorMessage"]="invalid username and password !";
        header("Location: index.php");

    }

?>


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

<?php 
// limit row show
$limitX = "";
if(isset($_POST["fromX"]))
{
    $limitX = "limit $_POST[fromX], $_POST[toX]"; 
}
else
{
    $limitX = "";   
}

if(isset($_POST["ShowAllItems"]))
{
    $limitX = "";
}


?>


<?php
     // save----------------------------------------
if(!empty($_POST["TotalRow"]))
{    // save -> send to database the cost price and total cost
    $totalRow = $_POST["TotalRow"];
    
    $ix =0;
   
    for($i=1;$i <= $totalRow ; $i++ )
    {
        if(isset($_POST["ch$i"]))
        {
           try
           {
            $sql = "update items set ucost='".$_POST["rowCost$i"]."', tcost='".$_POST["rowTotal$i"]."' where barcode='".$_POST["rowKay$i"]."';";
           
           // $pdo->exec($sql);

           
           $stmt = $pdo->prepare($sql);
           $stmt->execute();
           $stmt  = null;
           $ix++;
            
           }
           catch(PDOException $e)
            {
             echo($e->getMessage());
             echo($pdo->errorInfo());
            }
        }
       
        if(isset($_FILES["XuploadPhoto$i"]) && !empty($_FILES["XuploadPhoto$i"]))
        {
            $target_file = "images/".$_POST["rowKay$i"].".jpg";
            move_uploaded_file($_FILES["XuploadPhoto$i"]["tmp_name"], $target_file);
        }
    
    }

    echo ("Total items saved :  $ix");
}

?>






<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cpanel</title>
    <link href="Style_index.css" type="text/css" rel="stylesheet">
    <script src="qrcode.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
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
    thead
    {
       position: sticky; 
       top:25px;
       background-color: rgb(7, 137, 160);
    }
    .showOnly
    {
       background-color: rgb(145, 145, 91); 
       border: solid 1px black;
       margin-left: 10px;
       margin-right: 10px;
       padding-left: 5px;
       padding-right: 5px;
    }
        </style>
</head>
<body class="bodyBackground">
<div class="navBar">

<form action="Cpanel.php" method="POST" class="showOnly">
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
<?php
 echo ("<input type='hidden' name='username' value='$username'>");
 echo ("<input type='hidden' name='pass' value='$pass'>");
?>
</form>


<form action="Cpanel.php" method="POST" class="showOnly">
    From:<?php 
    if(isset($_POST["fromX"]))
    {
        echo("<input type='text' name='fromX' value='$_POST[fromX]'>"); 
    }
    else
    {
        echo("<input type='text' name='fromX' value='0'>"); 
    }
    
    ?>
      TO:<?php
      if(isset($_POST["toX"]))
      {
        echo("<input type='text' name='toX' value='$_POST[toX]'>");
      }
      else
      {
        echo("<input type='text' name='toX' value='100'>");
      }
      
      
      ?>
      <input type="submit" value="show">
<?php
 echo ("<input type='hidden' name='username' value='$username'>");
 echo ("<input type='hidden' name='pass' value='$pass'>");
?>  
</form>


<form action="Cpanel.php" method="POST" class="showOnly">
<input type="submit" value="Show All" name="ShowAllItems">
<?php

 echo ("<input type='hidden' name='username' value='$username'>");
 echo ("<input type='hidden' name='pass' value='$pass'>");
?>  
</form>


<form action="Cpanel.php" method="POST" enctype="multipart/form-data">


    


    
     <input type="button" name="selectALL" value="Select All" onclick="selectAllFunc()">
     <input type="button" name="DeleteSelected" value="Delete selected">
     <input type="submit" name="Save" value="Save">
     <input type="button" name="ResetPassword" value="reset password">
     
     <input type="button" name="brandList" value="Brand List">
     <input type="button" name="LocationList" value="Location List">
    </div>
    <table class="listTable">
<thead>
    <tr>
        <td>#</td>
        <td>SN</td>
        <td>Barcode</td>
        <td>Part No</td>
        <td>Descaption</td>
        <td>Brand</td>
        <td>Location</td>
        <td>Stock</td>
        <td>Unit cost</td>
        <td>Total cost</td>
        <td>image</td>
    </tr>
</thead>
<tbody>


<script>
  var overAllCost = 0;       
</script>

    <?php
    $sql = "SELECT * FROM items  $searchByX  $limitX";
    $result=$pdo->query($sql);

    if($result->rowCount() > 0)
    {




        echo ("<input type='hidden' name='username' value='$username'>");
        echo ("<input type='hidden' name='pass' value='$pass'>");
        
        // we have the item
       
         
         $i=0;
         
         while($row = $result->fetch())
         {
             $i++;
             echo ('<tr>');
             echo ("<td><input type='checkbox' name='ch$i' value='".$row["barcode"] ."' class='checkboxC'></td>");
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
             echo ("<td><input type='text' name='rowStock$i' value='". $row["stock"] ."' id='stock_".$row["barcode"]."' ></td>");

             echo ("<td><input type='text' name='rowCost$i' value='". $row["ucost"] ."' id='ucost_". $row["barcode"] ."' onchange=\"totalcostFun('ucost_$row[barcode]','stock_$row[barcode]','tcost_$row[barcode]')\"></td>");
             echo ("<td><input type='text' name='rowTotal$i' value='". $row["tcost"] ."' id='tcost_".$row["barcode"]."' ></td>");

             echo ("<td> <img src='". $row["image"] ."' onclick='uploadePhotoFun($i)' id='image$i'>  <input type='file' accept='.png, .jpg, .jpeg'  id='uploadPhoto$i' name='XuploadPhoto$i' hidden='' onchange='setImageFun($i)'>                             </td>");

             echo("<input type='hidden' value='$row[barcode]' name='rowKay$i'>");
             echo("</tr>");
         }

         echo("<input type='hidden' value='$i' name='TotalRow' id='TotalRowID'>");

    }
    ?>
     

</form>
    <tr>
        <td colspan="9" style="text-align: right; font-weight: bolder; font-family: Arial, Helvetica, sans-serif; background-color: rgb(238, 109, 109);" > overall cost <button type="button" onclick="overallCostFun()">=</button> </td>

        <td id="overCost">0</td>
   </tr>
</tbody>
 </table>


 <script>
    function uploadePhotoFun(i)
    {
        document.getElementById('uploadPhoto'+i).click();
       
    }
    function setImageFun(i)
    {
        let reader = new FileReader();
        var x =i;
        reader.readAsDataURL( document.getElementById('uploadPhoto'+i).files[0]);


       // document.getElementById('image'+i).src= ;
        //console.log(document.getElementById('image'+i).src= document.getElementById('uploadPhoto'+i).value);
       
        reader.onload= function (e) {
                //alert(e.target.result);
                $('#image'+x).attr('src', e.target.result);
              
            }
            
           
               
               
            


    }

   
</script>






<script>
    function totalcostFun(stock,ucost,tcost)
    {
       
      let Xstock =  document.getElementById(stock).value;
      
      let  Xucost =   document.getElementById(ucost).value;
      
      document.getElementById(tcost).value = Xstock * Xucost;
      
      overAllCost = overAllCost + parseInt(document.getElementById(tcost).value);
      
      document.getElementById("overCost").innerText = overAllCost;



    }


    function overallCostFun()
    {
        //overall Cost

        let totalRowShow = document.getElementById("TotalRowID").value;
        let x =0;
        let overAllCost = 0;
        while(x <=totalRowShow)
        {
            x=x+1; 

            let t = document.getElementsByName("rowTotal"+x)[0].value;
           overAllCost = overAllCost + parseInt(t);
            

          //console.log(overAllCost=overAllCost+parseInt(t));
          document.getElementById("overCost").innerText = overAllCost;
        }
        

    }

   
</script>




<script>
    function selectAllFunc()
    {
        var inputs = document.querySelectorAll("input[type='checkbox']");
        for(var i = 0; i < inputs.length; i++) {

            if(inputs[i].checked == true)
            {
                inputs[i].checked = false; 
            }
            else
            {
                inputs[i].checked = true;  
            }
            
        }


    }
</script>
</body>
</html>