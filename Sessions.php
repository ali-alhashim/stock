<?php
session_start();
function Message()
{

 if(isset($_SESSION["ErrorMessage"]))
 {
   $output = "<div class=\"alert-danger\">";

   $output.=htmlentities($_SESSION["ErrorMessage"]);
     $output.="</div>";
     $_SESSION["ErrorMessage"]=null;

     return $output;
 }
   if(isset($_SESSION["SuccessMessage"]))
    {
        $output = "<div class=\"alert-success\">";

        $output.=htmlentities($_SESSION["SuccessMessage"]);
        $output.="</div>";
        $_SESSION["SuccessMessage"]=null;

        return $output;
    }

}
?>
