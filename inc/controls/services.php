<?php 
   $getservice = mysql_query("SELECT Service_ID, Service_Name FROM servicetype servicetype where service_id not in (1, 3, 4, 5)");
?>
<select size="1" class="form-control" id="service_type" name="service_type" >


<?
 if($order['payment_gateway_names'][0] == 'Cash on Delivery (COD)'){
    echo '<option value="1" selected>COD</option>';
 }else{
     echo '<option value="5" selected>Zero COD</option>';
 }
 
while($service = mysql_fetch_array($getservice)){
        echo "<option value='" . $service[0] . "'>" . $service[1] . "</option>";
}
?>
<?
      echo'</select>';
?>
