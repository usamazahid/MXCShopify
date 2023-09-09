<?php 
$pgtitle = "Slip";
include('inc/header.php');

$path = $_SERVER['DOCUMENT_ROOT'];

require_once($path."/api/dbcon.php");

date_default_timezone_set("Asia/Karachi");
?>

<style type="text/css"> 
  body{
   
    padding: 50px;
  }
  @page {
    margin: 0;
  }
  *{
    font-family: "Helvetica Neue",Helvetica,Arial,sans-serif;
    color:#333;
    font-size: 11px;
    font-weight:bold;
  }
  h1, h2, h3, h4, h5, h6 {
    font-family: inherit;
    font-weight: 500;
    line-height: 1.1;
    color: inherit;
  }
  h2{
    font-size: 25px;
  }
  h3{
    font-size: 25px;
  }

  .borders {
    border-left: 1px dashed #ccc;
    border-right: 1px dashed #ccc;
  }
  .header{

    text-align: center;
    vertical-align: middle;
    line-height: 100px;
  }
  .payment-details{
    margin: 20px 0px;
  }
  .container{
    width: 100%;
    border: 1px solid black;
    float: left;
    padding: 1%;
    margin-bottom: 20px;
    margin top: 20px;
  }
  .text-left{
    text-align: left;
    width: 50%;
    float: left;
    display: inline-block;
  }
  .text-right{
    text-align: right;
    width: 50%;
    display: inline-block;
  }
  .text-center{
    text-align: center;
    width: 50%;
    float: left;
    display: inline-block;
  }

table {
  width: 50%;
  height: 150px;

  float: left;
 
}
td, th{

  text-align: left;
}
div {
  text-align: center;
}
.heading{
    margin:0 !important;
}

@media print  
{
    .container{
        page-break-inside: avoid;
    }
}

</style>

</head>
<body>

  <div class="header">

  </div>
  <div class="wrapper">


      <?php 
 $counter = 0;
 $uid = $_POST['uid'];
 
$cns = mysql_query("SELECT cnno FROM printcns where account = '".$uid."'");

while ($data = mysql_fetch_array($cns)){
  $counter++;
  $userdet = mysql_fetch_array(mysql_query("SELECT account, bookingdate, cnno, shippername, shippercity, shipperarea, shippercell, shipperlandline, shipperemail, pickupaddress, returnaddress, consigneename, consigneeref, consigneeemail, consigneecell, consigneeaddress, origincity, servicetype, pieces, weight, consignmenttype, consignmentdescription, consignmentremarks, holiday, specialhandling, returnservice, handcarry, timespecified, greenflyer, greenbox, bookingtime, amount, status, destcity FROM bookings WHERE cnno = '".$data[0]."' and account = '".$uid."' "));
  $servicetype = mysql_fetch_array(mysql_query("SELECT Service_Name FROM servicetype where Service_ID = '".$userdet[17]."'"));
  $cityname = mysql_fetch_array(mysql_query("SELECT CityName FROM city where CityID = '".$userdet[33]."'"));
 
echo '

 <div class="container">
  <div><h3>CONSIGNEE COPY</h3></div>
  <span><u>For Delivery Services, You Can Call Us On +92 320 4495499</u></span>
  <div class="leftpanel">
    <table>
     <tr>
       <td><img src="/member/assets/img/logo-black.png"></td>
       <td><div class="barcodeTarget" id="barcodevalue'.$counter.'">'. $userdet[2].'</div></td>   
     </tr>
     <tr>
      <td colspan="1">Shipper:</td>
      <td colspan="3">'. $userdet[3].'</td>
    </tr>
         <tr>
      <td colspan="1">City:</td>
      <td colspan="3">'. $userdet[4].'</td>
    </tr>
  
     <tr>
       <td>Customer Ref.#</td>
       <td>'. $userdet[12].'</td>
     </tr>

   </table>

    
  </div>
  <div class="rightpanel">
      <table>
     <tr>
       <td>Date</td>
       <td>'. date('m/d/y', strtotime($userdet[1])).'</td>
       <td>Time</td>
       <td>'. date('h:m:s A', strtotime($userdet[1])).'</td>
     </tr>
        <tr>
       <td>Service</td>
       <td>'. $servicetype[0].'</td>
       <td>Weight</td>
       <td>'. $userdet[19].'</td>
     </tr>
        <tr>
       <td>Fargile</td>
       <td>No</td>
       <td>Pieces</td>
       <td>'. $userdet[18].'</td>
     </tr>
        <tr>
       <td>Origin</td>
       <td>'. $userdet[4].'</td>
       <td>Destination</td>
       <td>'. $cityname[0].'</td>
     </tr>
     <tr>
       <td colspan="2">COD Amount PKR '. $userdet[31].'</td>
       <td >Decid. Ins. Value</td>
       <td >Rs, 0/-</td>
     </tr>
    <tr>
  <td colspan="1">Consignee:</td>
   <td colspan="3">'. $userdet[11].'</td>
     </tr>
     <tr>
       <td colspan="1">Contact:</td>
       <td colspan="3">'. $userdet[14].'</td>
     </tr>
     <tr>
       <td colspan="1">Address:</td>
       <td colspan="3">'. $userdet[15].'</td>
     </tr>
     <tr>
       <td>Remarks</td>
       <td colspan="3">'. $userdet[22].'</td>
     </tr>
   </table>
   <table style="clear: both; width: 100% !important; height: auto; border:1px solid black">
     <tr valign="top">
       <td>Product Details:</td>
       <td width="85%">'. $userdet[21].'</td>
     </tr>
   </table>
   
  </div>
   <div style="text-align: center; clear:both;">
     SPECIAL NOTE for CONSIGNEE: (1) Please don’t accept, if shipment is not intact. (2) Please don’t open the parcel before payment. (3)
Incase of any defects/complaints in parcel, please contact the shipper/brand. MXC is not responsible for any defect.

   </div>
    
 </div>

';}
include('inc/footer.php');
?>

<script type="text/javascript" src="inc/scripts/barcode/jquery-barcode.js"></script>
<script type="text/javascript">
window.onload = function() { window.print(); }
           function generateBarcode(element, value){
        
        
        var btype = "code128";
        var renderer = "css";

        var settings = {
          output:renderer,
          bgColor: "#FFFFFF",
          color:"#000000",
          barWidth: 1,
          barHeight: 50,
          moduleSize: 5,
          posX: 10,
          posY: 20,
          addQuietZone: 1
        };
       
     
          $('#'+element).html("").show().barcode(value, btype, settings);

      }
          

      
      $(function(){
var counnter = 0;
        $('.barcodeTarget').each(function(){
          counnter++;
        generateBarcode('barcodevalue' + counnter, $(this).text());
        });
      });
 </script>
</body>
</html>